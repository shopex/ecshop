<?php

/**
 * ECSHOP 常量
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: inc_constant.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/* 图片处理相关常数 */
define('ERR_INVALID_IMAGE',         1);
define('ERR_NO_GD',                 2);
define('ERR_IMAGE_NOT_EXISTS',      3);
define('ERR_DIRECTORY_READONLY',    4);
define('ERR_UPLOAD_FAILURE',        5);
define('ERR_INVALID_PARAM',         6);
define('ERR_INVALID_IMAGE_TYPE',    7);

/* 插件相关常数 */
define('ERR_COPYFILE_FAILED',       1);
define('ERR_CREATETABLE_FAILED',    2);
define('ERR_DELETEFILE_FAILED',     3);

/* 商品属性类型常数 */
define('ATTR_TEXT',                 0);
define('ATTR_OPTIONAL',             1);
define('ATTR_TEXTAREA',             2);
define('ATTR_URL',                  3);

/* 会员整合相关常数 */
define('ERR_USERNAME_EXISTS',       1); // 用户名已经存在
define('ERR_EMAIL_EXISTS',          2); // Email已经存在
define('ERR_INVALID_USERID',        3); // 无效的user_id
define('ERR_INVALID_USERNAME',      4); // 无效的用户名
define('ERR_INVALID_PASSWORD',      5); // 密码错误
define('ERR_INVALID_EMAIL',         6); // email错误
define('ERR_USERNAME_NOT_ALLOW',    7); // 用户名不允许注册
define('ERR_EMAIL_NOT_ALLOW',       8); // EMAIL不允许注册

/* 加入购物车失败的错误代码 */
define('ERR_NOT_EXISTS',            1); // 商品不存在
define('ERR_OUT_OF_STOCK',          2); // 商品缺货
define('ERR_NOT_ON_SALE',           3); // 商品已下架
define('ERR_CANNT_ALONE_SALE',      4); // 商品不能单独销售
define('ERR_NO_BASIC_GOODS',        5); // 没有基本件
define('ERR_NEED_SELECT_ATTR',      6); // 需要用户选择属性

/* 购物车商品类型 */
define('CART_GENERAL_GOODS',        0); // 普通商品
define('CART_GROUP_BUY_GOODS',      1); // 团购商品
define('CART_AUCTION_GOODS',        2); // 拍卖商品
define('CART_SNATCH_GOODS',         3); // 夺宝奇兵
define('CART_EXCHANGE_GOODS',       4); // 积分商城

/* 订单状态 */
define('OS_UNCONFIRMED',            0); // 未确认
define('OS_CONFIRMED',              1); // 已确认
define('OS_CANCELED',               2); // 已取消
define('OS_INVALID',                3); // 无效
define('OS_RETURNED',               4); // 退货
define('OS_SPLITED',                5); // 已分单
define('OS_SPLITING_PART',          6); // 部分分单

/* 支付类型 */
define('PAY_ORDER',                 0); // 订单支付
define('PAY_SURPLUS',               1); // 会员预付款

/* 配送状态 */
define('SS_UNSHIPPED',              0); // 未发货
define('SS_SHIPPED',                1); // 已发货
define('SS_RECEIVED',               2); // 已收货
define('SS_PREPARING',              3); // 备货中
define('SS_SHIPPED_PART',           4); // 已发货(部分商品)
define('SS_SHIPPED_ING',            5); // 发货中(处理分单)
define('OS_SHIPPED_PART',           6); // 已发货(部分商品)

/* 支付状态 */
define('PS_UNPAYED',                0); // 未付款
define('PS_PAYING',                 1); // 付款中
define('PS_PAYED',                  2); // 已付款

/* 综合状态 */
define('CS_AWAIT_PAY',              100); // 待付款：货到付款且已发货且未付款，非货到付款且未付款
define('CS_AWAIT_SHIP',             101); // 待发货：货到付款且未发货，非货到付款且已付款且未发货
define('CS_FINISHED',               102); // 已完成：已确认、已付款、已发货

/* 缺货处理 */
define('OOS_WAIT',                  0); // 等待货物备齐后再发
define('OOS_CANCEL',                1); // 取消订单
define('OOS_CONSULT',               2); // 与店主协商

/* 帐户明细类型 */
define('SURPLUS_SAVE',              0); // 为帐户冲值
define('SURPLUS_RETURN',            1); // 从帐户提款

/* 评论状态 */
define('COMMENT_UNCHECKED',         0); // 未审核
define('COMMENT_CHECKED',           1); // 已审核或已回复(允许显示)
define('COMMENT_REPLYED',           2); // 该评论的内容属于回复

/* 红包发放的方式 */
define('SEND_BY_USER',              0); // 按用户发放
define('SEND_BY_GOODS',             1); // 按商品发放
define('SEND_BY_ORDER',             2); // 按订单发放
define('SEND_BY_PRINT',             3); // 线下发放

/* 广告的类型 */
define('IMG_AD',                    0); // 图片广告
define('FALSH_AD',                  1); // flash广告
define('CODE_AD',                   2); // 代码广告
define('TEXT_AD',                   3); // 文字广告

/* 是否需要用户选择属性 */
define('ATTR_NOT_NEED_SELECT',      0); // 不需要选择
define('ATTR_NEED_SELECT',          1); // 需要选择

/* 用户中心留言类型 */
define('M_MESSAGE',                 0); // 留言
define('M_COMPLAINT',               1); // 投诉
define('M_ENQUIRY',                 2); // 询问
define('M_CUSTOME',                 3); // 售后
define('M_BUY',                     4); // 求购
define('M_BUSINESS',                5); // 商家
define('M_COMMENT',                 6); // 评论

/* 团购活动状态 */
define('GBS_PRE_START',             0); // 未开始
define('GBS_UNDER_WAY',             1); // 进行中
define('GBS_FINISHED',              2); // 已结束
define('GBS_SUCCEED',               3); // 团购成功（可以发货了）
define('GBS_FAIL',                  4); // 团购失败

/* 红包是否发送邮件 */
define('BONUS_NOT_MAIL',            0);
define('BONUS_MAIL_SUCCEED',        1);
define('BONUS_MAIL_FAIL',           2);

/* 商品活动类型 */
define('GAT_SNATCH',                0);
define('GAT_GROUP_BUY',             1);
define('GAT_AUCTION',               2);
define('GAT_POINT_BUY',             3);
define('GAT_PACKAGE',               4); // 超值礼包

/* 帐号变动类型 */
define('ACT_SAVING',                0);     // 帐户冲值
define('ACT_DRAWING',               1);     // 帐户提款
define('ACT_ADJUSTING',             2);     // 调节帐户
define('ACT_OTHER',                99);     // 其他类型

/* 密码加密方法 */
define('PWD_MD5',                   1);  //md5加密方式
define('PWD_PRE_SALT',              2);  //前置验证串的加密方式
define('PWD_SUF_SALT',              3);  //后置验证串的加密方式

/* 文章分类类型 */
define('COMMON_CAT',                1); //普通分类
define('SYSTEM_CAT',                2); //系统默认分类
define('INFO_CAT',                  3); //网店信息分类
define('UPHELP_CAT',                4); //网店帮助分类分类
define('HELP_CAT',                  5); //网店帮助分类

/* 活动状态 */
define('PRE_START',                 0); // 未开始
define('UNDER_WAY',                 1); // 进行中
define('FINISHED',                  2); // 已结束
define('SETTLED',                   3); // 已处理

/* 验证码 */
define('CAPTCHA_REGISTER',          1); //注册时使用验证码
define('CAPTCHA_LOGIN',             2); //登录时使用验证码
define('CAPTCHA_COMMENT',           4); //评论时使用验证码
define('CAPTCHA_ADMIN',             8); //后台登录时使用验证码
define('CAPTCHA_LOGIN_FAIL',       16); //登录失败后显示验证码
define('CAPTCHA_MESSAGE',          32); //留言时使用验证码

/* 优惠活动的优惠范围 */
define('FAR_ALL',                   0); // 全部商品
define('FAR_CATEGORY',              1); // 按分类选择
define('FAR_BRAND',                 2); // 按品牌选择
define('FAR_GOODS',                 3); // 按商品选择

/* 优惠活动的优惠方式 */
define('FAT_GOODS',                 0); // 送赠品或优惠购买
define('FAT_PRICE',                 1); // 现金减免
define('FAT_DISCOUNT',              2); // 价格打折优惠

/* 评论条件 */
define('COMMENT_LOGIN',             1); //只有登录用户可以评论
define('COMMENT_CUSTOM',            2); //只有有过一次以上购买行为的用户可以评论
define('COMMENT_BOUGHT',            3); //只有购买够该商品的人可以评论

/* 减库存时机 */
define('SDT_SHIP',                  0); // 发货时
define('SDT_PLACE',                 1); // 下订单时

/* 加密方式 */
define('ENCRYPT_ZC',                1); //zc加密方式
define('ENCRYPT_UC',                2); //uc加密方式

/* 商品类别 */
define('G_REAL',                    1); //实体商品
define('G_CARD',                    0); //虚拟卡

/* 积分兑换 */
define('TO_P',                      0); //兑换到商城消费积分
define('FROM_P',                    1); //用商城消费积分兑换
define('TO_R',                      2); //兑换到商城等级积分
define('FROM_R',                    3); //用商城等级积分兑换

/* 支付宝商家账户 */
define('ALIPAY_AUTH', 'gh0bis45h89m5mwcoe85us4qrwispes0');
define('ALIPAY_ID', '2088002052150939');

/* 添加feed事件到UC的TYPE*/
define('BUY_GOODS',                 1); //购买商品
define('COMMENT_GOODS',             2); //添加商品评论

/* 邮件发送用户 */
define('SEND_LIST', 0);
define('SEND_USER', 1);
define('SEND_RANK', 2);

/* license接口 */
define('LICENSE_VERSION', '1.0');

/* 配送方式 */
define('SHIP_LIST', 'cac|city_express|ems|flat|fpd|post_express|post_mail|presswork|sf_express|sto_express|yto|zto');

?>