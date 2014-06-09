ECSHOP v2.0 以上版本 URL Rewrite 使用说明


# 一、Apache 服务器

1. 首先您需要找到 Apache 安装目录，之后找到 conf 目录下的 httpd.conf 文件。
1. 将下面的代码复制到 httpd.conf 文件中，注意将 /ecshop 替换为您的商店的实际
安装目录。
```
<Directory /ecshop>
  Options FollowSymLinks
  AllowOverride All
</Directory>
```
1. 在 httpd.conf 中搜索 LoadModule rewrite_module，将该行前面的 # 号删除。
  如果您的 Apache 是1.3.x版本还需要查找 AddModule mod_rewrite.c，
  请将前面的#删除。
1. 保存 httpd.conf。
1. 将 ecshop 目录下的 htaccess.txt 重命名为 .htaccess。
1. 重新启动 Apache。
1. 进入 ecshop 管理中心->商店设置，将 URL 重写设置为启用。

如果您想通过 httpd.conf 来设置重写规则，请按照下面的步骤操作：
1. 执行上面第1-3步操作。
1. 找到您的商店所在的虚拟主机段，如：
```
    <VirtualHost 127.0.0.1>
        DocumentRoot "/home/ecshop/"
        ServerName www.ecshop.com
    </VirtualHost>
```
   1. 将下面的内容加入在</VirtualHost>之前
```
    <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)/index.html$                $1/index.php
    RewriteRule ^(.*)/category$                  $1/index.php                       [L]
    RewriteRule ^(.*)/feed-c([0-9]+).xml$        $1/feed.php?cat=$2                 [L]
    RewriteRule ^(.*)/feed-b([0-9]+).xml$        $1/feed.php?brand=$2               [L]
    RewriteRule ^(.*)/feed-type([^-]+)\.xml$     $1/feed\.php\?type=$2              [L]
    RewriteRule ^(.*)/feed.xml$                  $1/feed.php                        [L]
    RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-min([0-9]+)-max([0-9]+)-attr([^-]*)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$  $1/category.php?id=$2&brand=$3&price_min=$4&price_max=$5&filter_attr=$6&page=$7&sort=$8&order=$9 [QSA,L]
    RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-min([0-9]+)-max([0-9]+)-attr([^-]*)(.*)\.html$                            $1/category.php?id=$2&brand=$3&price_min=$4&price_max=$5&filter_attr=$6 [QSA,L]
    RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$                              $1/category.php?id=$2&brand=$3&page=$4&sort=$5&order=$6 [QSA,L]
    RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-([0-9]+)(.*)\.html$                                       $1/category.php?id=$2&brand=$3&page=$4 [QSA,L]
    RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)(.*)\.html$                                            $1/category.php?id=$2&brand=$3 [QSA,L]
    RewriteRule ^(.*)/category-([0-9]+)(.*)\.html$                               $1/category.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/goods-([0-9]+)(.*)\.html$                                  $1/goods.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$  $1/article_cat.php?id=$2&page=$3&sort=$4&order=$5 [QSA,L]
    RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)-(.+)(.*)\.html$              $1/article_cat\.php\?id=$1&page=$2&keywords=$3 [QSA,L]
    RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)(.*)\.html$                   $1/article_cat.php?id=$2&page=$3 [QSA,L]
    RewriteRule ^(.*)/article_cat-([0-9]+)(.*)\.html$                            $1/article_cat.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/article-([0-9]+)(.*)\.html$                                $1/article.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)\.html   $1/brand.php?id=$2&cat=$3&page=$4&sort=$5&order=$6 [QSA,L]
    RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)-([0-9]+)(.*)\.html                $1/brand.php?id=$2&cat=$3&page=$4 [QSA,L]
    RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)(.*)\.html                         $1/brand.php?id=$2&cat=$3 [QSA,L]
    RewriteRule ^(.*)/brand-([0-9]+)(.*)\.html                                   $1/brand.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/tag-(.*)\.html                                             $1/search.php?keywords=$2 [QSA,L]
    RewriteRule ^(.*)/snatch-([0-9]+)\.html$                                     $1/snatch.php?id=$2 [QSA,L]
    RewriteRule ^(.*)/group_buy-([0-9]+)\.html$                                  $1/group_buy.php?act=view&id=$2 [QSA,L]
    RewriteRule ^(.*)/auction-([0-9]+)\.html$                                    $1/auction.php?act=view&id=$2 [QSA,L]
    RewriteRule ^(.*)/exchange-id([0-9]+)(.*)\.html$                             $1/exchange\.php\?id=$2&act=view [QSA,L]
    RewriteRule ^(.*)/exchange-([0-9]+)-min([0-9]+)-max([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$ $1/exchange\.php\?cat_id=$2&integral_min=$3&integral_max=$4&page=$5&sort=$6&order=$7 [QSA,L]
    RewriteRule ^(.*)/exchange-([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$                         $1/exchange\.php\?cat_id=$2&page=$3&sort=$4&order=$5 [QSA,L]
    RewriteRule ^(.*)/exchange-([0-9]+)-([0-9]+)(.*)\.html$                                          $1/exchange\.php\?cat_id=$2&page=$3  [QSA,L]
    RewriteRule ^(.*)/exchange-([0-9]+)(.*)\.html$                                                   $1/exchange\.php\?cat_id=$2  [QSA,L]
    </IfModule>
```

# 二、IIS 服务器

1. 首先请进入以下网址 http://www.helicontech.com/download/，下载免费版的ISAPI_Rewrite组件：ISAPI_Rewrite Lite ( freeware )。如果您仅仅是测试用途使用这个就足够了，如果您是商业应用建议您购买完整版的 ISAPI_Rewrite Full。
如果您无法访问以上网址，您也可以到我们的网站上下载：http://www.ecshop.com
1. 点击下载到本地的文件 isapi_rwl_x86_0064.msi （该文件名和您下载的版本有关）
进行安装，安装成功之后进入安装目录（默认在``C:/Program Files/Helicon/ISAPI_Rewrite``）找到httpd.ini文件，点击右键将文件只读属性去掉。然后进入： 开始菜单->程序->Helicon->ISAPI_Rewrite->httpd.ini，

点击打开 httpd.ini 文件。
1. 复制下面的内容到httpd.ini文件
1. 保存 httpd.ini，进入 ecshop 管理中心->商店设置，将 URL 重写设置为启用。

```
[ISAPI_Rewrite]

# 为了确保重写规则不影响服务器上的其他站点
# 请将下面的语句前的#号去掉，并将(?:www\.)?site1\.com改为商店所在域名
# RewriteCond %{HTTP:Host} (?:www\.)?site1\.com

RewriteRule ^(.*)/index.html$                $1/index\.php           [I]
RewriteRule ^(.*)/category$                  $1/index\.php           [I]
RewriteRule ^(.*)/feed-c([0-9]+).xml$        $1/feed\.php\?cat=$2    [I]
RewriteRule ^(.*)/feed-b([0-9]+).xml$        $1/feed\.php\?brand=$2  [I]
RewriteRule ^(.*)/feed-type([^-]+)\.xml$     $1/feed\.php\?type=$2   [I]
RewriteRule ^(.*)/feed.xml$                  $1/feed\.php            [I]
RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-min([0-9]+)-max([0-9]+)-attr([^-]*)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$  $1/category\.php\?id=$2&brand=$3&price_min=$4&price_max=$5&filter_attr=$6&page=$7&sort=$8&order=$9 [I]
RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-min([0-9]+)-max([0-9]+)-attr([^-]*)(.*)\.html$                            $1/category\.php\?id=$2&brand=$3&price_min=$4&price_max=$5&filter_attr=$6                          [I]
RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$                              $1/category\.php\?id=$2&brand=$3&page=$4&sort=$5&order=$6                                          [I]
RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)-([0-9]+)(.*)\.html$                                       $1/category\.php\?id=$2&brand=$3&page=$4                                                           [I]
RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)(.*)\.html$                                            $1/category\.php\?id=$2&brand=$3                                                                   [I]
RewriteRule ^(.*)/category-([0-9]+)(.*)\.html$                               $1/category\.php\?id=$2                              [I]

RewriteRule ^(.*)/category-([0-9]+)-b([0-9]+)\.html(.*)$                                            $1/category\.php\?$4&id=$2&brand=$3

RewriteRule ^(.*)/goods-([0-9]+)(.*)\.html$                                  $1/goods\.php\?id=$2                                 [I]
RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$  $1/article_cat\.php\?id=$2&page=$3&sort=$4&order=$5  [I]
RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)-(.+)(.*)\.html$              $1/article_cat\.php\?id=$1&page=$2&keywords=$3	  [I]
RewriteRule ^(.*)/article_cat-([0-9]+)-([0-9]+)(.*)\.html$                   $1/article_cat\.php\?id=$2&page=$3                   [I]
RewriteRule ^(.*)/article_cat-([0-9]+)(.*)\.html$                            $1/article_cat\.php\?id=$2                           [I]
RewriteRule ^(.*)/article-([0-9]+)(.*)\.html$                                $1/article\.php\?id=$2                               [I]
RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)\.html   $1/brand\.php\?id=$2&cat=$3&page=$4&sort=$5&order=$6 [I]
RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)-([0-9]+)(.*)\.html                $1/brand\.php\?id=$2&cat=$3&page=$4                  [I]

RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)\.html(.*)$                        $1/brand\.php\?$4&id=$2&cat=$3                  [I]

RewriteRule ^(.*)/brand-([0-9]+)-c([0-9]+)(.*)\.html                         $1/brand\.php\?id=$2&cat=$3                          [I]
RewriteRule ^(.*)/brand-([0-9]+)(.*)\.html                                   $1/brand\.php\?id=$2                                 [I]
RewriteRule ^(.*)/tag-(.*)\.html                                             $1/search\.php\?keywords=$2                          [I]
RewriteRule ^(.*)/snatch-([0-9]+)\.html$                                     $1/snatch\.php\?id=$2                                [I]
RewriteRule ^(.*)/group_buy-([0-9]+)\.html$                                  $1/group_buy\.php\?act=view&id=$2                    [I]
RewriteRule ^(.*)/auction-([0-9]+)\.html$                                    $1/auction\.php\?act=view&id=$2                      [I]
RewriteRule ^(.*)/exchange-id([0-9]+)(.*)\.html$                             $1/exchange\.php\?id=$2&act=view                     [I]
RewriteRule ^(.*)/exchange-([0-9]+)-min([0-9]+)-max([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$ $1/exchange\.php\?cat_id=$2&integral_min=$3&integral_max=$4&page=$5&sort=$6&order=$7 [I]
RewriteRule ^(.*)/exchange-([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)(.*)\.html$                         $1/exchange\.php\?cat_id=$2&page=$3&sort=$4&order=$5 [I]
RewriteRule ^(.*)/exchange-([0-9]+)-([0-9]+)(.*)\.html$                                          $1/exchange\.php\?cat_id=$2&page=$3  [I]
RewriteRule ^(.*)/exchange-([0-9]+)(.*)\.html$                                                   $1/exchange\.php\?cat_id=$2  [I]
```