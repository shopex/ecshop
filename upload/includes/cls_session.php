<?php

/**
 * ECSHOP SESSION 鍏?敤绫诲簱
 * ============================================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com锛
 * ----------------------------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_session.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class cls_session
{
    var $db             = NULL;
    var $session_table  = '';

    var $max_life_time  = 1800; // SESSION 杩囨湡鏃堕棿

    var $session_name   = '';
    var $session_id     = '';

    var $session_expiry = '';
    var $session_md5    = '';

    var $session_cookie_path   = '/';
    var $session_cookie_domain = '';
    var $session_cookie_secure = false;

    var $_ip   = '';
    var $_time = 0;

    function __construct(&$db, $session_table, $session_data_table, $session_name = 'ECS_ID', $session_id = '')
    {
        $this->cls_session($db, $session_table, $session_data_table, $session_name, $session_id);
    }

    function cls_session(&$db, $session_table, $session_data_table, $session_name = 'ECS_ID', $session_id = '')
    {
        $GLOBALS['_SESSION'] = array();

        if (!empty($GLOBALS['cookie_path']))
        {
            $this->session_cookie_path = $GLOBALS['cookie_path'];
        }
        else
        {
            $this->session_cookie_path = '/';
        }

        if (!empty($GLOBALS['cookie_domain']))
        {
            $this->session_cookie_domain = $GLOBALS['cookie_domain'];
        }
        else
        {
            $this->session_cookie_domain = '';
        }

        if (!empty($GLOBALS['cookie_secure']))
        {
            $this->session_cookie_secure = $GLOBALS['cookie_secure'];
        }
        else
        {
            $this->session_cookie_secure = false;
        }

        $this->session_name       = $session_name;
        $this->session_table      = $session_table;
        $this->session_data_table = $session_data_table;

        $this->db  = &$db;
        $this->_ip = real_ip();

        if ($session_id == '' && !empty($_COOKIE[$this->session_name]))
        {
            $this->session_id = $_COOKIE[$this->session_name];
        }
        else
        {
            $this->session_id = $session_id;
        }

        if ($this->session_id)
        {
            $tmp_session_id = substr($this->session_id, 0, 32);
            if ($this->gen_session_key($tmp_session_id) == substr($this->session_id, 32))
            {
                $this->session_id = $tmp_session_id;
            }
            else
            {
                $this->session_id = '';
            }
        }

        $this->_time = time();

        if ($this->session_id)
        {
            $this->load_session();
        }
        else
        {
            $this->gen_session_id();

            setcookie($this->session_name, $this->session_id . $this->gen_session_key($this->session_id), 0, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);
        }

        register_shutdown_function(array(&$this, 'close_session'));
    }

    function gen_session_id()
    {
        $this->session_id = md5(uniqid(mt_rand(), true));

        return $this->insert_session();
    }

    function gen_session_key($session_id)
    {
        static $ip = '';

        if ($ip == '')
        {
            $ip = substr($this->_ip, 0, strrpos($this->_ip, '.'));
        }

        return sprintf('%08x', crc32(ROOT_PATH . $ip . $session_id));
    }

    function insert_session()
    {
        return $this->db->query('INSERT INTO ' . $this->session_table . " (sesskey, expiry, ip, data) VALUES ('" . $this->session_id . "', '". $this->_time ."', '". $this->_ip ."', 'a:0:{}')");
    }

    function load_session()
    {
        $session = $this->db->getRow('SELECT userid, adminid, user_name, user_rank, discount, email, data, expiry FROM ' . $this->session_table . " WHERE sesskey = '" . $this->session_id . "'");
        if (empty($session))
        {
            $this->insert_session();

            $this->session_expiry = 0;
            $this->session_md5    = '40cd750bba9870f18aada2478b24840a';
            $GLOBALS['_SESSION']  = array();
        }
        else
        {
            if (!empty($session['data']) && $this->_time - $session['expiry'] <= $this->max_life_time)
            {
                $this->session_expiry = $session['expiry'];
                $this->session_md5    = md5($session['data']);
                $GLOBALS['_SESSION']  = unserialize($session['data']);
                $GLOBALS['_SESSION']['user_id'] = $session['userid'];
                $GLOBALS['_SESSION']['admin_id'] = $session['adminid'];
                $GLOBALS['_SESSION']['user_name'] = $session['user_name'];
                $GLOBALS['_SESSION']['user_rank'] = $session['user_rank'];
                $GLOBALS['_SESSION']['discount'] = $session['discount'];
                $GLOBALS['_SESSION']['email'] = $session['email'];
            }
            else
            {
                $session_data = $this->db->getRow('SELECT data, expiry FROM ' . $this->session_data_table . " WHERE sesskey = '" . $this->session_id . "'");
                if (!empty($session_data['data']) && $this->_time - $session_data['expiry'] <= $this->max_life_time)
                {
                    $this->session_expiry = $session_data['expiry'];
                    $this->session_md5    = md5($session_data['data']);
                    $GLOBALS['_SESSION']  = unserialize($session_data['data']);
                    $GLOBALS['_SESSION']['user_id'] = $session['userid'];
                    $GLOBALS['_SESSION']['admin_id'] = $session['adminid'];
                    $GLOBALS['_SESSION']['user_name'] = $session['user_name'];
                    $GLOBALS['_SESSION']['user_rank'] = $session['user_rank'];
                    $GLOBALS['_SESSION']['discount'] = $session['discount'];
                    $GLOBALS['_SESSION']['email'] = $session['email'];
                }
                else
                {
                    $this->session_expiry = 0;
                    $this->session_md5    = '40cd750bba9870f18aada2478b24840a';
                    $GLOBALS['_SESSION']  = array();
                }
            }
        }
    }

    function update_session()
    {
        $adminid = !empty($GLOBALS['_SESSION']['admin_id']) ? intval($GLOBALS['_SESSION']['admin_id']) : 0;
        $userid  = !empty($GLOBALS['_SESSION']['user_id'])  ? intval($GLOBALS['_SESSION']['user_id'])  : 0;
        $user_name  = !empty($GLOBALS['_SESSION']['user_name'])  ? trim($GLOBALS['_SESSION']['user_name'])  : 0;
        $user_rank  = !empty($GLOBALS['_SESSION']['user_rank'])  ? intval($GLOBALS['_SESSION']['user_rank'])  : 0;
        $discount  = !empty($GLOBALS['_SESSION']['discount'])  ? round($GLOBALS['_SESSION']['discount'], 2)  : 0;
        $email  = !empty($GLOBALS['_SESSION']['email'])  ? trim($GLOBALS['_SESSION']['email'])  : 0;
        unset($GLOBALS['_SESSION']['admin_id']);
        unset($GLOBALS['_SESSION']['user_id']);
        unset($GLOBALS['_SESSION']['user_name']);
        unset($GLOBALS['_SESSION']['user_rank']);
        unset($GLOBALS['_SESSION']['discount']);
        unset($GLOBALS['_SESSION']['email']);

        $data        = serialize($GLOBALS['_SESSION']);
        $this->_time = time();

        if ($this->session_md5 == md5($data) && $this->_time < $this->session_expiry + 10)
        {
            return true;
        }

        $data = addslashes($data);

        if (isset($data{255}))
        {
            $this->db->autoReplace($this->session_data_table, array('sesskey' => $this->session_id, 'expiry' => $this->_time, 'data' => $data), array('expiry' => $this->_time,'data' => $data));

            $data = '';
        }

        return $this->db->query('UPDATE ' . $this->session_table . " SET expiry = '" . $this->_time . "', ip = '" . $this->_ip . "', userid = '" . $userid . "', adminid = '" . $adminid . "', user_name='" . $user_name . "', user_rank='" . $user_rank . "', discount='" . $discount . "', email='" . $email . "', data = '$data' WHERE sesskey = '" . $this->session_id . "' LIMIT 1");
    }

    function close_session()
    {
        $this->update_session();

        /* 闅忔満瀵 sessions_data 鐨勫簱杩涜?鍒犻櫎鎿嶄綔 */
        if (mt_rand(0, 2) == 2)
        {
            $this->db->query('DELETE FROM ' . $this->session_data_table . ' WHERE expiry < ' . ($this->_time - $this->max_life_time));
        }

        if ((time() % 2) == 0)
        {
            return $this->db->query('DELETE FROM ' . $this->session_table . ' WHERE expiry < ' . ($this->_time - $this->max_life_time));
        }

        return true;
    }

    function delete_spec_admin_session($adminid)
    {
        if (!empty($GLOBALS['_SESSION']['admin_id']) && $adminid)
        {
            return $this->db->query('DELETE FROM ' . $this->session_table . " WHERE adminid = '$adminid'");
        }
        else
        {
            return false;
        }
    }

    function destroy_session()
    {
        $GLOBALS['_SESSION'] = array();

        setcookie($this->session_name, $this->session_id, 1, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);

        /* ECSHOP 鑷?畾涔夋墽琛岄儴鍒 */
        if (!empty($GLOBALS['ecs']))
        {
            $this->db->query('DELETE FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE session_id = '$this->session_id'");
        }
        /* ECSHOP 鑷?畾涔夋墽琛岄儴鍒 */

        $this->db->query('DELETE FROM ' . $this->session_data_table . " WHERE sesskey = '" . $this->session_id . "' LIMIT 1");

        return $this->db->query('DELETE FROM ' . $this->session_table . " WHERE sesskey = '" . $this->session_id . "' LIMIT 1");
    }

    function get_session_id()
    {
        return $this->session_id;
    }

    function get_users_count()
    {
        return $this->db->getOne('SELECT count(*) FROM ' . $this->session_table);
    }
}

?>