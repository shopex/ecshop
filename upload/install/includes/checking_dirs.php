<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$checking_dirs = array(
                    /* 取消检测uc_client */
                    //'uc_client/data',
                    'cert',
                    'images',
                    'images/upload',
                    'images/upload/Image',
                    'images/upload/File',
                    'images/upload/Flash',
                    'images/upload/Media',
                    'data',
                    'data/afficheimg',
                    'data/brandlogo',
                    'data/cardimg',
                    'data/feedbackimg',
                    'data/packimg',
                    'data/sqldata',
                    'temp',
                    'temp/backup',
                    'temp/caches',
                    'temp/compiled',
                    'temp/query_caches',
                    'temp/static_caches'
                    );

?>