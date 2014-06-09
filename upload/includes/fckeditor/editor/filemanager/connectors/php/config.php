<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Configuration file for the File Manager Connector for PHP.
 */

global $Config ;

// SECURITY: You must explicitelly enable this "uploader".
// $Config['Enabled'] = false ;

// Set if the file type must be considere in the target path.
// Ex: /userfiles/image/ or /userfiles/file/
// $Config['UseFileType'] = false ;

// Path to uploaded files relative to the document root.
// $Config['UserFilesPath'] = '/userfiles/' ;

// by weberliu @ 2007-2-6

define('IN_ECS', true);
define('ROOT_PATH', preg_replace('/includes(.*)/i', '', str_replace('\\', '/', __FILE__)));

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

$root_path = preg_replace('/includes(.*)/i', '', PHP_SELF);

require(ROOT_PATH . 'data/config.php');
require(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'includes/cls_mysql.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_session.php');
require(ROOT_PATH . 'includes/lib_common.php');

/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', $ecs->data_dir());
define('IMAGE_DIR', $ecs->image_dir());

$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);

/* init session */
$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'ECSCP_ID');

if (!empty($_SESSION['admin_id']))
{
    if ($_SESSION['action_list'] == 'all')
    {
        $enable = true;
    }
    else
    {
        if (strpos(',' . $_SESSION['action_list'] . ',', ',goods_manage,') === false && strpos(',' . $_SESSION['action_list'] . ',', ',virualcard,') === false && strpos(',' .
        $_SESSION['action_list'] . ',', ',article_manage,') === false)
        {
            $enable = false;
        }
        else
        {
            $enable = true;
        }
    }
}
else
{
    $enable = false;
}

/* 载入系统参数 */
$_CFG = load_config();

$Config['Enabled'] = $enable;

// SECURITY: You must explicitly enable this "connector". (Set it to "true").
// WARNING: don't just set "$Config['Enabled'] = true ;", you must be sure that only
//      authenticated users can access this file or use some kind of session checking.
//$Config['Enabled'] = false ;


// Path to user files relative to the document root.
$Config['UserFilesPath'] = $root_path . IMAGE_DIR . '/upload/';

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Useful if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = ROOT_PATH . IMAGE_DIR . '/upload/' ;

// Due to security issues with Apache modules, it is recommended to leave the
// following setting enabled.
$Config['ForceSingleExtension'] = true ;

// Perform additional checks for image files.
// If set to true, validate image size (using getimagesize).
$Config['SecureImageUploads'] = true;

// What the user can do with this connector.
$Config['ConfigAllowedCommands'] = array('QuickUpload', 'FileUpload', 'GetFolders', 'GetFoldersAndFiles', 'CreateFolder') ;

// Allowed Resource Types.
$Config['ConfigAllowedTypes'] = array('File', 'Image', 'Flash', 'Media') ;

// For security, HTML is allowed in the first Kb of data for files having the
// following extensions only.
$Config['HtmlExtensions'] = array("html", "htm", "xml", "xsd", "txt", "js") ;

// After file is uploaded, sometimes it is required to change its permissions
// so that it was possible to access it at the later time.
// If possible, it is recommended to set more restrictive permissions, like 0755.
// Set to 0 to disable this feature.
// Note: not needed on Windows-based servers.
$Config['ChmodOnUpload'] = 0777 ;

// See comments above.
// Used when creating folders that does not exist.
$Config['ChmodOnFolderCreate'] = 0777 ;

/*
    Configuration settings for each Resource Type

    - AllowedExtensions: the possible extensions that can be allowed.
        If it is empty then any file type can be uploaded.
    - DeniedExtensions: The extensions that won't be allowed.
        If it is empty then no restrictions are done here.

    For a file to be uploaded it has to fulfill both the AllowedExtensions
    and DeniedExtensions (that's it: not being denied) conditions.

    - FileTypesPath: the virtual folder relative to the document root where
        these resources will be located.
        Attention: It must start and end with a slash: '/'

    - FileTypesAbsolutePath: the physical path to the above folder. It must be
        an absolute path.
        If it's an empty string then it will be autocalculated.
        Useful if you are using a virtual directory, symbolic link or alias.
        Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
        Attention: The above 'FileTypesPath' must point to the same directory.
        Attention: It must end with a slash: '/'

     - QuickUploadPath: the virtual folder relative to the document root where
        these resources will be uploaded using the Upload tab in the resources
        dialogs.
        Attention: It must start and end with a slash: '/'

     - QuickUploadAbsolutePath: the physical path to the above folder. It must be
        an absolute path.
        If it's an empty string then it will be autocalculated.
        Useful if you are using a virtual directory, symbolic link or alias.
        Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
        Attention: The above 'QuickUploadPath' must point to the same directory.
        Attention: It must end with a slash: '/'

        NOTE: by default, QuickUploadPath and QuickUploadAbsolutePath point to
        "userfiles" directory to maintain backwards compatibility with older versions of FCKeditor.
        This is fine, but you in some cases you will be not able to browse uploaded files using file browser.
        Example: if you click on "image button", select "Upload" tab and send image
        to the server, image will appear in FCKeditor correctly, but because it is placed
        directly in /userfiles/ directory, you'll be not able to see it in built-in file browser.
        The more expected behaviour would be to send images directly to "image" subfolder.
        To achieve that, simply change
            $Config['QuickUploadPath']['Image']         = $Config['UserFilesPath'] ;
            $Config['QuickUploadAbsolutePath']['Image'] = $Config['UserFilesAbsolutePath'] ;
        into:
            $Config['QuickUploadPath']['Image']         = $Config['FileTypesPath']['Image'] ;
            $Config['QuickUploadAbsolutePath']['Image']     = $Config['FileTypesAbsolutePath']['Image'] ;

*/

$Config['AllowedExtensions']['File']    = array('7z', 'aiff', 'asf', 'avi', 'bmp', 'csv', 'doc', 'fla', 'flv', 'gif', 'gz', 'gzip', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pxd', 'qt', 'ram', 'rar', 'rm', 'rmi', 'rmvb', 'rtf', 'sdc', 'sitd', 'swf', 'sxc', 'sxw', 'tar', 'tgz', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 'wmv', 'xls', 'xml', 'zip') ;
$Config['FileTypesPath']['File']        = $Config['UserFilesPath'] . 'File/' ;
$Config['FileTypesAbsolutePath']['File']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'File/' ;
$Config['QuickUploadPath']['File']      = $Config['UserFilesPath'] . 'File/' ;
$Config['QuickUploadAbsolutePath']['File']= $Config['UserFilesAbsolutePath'] . 'File/' ;

//$Config['AllowedExtensions']['Image']   = array('bmp','gif','jpeg','jpg','png') ;
$Config['AllowedExtensions']['Image']    = array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']    = array() ;
$Config['FileTypesPath']['Image']       = $Config['UserFilesPath'] . 'Image/' ;
$Config['FileTypesAbsolutePath']['Image']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'Image/' ;
$Config['QuickUploadPath']['Image']     = $Config['UserFilesPath'] . 'Image/' ;
$Config['QuickUploadAbsolutePath']['Image']= $Config['UserFilesAbsolutePath'] . 'Image/' ;

//$Config['AllowedExtensions']['Flash']   = array('swf','flv') ;
$Config['AllowedExtensions']['Flash']    = array('swf','fla') ;
$Config['DeniedExtensions']['Flash']    = array() ;
$Config['FileTypesPath']['Flash']       = $Config['UserFilesPath'] . 'Flash/' ;
$Config['FileTypesAbsolutePath']['Flash']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'Flash/' ;
$Config['QuickUploadPath']['Flash']     = $Config['UserFilesPath'] . 'Flash/' ;
$Config['QuickUploadAbsolutePath']['Flash']= $Config['UserFilesAbsolutePath'] . 'Flash/' ;

//$Config['AllowedExtensions']['Media']   = array('aiff', 'asf', 'avi', 'bmp', 'fla', 'flv', 'gif', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'png', 'qt', 'ram', 'rm', 'rmi', 'rmvb', 'swf', 'tif', 'tiff', 'wav', 'wma', 'wmv') ;
$Config['AllowedExtensions']['Media']   =array('7z', 'aiff', 'asf', 'avi', 'bmp', 'csv', 'doc', 'fla', 'flv', 'gif', 'gz', 'gzip', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pxd', 'qt', 'ram', 'rar', 'rm', 'rmi', 'rmvb', 'rtf', 'sdc', 'sitd', 'swf', 'sxc', 'sxw', 'tar', 'tgz', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 'wmv', 'xls', 'xml', 'zip') ;
$Config['DeniedExtensions']['Media']    = array() ;
$Config['FileTypesPath']['Media']       = $Config['UserFilesPath'] . 'Media/' ;
$Config['FileTypesAbsolutePath']['Media']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'Media/' ;
$Config['QuickUploadPath']['Media']     = $Config['UserFilesPath'] . 'Media/' ;
$Config['QuickUploadAbsolutePath']['Media']= $Config['UserFilesAbsolutePath'] . 'Media/' ;

?>
