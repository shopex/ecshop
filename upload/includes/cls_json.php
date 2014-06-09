<?php

/**
 * ECSHOP JSON 绫
 * ===========================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com锛
 * ----------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ==========================================================
 * $Author: liubo $
 * $Id: cls_json.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

if (!defined('EC_CHARSET'))
{
    define('EC_CHARSET', 'utf-8');
}

class JSON
{
    var $at   = 0;
    var $ch   = '';
    var $text = '';

    function encode($arg, $force = true)
    {
        static $_force;
        if (is_null($_force))
        {
            $_force = $force;
        }

        if ($_force && EC_CHARSET == 'utf-8' && function_exists('json_encode'))
        {
            return json_encode($arg);
        }

        $returnValue = '';
        $c           = '';
        $i           = '';
        $l           = '';
        $s           = '';
        $v           = '';
        $numeric     = true;

        switch (gettype($arg))
        {
            case 'array':
                foreach ($arg AS $i => $v)
                {
                    if (!is_numeric($i))
                    {
                        $numeric = false;
                        break;
                    }
                }

                if ($numeric)
                {
                    foreach ($arg AS $i => $v)
                    {
                        if (strlen($s) > 0)
                        {
                            $s .= ',';
                        }
                        $s .= $this->encode($arg[$i]);
                    }

                    $returnValue = '[' . $s . ']';
                }
                else
                {
                    foreach ($arg AS $i => $v)
                    {
                        if (strlen($s) > 0)
                        {
                            $s .= ',';
                        }
                        $s .= $this->encode($i) . ':' . $this->encode($arg[$i]);
                    }

                    $returnValue = '{' . $s . '}';
                }
                break;

            case 'object':
                foreach (get_object_vars($arg) AS $i => $v)
                {
                    $v = $this->encode($v);

                    if (strlen($s) > 0)
                    {
                        $s .= ',';
                    }
                    $s .= $this->encode($i) . ':' . $v;
                }

                $returnValue = '{' . $s . '}';
                break;

            case 'integer':
            case 'double':
                $returnValue = is_numeric($arg) ? (string) $arg : 'null';
                break;

            case 'string':
                $returnValue = '"' . strtr($arg, array(
                    "\r"   => '\\r',    "\n"   => '\\n',    "\t"   => '\\t',     "\b"   => '\\b',
                    "\f"   => '\\f',    '\\'   => '\\\\',   '"'    => '\"',
                    "\x00" => '\u0000', "\x01" => '\u0001', "\x02" => '\u0002', "\x03" => '\u0003',
                    "\x04" => '\u0004', "\x05" => '\u0005', "\x06" => '\u0006', "\x07" => '\u0007',
                    "\x08" => '\b',     "\x0b" => '\u000b', "\x0c" => '\f',     "\x0e" => '\u000e',
                    "\x0f" => '\u000f', "\x10" => '\u0010', "\x11" => '\u0011', "\x12" => '\u0012',
                    "\x13" => '\u0013', "\x14" => '\u0014', "\x15" => '\u0015', "\x16" => '\u0016',
                    "\x17" => '\u0017', "\x18" => '\u0018', "\x19" => '\u0019', "\x1a" => '\u001a',
                    "\x1b" => '\u001b', "\x1c" => '\u001c', "\x1d" => '\u001d', "\x1e" => '\u001e',
                    "\x1f" => '\u001f'
                )) . '"';
                break;

            case 'boolean':
                $returnValue = $arg?'true':'false';
                break;

            default:
                $returnValue = 'null';
        }

        return $returnValue;
    }

    function decode($text,$type=0) // 榛樿?type=0杩斿洖obj,type=1杩斿洖array
    {
        if (empty($text))
        {
            return '';
        }
        elseif (!is_string($text))
        {
            return false;
        }

        if (EC_CHARSET === 'utf-8' && function_exists('json_decode'))
        {
            return addslashes_deep_obj(json_decode(stripslashes($text),$type));
        }

        $this->at   = 0;
        $this->ch   = '';
        $this->text = strtr(stripslashes($text), array(
                "\r"   => '', "\n"   => '', "\t"   => '', "\b"   => '',
                "\x00" => '', "\x01" => '', "\x02" => '', "\x03" => '',
                "\x04" => '', "\x05" => '', "\x06" => '', "\x07" => '',
                "\x08" => '', "\x0b" => '', "\x0c" => '', "\x0e" => '',
                "\x0f" => '', "\x10" => '', "\x11" => '', "\x12" => '',
                "\x13" => '', "\x14" => '', "\x15" => '', "\x16" => '',
                "\x17" => '', "\x18" => '', "\x19" => '', "\x1a" => '',
                "\x1b" => '', "\x1c" => '', "\x1d" => '', "\x1e" => '',
                "\x1f" => ''
        ));

        $this->next();
        $return = $this->val();

        $result = empty($type) ? $return : $this->object_to_array($return);

        return addslashes_deep_obj($result);
    }

    /**
     * triggers a PHP_ERROR
     *
     * @access   private
     * @param    string    $m    error message
     *
     * @return   void
     */
    function error($m)
    {
        echo($m . ' at offset ' . $this->at . ': ' . $this->text);
    }

    /**
     * returns the next character of a JSON string
     *
     * @access  private
     *
     * @return  string
     */
    function next()
    {
        $this->ch = !isset($this->text{$this->at}) ? '' : $this->text{$this->at};
        $this->at++;

        return $this->ch;
    }

    /**
     * handles strings
     *
     * @access  private
     *
     * @return  void
     */
    function str()
    {
        $i = '';
        $s = '';
        $t = '';
        $u = '';

        if ($this->ch == '"')
        {
            while ($this->next() !== null)
            {
                if ($this->ch == '"')
                {
                    $this->next();

                    return $s;
                }
                elseif ($this->ch == '\\')
                {
                    switch ($this->next())
                    {
                        case 'b':
                            $s .= '\b';
                            break;

                        case 'f':
                            $s .= '\f';
                            break;

                        case 'n':
                            $s .= '\n';
                            break;

                        case 'r':
                            $s .= '\r';
                            break;

                        case 't':
                            $s .= '\t';
                            break;

                        case 'u':
                            $u = 0;

                            for ($i = 0; $i < 4; $i++)
                            {
                                $t = (integer) sprintf('%01c', hexdec($this->next()));

                                if (!is_numeric($t))
                                {
                                    break 2;
                                }
                                $u = $u * 16 + $t;
                            }

                            $s .= chr($u);
                            break;
                        case '\'':
                            $s .= '\'';
                            break;
                        default:
                            $s .= $this->ch;
                    }
                }
                else
                {
                    $s .= $this->ch;
                }
            }
        }

        $this->error('Bad string');
    }

    /**
     * handless arrays
     *
     * @access  private
     *
     * @return  void
     */
    function arr()
    {
        $a = array();

        if ($this->ch == '[')
        {
            $this->next();

            if ($this->ch == ']')
            {
                $this->next();

                return $a;
            }

            while (isset($this->ch))
            {
                array_push($a, $this->val());

                if ($this->ch == ']')
                {
                    $this->next();

                    return $a;

                }
                elseif ($this->ch != ',')
                {
                    break;
                }

                $this->next();

            }

            $this->error('Bad array');
        }
    }

    /**
     * handles objects
     *
     * @access  public
     *
     * @return  void
     */
    function obj()
    {
        $k = '';
        $o = new StdClass();

        if ($this->ch == '{')
        {
            $this->next();

            if ($this->ch == '}')
            {
                $this->next();

                return $o;
            }

            while ($this->ch)
            {
                $k = $this->str();

                if ($this->ch != ':')
                {
                    break;
                }

                $this->next();
                $o->$k = $this->val();

                if ($this->ch == '}')
                {
                    $this->next();

                    return $o;
                }
                elseif ($this->ch != ',')
                {
                    break;
                }

                $this->next();
            }
        }

        $this->error('Bad object');
    }

    /**
     * handles objects
     *
     * @access  public
     *
     * @return  void
     */
    function assoc()
    {
        $k = '';
        $a = array();

        if ($this->ch == '<')
        {
            $this->next();

            if ($this->ch == '>')
            {
                $this->next();

                return $a;
            }

            while ($this->ch)
            {
                $k = $this->str();

                if ($this->ch != ':')
                {
                    break;
                }

                $this->next();
                $a[$k] = $this->val();

                if ($this->ch == '>')
                {
                    $this->next();

                    return $a;
                }
                elseif ($this->ch != ',')
                {
                    break;
                }

                $this->next();
            }
        }

        $this->error('Bad associative array');
    }

    /**
     * handles numbers
     *
     * @access  private
     *
     * @return  void
     */
    function num()
    {
        $n = '';
        $v = '';

        if ($this->ch == '-')
        {
            $n = '-';
            $this->next();
        }

        while ($this->ch >= '0' && $this->ch <= '9')
        {
            $n .= $this->ch;
            $this->next();
        }

        if ($this->ch == '.')
        {
            $n .= '.';

            while ($this->next() && $this->ch >= '0' && $this->ch <= '9')
            {
                $n .= $this->ch;
            }
        }

        if ($this->ch == 'e' || $this->ch == 'E')
        {
            $n .= 'e';
            $this->next();

            if ($this->ch == '-' || $this->ch == '+')
            {
                $n .= $this->ch;
                $this->next();
            }

            while ($this->ch >= '0' && $this->ch <= '9')
            {
                $n .= $this->ch;
                $this->next();
            }
        }

        $v += $n;

        if (!is_numeric($v))
        {
            $this->error('Bad number');
        }
        else
        {
            return $v;
        }
    }

    /**
     * handles words
     *
     * @access  private
     *
     * @return  mixed
     */
    function word()
    {
        switch ($this->ch)
        {
            case 't':

                if ($this->next() == 'r' && $this->next() == 'u' && $this->next() == 'e')
                {
                    $this->next();

                    return true;
                }
                break;

            case 'f':
                if ($this->next() == 'a' && $this->next() == 'l' && $this->next() == 's' && $this->next() == 'e')
                {
                    $this->next();

                    return false;
                }
                break;

            case 'n':
                if ($this->next() == 'u' && $this->next() == 'l' && $this->next() == 'l')
                {
                    $this->next();

                    return null;
                }
                break;
        }

        $this->error('Syntax error');
    }

    /**
     * generic value handler
     *
     * @access  private
     *
     * @return  mixed
     */
    function val()
    {
        switch ($this->ch)
        {
            case '{':
                return $this->obj();

            case '[':
                return $this->arr();

            case '<':
                return $this->assoc();

            case '"':
                return $this->str();

            case '-':
                return $this->num();

            default:
                return ($this->ch >= '0' && $this->ch <= '9') ? $this->num() : $this->word();
        }
    }

    /**
     * Gets the properties of the given object recursion
     *
     * @access private
     *
     * @return array
     */
    function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val)
        {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
}

?>