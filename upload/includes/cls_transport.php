<?php

/**
 * ECSHOP 服务器之间数据传输器。采集到的信息包括HTTP头和HTTP体，
 * 并以一维数组的形式返回，如：array('header' => 'bar', 'body' => 'foo')。
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_transport.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class transport
{
    /**
     * 脚本执行时间。－1表示采用PHP的默认值。
     *
     * @access  private
     * @var     integer     $time_limit
     */
    var $time_limit                  = -1;

    /**
     * 在多少秒之内，如果连接不可用，脚本就停止连接。－1表示采用PHP的默认值。
     *
     * @access  private
     * @var     integer     $connect_timeout
     */
    var $connect_timeout             = -1;

    /**
     * 连接后，限定多少秒超时。－1表示采用PHP的默认值。此项仅当采用CURL库时启用。
     *
     * @access  private
     * @var     integer    $stream_timeout
     */
    var $stream_timeout              = -1;

    /**
     * 是否使用CURL库来连接。false表示采用fsockopen进行连接。
     *
     * @access  private
     * @var     boolean     $use_curl
     */
    var $use_curl                    = false;

    /**
     * 构造函数
     *
     * @access  public
     * @param   integer     $time_limit
     * @param   integer     $connect_timeout
     * @param   integer     $stream_timeout
     * @param   boolean     $use_curl
     * @return  void
     */
    function __construct($time_limit = -1, $connect_timeout = -1, $stream_timeout = -1, $use_curl = false)
    {
        $this->transport($time_limit, $connect_timeout, $stream_timeout, $use_curl);
    }

    /**
     * 构造函数
     *
     * @access  public
     * @param   integer     $time_limit
     * @param   integer     $connect_timeout
     * @param   integer     $stream_timeout
     * @param   boolean     $use_curl
     * @return  void
     */
    function transport($time_limit = -1, $connect_timeout = -1, $stream_timeout = -1, $use_curl = false)
    {
        $this->time_limit = $time_limit;
        $this->connect_timeout = $connect_timeout;
        $this->stream_timeout = $stream_timeout;
        $this->use_curl = $use_curl;
    }

    /**
     * 请求远程服务器
     *
     * @access  public
     * @param   string      $url            远程服务器的URL
     * @param   mix         $params         查询参数，形如bar=foo&foo=bar；或者是一维关联数组，形如array('a'=>'aa',...)
     * @param   string      $method         请求方式，是POST还是GET
     * @param   array       $my_header      用户要发送的头部信息，为一维关联数组，形如array('a'=>'aa',...)
     * @return  array                       成功返回一维关联数组，形如array('header'=>'bar', 'body'=>'foo')，
     *                                      重大错误程序直接停止运行，否则返回false。
     */
    function request($url, $params = '', $method = 'POST', $my_header = '')
    {
        $fsock_exists = function_exists('fsockopen');
        $curl_exists = function_exists('curl_init');

        if (!$fsock_exists && !$curl_exists)
        {
            die('No method available!');
        }

        if (!$url)
        {
            die('Invalid url!');
        }

        if ($this->time_limit > -1)//如果为0，不限制执行时间
        {
            set_time_limit($this->time_limit);
        }

        $method = $method === 'GET' ? $method : 'POST';
        $response = '';
        $temp_str = '';

        /* 格式化将要发要送的参数 */
        if ($params && is_array($params))
        {
            foreach ($params AS $key => $value)
            {
                $temp_str .= '&' . $key . '=' . $value;
            }
            $params = preg_replace('/^&/', '', $temp_str);
        }

        /* 如果fsockopen存在，且用户不指定使用curl，则调用use_socket函数 */
        if ($fsock_exists && !$this->use_curl)
        {
            $response = $this->use_socket($url, $params, $method, $my_header);
        }
        /* 只要上述条件中的任一个不成立，流程就转向这里，这时如果curl模块可用，就调用use_curl函数 */
        elseif ($curl_exists)
        {
            $response = $this->use_curl($url, $params, $method, $my_header);
        }

        /* 空响应或者传输过程中发生错误，程序将返回false */
        if (!$response)
        {
            return false;
        }

        return $response;
    }

    /**
     * 使用fsockopen进行连接
     *
     * @access  private
     * @param   string      $url            远程服务器的URL
     * @param   string      $params         查询参数，形如bar=foo&foo=bar
     * @param   string      $method         请求方式，是POST还是GET
     * @param   array       $my_header      用户要发送的头部信息，为一维关联数组，形如array('a'=>'aa',...)
     * @return  array                       成功返回一维关联数组，形如array('header'=>'bar', 'body'=>'foo')，
     *                                      否则返回false。
     */
    function use_socket($url, $params, $method, $my_header)
    {
        $query = '';
        $auth = '';
        $content_type = '';
        $content_length = '';
        $request_body = '';
        $request = '';
        $http_response = '';
        $temp_str = '';
        $error = '';
        $errstr = '';
        $crlf = $this->generate_crlf();

        if ($method === 'GET')
        {
            $query = $params ? "?$params" : '';
        }
        else
        {
            $request_body  = $params;
            $content_type = 'Content-Type: application/x-www-form-urlencoded' . $crlf;
            $content_length = 'Content-Length: ' . strlen($request_body) . $crlf . $crlf;
        }

        $url_parts = $this->parse_raw_url($url);
        $path = $url_parts['path'] . $query;

        if (!empty($url_parts['user']))
        {
            $auth = 'Authorization: Basic '
                    . base64_encode($url_parts['user'] . ':' . $url_parts['pass']) . $crlf;
        }

        /* 格式化自定义头部信息 */
        if ($my_header && is_array($my_header))
        {
            foreach ($my_header AS $key => $value)
            {
                $temp_str .= $key . ': ' . $value . $crlf;
            }
            $my_header = $temp_str;
        }

        /* 构造HTTP请求头部 */
        $request = "$method $path HTTP/1.0$crlf"
                . 'Host: ' . $url_parts['host'] . $crlf
                . $auth
                . $my_header
                . $content_type
                . $content_length
                . $request_body;

        if ($this->connect_timeout > -1)
        {
            $fp = @fsockopen($url_parts['host'], $url_parts['port'], $error, $errstr, $this->connect_timeout);
        }
        else
        {
            $fp = @fsockopen($url_parts['host'], $url_parts['port'], $error, $errstr);
        }

        if (!$fp)
        {
            return false;//打开失败
        }

        if (!@fwrite($fp, $request))
        {
            return false;//写入失败
        }

        while (!feof($fp))
        {
            $http_response .= fgets($fp);
        }

        if (!$http_response)
        {
            return false;//空响应
        }

        $separator = '/\r\n\r\n|\n\n|\r\r/';
        list($http_header, $http_body) = preg_split($separator, $http_response, 2);

        $http_response = array('header' => $http_header,//header肯定有值
                               'body'   => $http_body);//body可能为空
        @fclose($fp);

        return $http_response;
    }

    /**
     * 使用curl进行连接
     *
     * @access  private
     * @param   string      $url            远程服务器的URL
     * @param   string      $params         查询参数，形如bar=foo&foo=bar
     * @param   string      $method         请求方式，是POST还是GET
     * @param   array       $my_header      用户要发送的头部信息，为一维关联数组，形如array('a'=>'aa',...)
     * @return  array                       成功返回一维关联数组，形如array('header'=>'bar', 'body'=>'foo')，
     *                                      失败返回false。
     */
    function use_curl($url, $params, $method, $my_header)
    {
        /* 开始一个新会话 */
        $curl_session = curl_init();

        /* 基本设置 */
        curl_setopt($curl_session, CURLOPT_FORBID_REUSE, true); // 处理完后，关闭连接，释放资源
        curl_setopt($curl_session, CURLOPT_HEADER, true);//结果中包含头部信息
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);//把结果返回，而非直接输出
        curl_setopt($curl_session, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);//采用1.0版的HTTP协议

        $url_parts = $this->parse_raw_url($url);

        /* 设置验证策略 */
        if (!empty($url_parts['user']))
        {
            $auth = $url_parts['user'] . ':' . $url_parts['pass'];
            curl_setopt($curl_session, CURLOPT_USERPWD, $auth);
            curl_setopt($curl_session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        $header = array();

        /* 设置主机 */
        $header[] = 'Host: ' . $url_parts['host'];

        /* 格式化自定义头部信息 */
        if ($my_header && is_array($my_header))
        {
            foreach ($my_header AS $key => $value)
            {
                $header[] = $key . ': ' . $value;
            }
        }

        if ($method === 'GET')
        {
            curl_setopt($curl_session, CURLOPT_HTTPGET, true);
            $url .= $params ? '?' . $params : '';
        }
        else
        {
            curl_setopt($curl_session, CURLOPT_POST, true);
            $header[] = 'Content-Type: application/x-www-form-urlencoded';
            $header[] = 'Content-Length: ' . strlen($params);
            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $params);
        }

        /* 设置请求地址 */
        curl_setopt($curl_session, CURLOPT_URL, $url);

        /* 设置头部信息 */
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $header);

        if ($this->connect_timeout > -1)
        {
            curl_setopt($curl_session, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        }

        if ($this->stream_timeout > -1)
        {
            curl_setopt($curl_session, CURLOPT_TIMEOUT, $this->stream_timeout);
        }

        /* 发送请求 */
        $http_response = curl_exec($curl_session);

        if (curl_errno($curl_session) != 0)
        {
            return false;
        }

        $separator = '/\r\n\r\n|\n\n|\r\r/';
        list($http_header, $http_body) = preg_split($separator, $http_response, 2);

        $http_response = array('header' => $http_header,//肯定有值
                               'body'   => $http_body); //可能为空

        curl_close($curl_session);

        return $http_response;
    }

    /**
     * Similar to PHP's builtin parse_url() function, but makes sure what the schema,
     * path and port keys are set to http, /, 80 respectively if they're missing
     *
     * @access     private
     * @param      string    $raw_url    Raw URL to be split into an array
     * @author     http://www.cpaint.net/
     * @return     array
     */
    function parse_raw_url($raw_url)
    {
        $retval   = array();
        $raw_url  = (string) $raw_url;

        // make sure parse_url() recognizes the URL correctly.
        if (strpos($raw_url, '://') === false)
        {
          $raw_url = 'http://' . $raw_url;
        }

        // split request into array
        $retval = parse_url($raw_url);

        // make sure a path key exists
        if (!isset($retval['path']))
        {
          $retval['path'] = '/';
        }

        // set port to 80 if none exists
        if (!isset($retval['port']))
        {
          $retval['port'] = '80';
        }

        return $retval;
    }

    /**
     * 产生一个换行符，不同的操作系统会有不同的换行符
     *
     * @access     private
     * @return     string       用双引号引用的换行符
     */
    function generate_crlf()
    {
        $crlf = '';

        if (strtoupper(substr(PHP_OS, 0, 3) === 'WIN'))
        {
            $crlf = "\r\n";
        }
        elseif (strtoupper(substr(PHP_OS, 0, 3) === 'MAC'))
        {
            $crlf = "\r";
        }
        else
        {
            $crlf = "\n";
        }

        return $crlf;
    }
}

?>