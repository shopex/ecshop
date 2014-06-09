<?php

/**
 * ECSHOP 后台对上传文件的处理类(实现图片上传，图片缩小， 增加水印)
 * 需要定义以下常量
 *  define('ERR_INVALID_IMAGE',             1);
 *  define('ERR_NO_GD',                     2);
 *  define('ERR_IMAGE_NOT_EXISTS',          3);
 *  define('ERR_DIRECTORY_READONLY',        4);
 *  define('ERR_UPLOAD_FAILURE',            5);
 *  define('ERR_INVALID_PARAM',             6);
 *  define('ERR_INVALID_IMAGE_TYPE',        7);
 *  define('ROOT_PATH',                     '网站根目录')
 *
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_image.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class cls_image
{
    var $error_no    = 0;
    var $error_msg   = '';
    var $images_dir  = IMAGE_DIR;
    var $data_dir    = DATA_DIR;
    var $bgcolor     = '';
    var $type_maping = array(1 => 'image/gif', 2 => 'image/jpeg', 3 => 'image/png');

    function __construct($bgcolor='')
    {
        $this->cls_image($bgcolor);
    }

    function cls_image($bgcolor='')
    {
        if ($bgcolor)
        {
            $this->bgcolor = $bgcolor;
        }
        else
        {
            $this->bgcolor = "#FFFFFF";
        }
    }

    /**
     * 图片上传的处理函数
     *
     * @access      public
     * @param       array       upload       包含上传的图片文件信息的数组
     * @param       array       dir          文件要上传在$this->data_dir下的目录名。如果为空图片放在则在$this->images_dir下以当月命名的目录下
     * @param       array       img_name     上传图片名称，为空则随机生成
     * @return      mix         如果成功则返回文件名，否则返回false
     */
    function upload_image($upload, $dir = '', $img_name = '')
    {
        /* 没有指定目录默认为根目录images */
        if (empty($dir))
        {
            /* 创建当月目录 */
            $dir = date('Ym');
            $dir = ROOT_PATH . $this->images_dir . '/' . $dir . '/';
        }
        else
        {
            /* 创建目录 */
            $dir = ROOT_PATH . $this->data_dir . '/' . $dir . '/';
            if ($img_name)
            {
                $img_name = $dir . $img_name; // 将图片定位到正确地址
            }
        }

        /* 如果目标目录不存在，则创建它 */
        if (!file_exists($dir))
        {
            if (!make_dir($dir))
            {
                /* 创建目录失败 */
                $this->error_msg = sprintf($GLOBALS['_LANG']['directory_readonly'], $dir);
                $this->error_no  = ERR_DIRECTORY_READONLY;

                return false;
            }
        }

        if (empty($img_name))
        {
            $img_name = $this->unique_name($dir);
            $img_name = $dir . $img_name . $this->get_filetype($upload['name']);
        }

        if (!$this->check_img_type($upload['type']))
        {
            $this->error_msg = $GLOBALS['_LANG']['invalid_upload_image_type'];
            $this->error_no  =  ERR_INVALID_IMAGE_TYPE;
            return false;
        }

        /* 允许上传的文件类型 */
        $allow_file_types = '|GIF|JPG|JEPG|PNG|BMP|SWF|';
        if (!check_file_type($upload['tmp_name'], $img_name, $allow_file_types))
        {
            $this->error_msg = $GLOBALS['_LANG']['invalid_upload_image_type'];
            $this->error_no  =  ERR_INVALID_IMAGE_TYPE;
            return false;
        }

        if ($this->move_file($upload, $img_name))
        {
            return str_replace(ROOT_PATH, '', $img_name);
        }
        else
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['upload_failure'], $upload['name']);
            $this->error_no  = ERR_UPLOAD_FAILURE;

            return false;
        }
    }

    /**
     * 创建图片的缩略图
     *
     * @access  public
     * @param   string      $img    原始图片的路径
     * @param   int         $thumb_width  缩略图宽度
     * @param   int         $thumb_height 缩略图高度
     * @param   strint      $path         指定生成图片的目录名
     * @return  mix         如果成功返回缩略图的路径，失败则返回false
     */
    function make_thumb($img, $thumb_width = 0, $thumb_height = 0, $path = '', $bgcolor='')
    {
         $gd = $this->gd_version(); //获取 GD 版本。0 表示没有 GD 库，1 表示 GD 1.x，2 表示 GD 2.x
         if ($gd == 0)
         {
             $this->error_msg = $GLOBALS['_LANG']['missing_gd'];
             return false;
         }

        /* 检查缩略图宽度和高度是否合法 */
        if ($thumb_width == 0 && $thumb_height == 0)
        {
            return str_replace(ROOT_PATH, '', str_replace('\\', '/', realpath($img)));
        }

        /* 检查原始文件是否存在及获得原始文件的信息 */
        $org_info = @getimagesize($img);
        if (!$org_info)
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['missing_orgin_image'], $img);
            $this->error_no  = ERR_IMAGE_NOT_EXISTS;

            return false;
        }

        if (!$this->check_img_function($org_info[2]))
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['nonsupport_type'], $this->type_maping[$org_info[2]]);
            $this->error_no  =  ERR_NO_GD;

            return false;
        }

        $img_org = $this->img_resource($img, $org_info[2]);

        /* 原始图片以及缩略图的尺寸比例 */
        $scale_org      = $org_info[0] / $org_info[1];
        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
        if ($thumb_width == 0)
        {
            $thumb_width = $thumb_height * $scale_org;
        }
        if ($thumb_height == 0)
        {
            $thumb_height = $thumb_width / $scale_org;
        }

        /* 创建缩略图的标志符 */
        if ($gd == 2)
        {
            $img_thumb  = imagecreatetruecolor($thumb_width, $thumb_height);
        }
        else
        {
            $img_thumb  = imagecreate($thumb_width, $thumb_height);
        }

        /* 背景颜色 */
        if (empty($bgcolor))
        {
            $bgcolor = $this->bgcolor;
        }
        $bgcolor = trim($bgcolor,"#");
        sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);

        if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height)
        {
            $lessen_width  = $thumb_width;
            $lessen_height  = $thumb_width / $scale_org;
        }
        else
        {
            /* 原始图片比较高，则以高度为准 */
            $lessen_width  = $thumb_height * $scale_org;
            $lessen_height = $thumb_height;
        }

        $dst_x = ($thumb_width  - $lessen_width)  / 2;
        $dst_y = ($thumb_height - $lessen_height) / 2;

        /* 将原始图片进行缩放处理 */
        if ($gd == 2)
        {
            imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
        }
        else
        {
            imagecopyresized($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
        }

        /* 创建当月目录 */
        if (empty($path))
        {
            $dir = ROOT_PATH . $this->images_dir . '/' . date('Ym').'/';
        }
        else
        {
            $dir = $path;
        }


        /* 如果目标目录不存在，则创建它 */
        if (!file_exists($dir))
        {
            if (!make_dir($dir))
            {
                /* 创建目录失败 */
                $this->error_msg  = sprintf($GLOBALS['_LANG']['directory_readonly'], $dir);
                $this->error_no   = ERR_DIRECTORY_READONLY;
                return false;
            }
        }

        /* 如果文件名为空，生成不重名随机文件名 */
        $filename = $this->unique_name($dir);

        /* 生成文件 */
        if (function_exists('imagejpeg'))
        {
            $filename .= '.jpg';
            imagejpeg($img_thumb, $dir . $filename);
        }
        elseif (function_exists('imagegif'))
        {
            $filename .= '.gif';
            imagegif($img_thumb, $dir . $filename);
        }
        elseif (function_exists('imagepng'))
        {
            $filename .= '.png';
            imagepng($img_thumb, $dir . $filename);
        }
        else
        {
            $this->error_msg = $GLOBALS['_LANG']['creating_failure'];
            $this->error_no  =  ERR_NO_GD;

            return false;
        }

        imagedestroy($img_thumb);
        imagedestroy($img_org);

        //确认文件是否生成
        if (file_exists($dir . $filename))
        {
            return str_replace(ROOT_PATH, '', $dir) . $filename;
        }
        else
        {
            $this->error_msg = $GLOBALS['_LANG']['writting_failure'];
            $this->error_no   = ERR_DIRECTORY_READONLY;

            return false;
        }
    }

    /**
     * 为图片增加水印
     *
     * @access      public
     * @param       string      filename            原始图片文件名，包含完整路径
     * @param       string      target_file         需要加水印的图片文件名，包含完整路径。如果为空则覆盖源文件
     * @param       string      $watermark          水印完整路径
     * @param       int         $watermark_place    水印位置代码
     * @return      mix         如果成功则返回文件路径，否则返回false
     */
    function add_watermark($filename, $target_file='', $watermark='', $watermark_place='', $watermark_alpha = 0.65)
    {
        // 是否安装了GD
        $gd = $this->gd_version();
        if ($gd == 0)
        {
            $this->error_msg = $GLOBALS['_LANG']['missing_gd'];
            $this->error_no  = ERR_NO_GD;

            return false;
        }

        // 文件是否存在
        if ((!file_exists($filename)) || (!is_file($filename)))
        {
            $this->error_msg  = sprintf($GLOBALS['_LANG']['missing_orgin_image'], $filename);
            $this->error_no   = ERR_IMAGE_NOT_EXISTS;

            return false;
        }

        /* 如果水印的位置为0，则返回原图 */
        if ($watermark_place == 0 || empty($watermark))
        {
            return str_replace(ROOT_PATH, '', str_replace('\\', '/', realpath($filename)));
        }

        if (!$this->validate_image($watermark))
        {
            /* 已经记录了错误信息 */
            return false;
        }

        // 获得水印文件以及源文件的信息
        $watermark_info     = @getimagesize($watermark);
        $watermark_handle   = $this->img_resource($watermark, $watermark_info[2]);

        if (!$watermark_handle)
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['create_watermark_res'], $this->type_maping[$watermark_info[2]]);
            $this->error_no  = ERR_INVALID_IMAGE;

            return false;
        }

        // 根据文件类型获得原始图片的操作句柄
        $source_info    = @getimagesize($filename);
        $source_handle  = $this->img_resource($filename, $source_info[2]);
        if (!$source_handle)
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['create_origin_image_res'], $this->type_maping[$source_info[2]]);
            $this->error_no = ERR_INVALID_IMAGE;

            return false;
        }

        // 根据系统设置获得水印的位置
        switch ($watermark_place)
        {
            case '1':
                $x = 0;
                $y = 0;
                break;
            case '2':
                $x = $source_info[0] - $watermark_info[0];
                $y = 0;
                break;
            case '4':
                $x = 0;
                $y = $source_info[1] - $watermark_info[1];
                break;
            case '5':
                $x = $source_info[0] - $watermark_info[0];
                $y = $source_info[1] - $watermark_info[1];
                break;
            default:
                $x = $source_info[0]/2 - $watermark_info[0]/2;
                $y = $source_info[1]/2 - $watermark_info[1]/2;
        }

        if (strpos(strtolower($watermark_info['mime']), 'png') !== false)
        {
            imageAlphaBlending($watermark_handle, true);
            imagecopy($source_handle, $watermark_handle, $x, $y, 0, 0,$watermark_info[0], $watermark_info[1]);
        }
        else
        {
            imagecopymerge($source_handle, $watermark_handle, $x, $y, 0, 0,$watermark_info[0], $watermark_info[1], $watermark_alpha);
        }
        $target = empty($target_file) ? $filename : $target_file;

        switch ($source_info[2] )
        {
            case 'image/gif':
            case 1:
                imagegif($source_handle,  $target);
                break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                imagejpeg($source_handle, $target);
                break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                imagepng($source_handle,  $target);
                break;

            default:
                $this->error_msg = $GLOBALS['_LANG']['creating_failure'];
                $this->error_no = ERR_NO_GD;

                return false;
        }

        imagedestroy($source_handle);

        $path = realpath($target);
        if ($path)
        {
            return str_replace(ROOT_PATH, '', str_replace('\\', '/', $path));
        }
        else
        {
            $this->error_msg = $GLOBALS['_LANG']['writting_failure'];
            $this->error_no  = ERR_DIRECTORY_READONLY;

            return false;
        }
    }

    /**
     *  检查水印图片是否合法
     *
     * @access  public
     * @param   string      $path       图片路径
     *
     * @return boolen
     */
    function validate_image($path)
    {
        if (empty($path))
        {
            $this->error_msg = $GLOBALS['_LANG']['empty_watermark'];
            $this->error_no  = ERR_INVALID_PARAM;

            return false;
        }

        /* 文件是否存在 */
        if (!file_exists($path))
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['missing_watermark'], $path);
            $this->error_no = ERR_IMAGE_NOT_EXISTS;
            return false;
        }

        // 获得文件以及源文件的信息
        $image_info     = @getimagesize($path);

        if (!$image_info)
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['invalid_image_type'], $path);
            $this->error_no = ERR_INVALID_IMAGE;
            return false;
        }

        /* 检查处理函数是否存在 */
        if (!$this->check_img_function($image_info[2]))
        {
            $this->error_msg = sprintf($GLOBALS['_LANG']['nonsupport_type'], $this->type_maping[$image_info[2]]);
            $this->error_no  =  ERR_NO_GD;
            return false;
        }

        return true;
    }

    /**
     * 返回错误信息
     *
     * @return  string   错误信息
     */
    function error_msg()
    {
        return $this->error_msg;
    }

    /*------------------------------------------------------ */
    //-- 工具函数
    /*------------------------------------------------------ */

    /**
     * 检查图片类型
     * @param   string  $img_type   图片类型
     * @return  bool
     */
    function check_img_type($img_type)
    {
        return $img_type == 'image/pjpeg' ||
               $img_type == 'image/x-png' ||
               $img_type == 'image/png'   ||
               $img_type == 'image/gif'   ||
               $img_type == 'image/jpeg';
    }

    /**
     * 检查图片处理能力
     *
     * @access  public
     * @param   string  $img_type   图片类型
     * @return  void
     */
    function check_img_function($img_type)
    {
        switch ($img_type)
        {
            case 'image/gif':
            case 1:

                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromgif');
                }
                else
                {
                    return (imagetypes() & IMG_GIF) > 0;
                }
            break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromjpeg');
                }
                else
                {
                    return (imagetypes() & IMG_JPG) > 0;
                }
            break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                if (PHP_VERSION >= '4.3')
                {
                     return function_exists('imagecreatefrompng');
                }
                else
                {
                    return (imagetypes() & IMG_PNG) > 0;
                }
            break;

            default:
                return false;
        }
    }

    /**
     * 生成随机的数字串
     *
     * @author: weber liu
     * @return string
     */
    function random_filename()
    {
        $str = '';
        for($i = 0; $i < 9; $i++)
        {
            $str .= mt_rand(0, 9);
        }

        return gmtime() . $str;
    }

    /**
     *  生成指定目录不重名的文件名
     *
     * @access  public
     * @param   string      $dir        要检查是否有同名文件的目录
     *
     * @return  string      文件名
     */
    function unique_name($dir)
    {
        $filename = '';
        while (empty($filename))
        {
            $filename = cls_image::random_filename();
            if (file_exists($dir . $filename . '.jpg') || file_exists($dir . $filename . '.gif') || file_exists($dir . $filename . '.png'))
            {
                $filename = '';
            }
        }

        return $filename;
    }

    /**
     *  返回文件后缀名，如‘.php’
     *
     * @access  public
     * @param
     *
     * @return  string      文件后缀名
     */
    function get_filetype($path)
    {
        $pos = strrpos($path, '.');
        if ($pos !== false)
        {
            return substr($path, $pos);
        }
        else
        {
            return '';
        }
    }

     /**
     * 根据来源文件的文件类型创建一个图像操作的标识符
     *
     * @access  public
     * @param   string      $img_file   图片文件的路径
     * @param   string      $mime_type  图片文件的文件类型
     * @return  resource    如果成功则返回图像操作标志符，反之则返回错误代码
     */
    function img_resource($img_file, $mime_type)
    {
        switch ($mime_type)
        {
            case 1:
            case 'image/gif':
                $res = imagecreatefromgif($img_file);
                break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
                $res = imagecreatefromjpeg($img_file);
                break;

            case 3:
            case 'image/x-png':
            case 'image/png':
                $res = imagecreatefrompng($img_file);
                break;

            default:
                return false;
        }

        return $res;
    }

    /**
     * 获得服务器上的 GD 版本
     *
     * @access      public
     * @return      int         可能的值为0，1，2
     */
    function gd_version()
    {
        static $version = -1;

        if ($version >= 0)
        {
            return $version;
        }

        if (!extension_loaded('gd'))
        {
            $version = 0;
        }
        else
        {
            // 尝试使用gd_info函数
            if (PHP_VERSION >= '4.3')
            {
                if (function_exists('gd_info'))
                {
                    $ver_info = gd_info();
                    preg_match('/\d/', $ver_info['GD Version'], $match);
                    $version = $match[0];
                }
                else
                {
                    if (function_exists('imagecreatetruecolor'))
                    {
                        $version = 2;
                    }
                    elseif (function_exists('imagecreate'))
                    {
                        $version = 1;
                    }
                }
            }
            else
            {
                if (preg_match('/phpinfo/', ini_get('disable_functions')))
                {
                    /* 如果phpinfo被禁用，无法确定gd版本 */
                    $version = 1;
                }
                else
                {
                  // 使用phpinfo函数
                   ob_start();
                   phpinfo(8);
                   $info = ob_get_contents();
                   ob_end_clean();
                   $info = stristr($info, 'gd version');
                   preg_match('/\d/', $info, $match);
                   $version = $match[0];
                }
             }
        }

        return $version;
     }

    /**
     *
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function move_file($upload, $target)
    {
        if (isset($upload['error']) && $upload['error'] > 0)
        {
            return false;
        }

        if (!move_upload_file($upload['tmp_name'], $target))
        {
            return false;
        }

        return true;
    }
}

?>