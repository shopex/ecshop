<?php

/**
 * ECSHOP 管理中心會員數據整合插件管理程序語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: integrate.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['integrate_name'] = '名稱';
$_LANG['integrate_version'] = '版本';
$_LANG['integrate_author'] = '作者';

/* 插件列表 */
$_LANG['update_success'] = '設置會員數據整合插件已經成功。';
$_LANG['install_confirm'] = '您確定要安裝該會員數據整合插件嗎？';
$_LANG['need_not_setup'] = '當您採用ECSHOP會員系統時，無須進行設置。';
$_LANG['different_domain'] = '您設置的整合對像和 ECSHOP 不在同一域下。<br />您將只能共享該系統的會員數據，但無法實現同時登錄。';
$_LANG['points_set'] = '積分兌換設置';
$_LANG['view_user_list'] = '查看論壇用戶';
$_LANG['view_install_log'] = '查看安裝日誌';

$_LANG['integrate_setup'] = '設置會員數據整合插件';
$_LANG['continue_sync'] = '繼續同步會員數據';
$_LANG['go_userslist'] = '返回會員帳號列表';
$_LANG['user_help'] = '<pre>
使用方法：
         1:如果需要整合其他的用戶系統，可以安裝適當的版本號插件進行整合。
         2:如果需要更換整合的用戶系統，直接安裝目標插件即可完成整合，同時自動卸載上一次整合插件。
         3:如果不需要整合任何用戶系統，請選擇安裝ecshop插件，即可卸載所有的整合插件。
                           </pre>';
/* 查看安裝日誌 */
$_LANG['lost_install_log'] = '未找到安裝日誌';
$_LANG['empty_install_log'] = '安裝日誌為空';

/* 表單相關語言項 */
$_LANG['db_notice'] = '點擊「<font color="#000000">下一步</font>」將引導你到將商城用戶數據同步到整合論壇。如果不需同步數據請點擊「<font color="#000000">直接保存配置信息</font>」';

$_LANG['lable_db_host'] = '數據庫服務器主機名：';
$_LANG['lable_db_name'] = '數據庫名：';
$_LANG['lable_db_chartset'] = '數據庫字符集：';
$_LANG['lable_is_latin1'] = '是否為latin1編碼';
$_LANG['lable_db_user'] = '數據庫帳號：';
$_LANG['lable_db_pass'] = '數據庫密碼：';
$_LANG['lable_prefix'] = '數據表前綴：';
$_LANG['lable_url'] = '被整合系統的完整 URL：';
/* 表單相關語言項(discus5x) */
$_LANG['cookie_prefix']          = 'COOKIE前綴：';
$_LANG['cookie_salt']          = 'COOKIE加密串：';
$_LANG['button_next'] = '下一步';
$_LANG['button_force_save_config'] = '直接保存配置信息';
$_LANG['save_confirm'] = '您確定要直接保存配置信息嗎？';
$_LANG['button_save_config'] = '保存配置信息';

$_LANG['error_db_msg'] = '數據庫地址、用戶或密碼不正確';
$_LANG['error_db_exist'] = '數據庫不存在';
$_LANG['error_table_exist'] = '整合論壇關鍵數據表不存在，你填寫的信息有誤';

$_LANG['notice_latin1'] = '該選項填寫錯誤時將可能到導致中文用戶名無法使用';
$_LANG['error_not_latin1'] = '整合數據庫檢測到不是latin1編碼！請重新選擇';
$_LANG['error_is_latin1'] = '整合數據庫檢測到是lantin1編碼！請重新選擇';
$_LANG['invalid_db_charset'] = '整合數據庫檢測到是%s 字符集，而非%s 字符集';
$_LANG['error_latin1'] = '你填寫的整合信息會導致嚴重錯誤，無法完成整合';

/* 檢查同名用戶 */
$_LANG['conflict_username_check'] = '檢查商城用戶是否和整合論壇用戶有重名';
$_LANG['check_notice'] = '本頁將檢測商城已有用戶和論壇用戶是否有重名，點擊「開始檢查前」，請為商城重名用戶選擇一個默認處理方法';
$_LANG['default_method'] = '如果檢測出商城有重名用戶，請為這些用戶選擇一個默認處理方法';
$_LANG['shop_user_total'] = '商城共有 %s 個用戶待檢查';
$_LANG['lable_size'] = '每次檢查用戶個數';
$_LANG['start_check'] = '開始檢查';
$_LANG['next'] = '下一步';
$_LANG['checking'] = '正在檢查...(請不要關閉瀏覽器)';
$_LANG['notice'] = '已經檢查 %s / %s ';
$_LANG['check_complete'] = '檢查完成';

/* 同名用戶處理 */
$_LANG['conflict_username_modify'] = '商城重名用戶列表';
$_LANG['modify_notice'] = '以下列出了所有商城與論壇的重名用戶及處理方法。如果您已確認所有操作，請點擊「開始整合」；您對重名用戶的操作的更改需要點擊按鈕「保存本頁更改」才能生效。';
$_LANG['page_default_method'] = '本頁面中重名用戶默認處理方法';
$_LANG['lable_rename'] = '商城重名用戶加後綴';
$_LANG['lable_delete'] = '刪除商城的重名用戶及相關數據';
$_LANG['lable_ignore'] = '保留商城重名用戶，論壇同名用戶視為同一用戶';
$_LANG['short_rename'] = '商城用戶改名為';
$_LANG['short_delete'] = '刪除商城用戶';
$_LANG['short_ignore'] = '保留商城用戶';
$_LANG['user_name'] = '商城用戶名';
$_LANG['email'] = 'email';
$_LANG['reg_date'] = '註冊日期';
$_LANG['all_user'] = '所有商城重名用戶';
$_LANG['error_user'] = '需要重新選擇操作的商城用戶';
$_LANG['rename_user'] = '需要改名的商城用戶';
$_LANG['delete_user'] = '需要刪除的商城用戶';
$_LANG['ignore_user'] = '需要保留的商城用戶';

$_LANG['submit_modify'] = '保存本頁變更';
$_LANG['button_confirm_next'] = '開始整合';


/* 用戶同步 */
$_LANG['user_sync'] = '同步商城數據到論壇，並完成整合';
$_LANG['button_pre'] = '上一步';
$_LANG['task_name'] = '任務名';
$_LANG['task_status'] = '任務狀態';
$_LANG['task_del'] = '%s 個商城用戶數待刪除';
$_LANG['task_rename'] = '%s 個商城用戶需要改名';
$_LANG['task_sync'] = '%s 個商城用戶需要同步到論壇';
$_LANG['task_save'] = '保存配置信息，並完成整合';
$_LANG['task_uncomplete'] = '未完成';
$_LANG['task_run'] = '執行中 (%s / %s)';
$_LANG['task_complete'] = '已完成';
$_LANG['start_task'] = '開始任務';
$_LANG['sync_status'] = '已經同步 %s / %s';
$_LANG['sync_size'] = '每次處理用戶數量';
$_LANG['sync_ok'] = '恭喜您。整合成功';


$_LANG['save_ok'] = '保存成功';

/* 積分設置 */
$_LANG['no_points'] = '沒有檢測到論壇有可以兌換的積分';
$_LANG['bbs'] = '論壇';
$_LANG['shop_pay_points'] = '商城消費積分';
$_LANG['shop_rank_points'] = '商城等級積分';
$_LANG['add_rule'] = '新增規則';
$_LANG['modify'] = '修改';
$_LANG['rule_name'] = '兌換規則';
$_LANG['rule_rate'] = '兌換比例';

/* JS語言項 */
$_LANG['js_languages']['no_host'] = '數據庫服務器主機名不能為空。';
$_LANG['js_languages']['no_user'] = '數據庫帳號不能為空。';
$_LANG['js_languages']['no_name'] = '數據庫名不能為空。';
$_LANG['js_languages']['no_integrate_url'] = '請輸入整合對象的完整 URL';
$_LANG['js_languages']['install_confirm'] = '請不要在系統運行中隨意的更換整合對象。\r\n您確定要安裝該會員數據整合插件嗎？';
$_LANG['js_languages']['num_invalid'] = '同步數據的記錄數不是一個整數';
$_LANG['js_languages']['start_invalid'] = '同步數據的起始位置不是一個整數';
$_LANG['js_languages']['sync_confirm'] = '同步會員數據會將目標數據表重建。請在執行同步之前備份好您的數據。\r\n您確定要開始同步會員數據嗎？';

$_LANG['cookie_prefix_notice'] = 'UTF8版本的cookie前綴默認為xnW_，GB2312/GBK版本的cookie前綴默認為KD9_。';

$_LANG['js_languages']['no_method'] = '請選擇一種默認處理方法';

$_LANG['js_languages']['rate_not_null'] = '比例不能為空';
$_LANG['js_languages']['rate_not_int'] = '比例只能填整數';
$_LANG['js_languages']['rate_invailed'] = '你填寫了一個無效的比例';
$_LANG['js_languages']['user_importing'] = '正在導入用戶到UCenter中...';

/* UCenter設置語言項 */
$_LANG['ucenter_tab_base'] = '基本設置';
$_LANG['ucenter_tab_show'] = '顯示設置';
$_LANG['ucenter_lab_id'] = 'UCenter 應用 ID:';
$_LANG['ucenter_lab_key'] = 'UCenter 通信密鑰:';
$_LANG['ucenter_lab_url'] = 'UCenter 訪問地址:';
$_LANG['ucenter_lab_ip'] = 'UCenter IP 地址:';
$_LANG['ucenter_lab_connect'] = 'UCenter 連接方式:';
$_LANG['ucenter_lab_db_host'] = 'UCenter 數據庫服務器:';
$_LANG['ucenter_lab_db_user'] = 'UCenter 數據庫用戶名:';
$_LANG['ucenter_lab_db_pass'] = 'UCenter 數據庫密碼:';
$_LANG['ucenter_lab_db_name'] = 'UCenter 數據庫名:';
$_LANG['ucenter_lab_db_pre'] = 'UCenter 表前綴:';
$_LANG['ucenter_lab_tag_number'] = 'TAG 標籤顯示數量:';
$_LANG['ucenter_lab_credit_0'] = '等級積分名稱:';
$_LANG['ucenter_lab_credit_1'] = '消費積分名稱:';
$_LANG['ucenter_opt_database'] = '數據庫方式';
$_LANG['ucenter_opt_interface'] = '接口方式';

$_LANG['ucenter_notice_id'] = '該值為當前商店在 UCenter 的應用 ID，一般情況請不要改動';
$_LANG['ucenter_notice_key'] = '通信密鑰用於在 UCenter 和 ECShop 之間傳輸信息的加密，可包含任何字母及數字，請在 UCenter 與 ECShop 設置完全相同的通訊密鑰，以確保兩套系統能夠正常通信';
$_LANG['ucenter_notice_url'] = '該值在您安裝完 UCenter 後會被初始化，在您 UCenter 地址或者目錄改變的情況下，修改此項，一般情況請不要改動 例如: http://www.sitename.com/uc_server (最後不要加"/")';
$_LANG['ucenter_notice_ip'] = '如果您的服務器無法通過域名訪問 UCenter，可以輸入 UCenter 服務器的 IP 地址';
$_LANG['ucenter_notice_connect'] = '請根據您的服務器網絡環境選擇適當的連接方式';
$_LANG['ucenter_notice_db_host'] = '可以是本地也可以是遠程數據庫服務器，如果 MySQL 端口不是默認的 3306，請填寫如下形式：127.0.0.1:6033';
$_LANG['uc_notice_ip'] = '連接的過程中出了點問題，請您填寫服務器 IP 地址，如果您的 UC 與 ECShop 裝在同一服務器上，我們建議您嘗試填寫 127.0.0.1';

$_LANG['uc_lab_url'] = 'UCenter 的 URL:';
$_LANG['uc_lab_pass'] = 'UCenter 創始人密碼:';
$_LANG['uc_lab_ip'] = 'UCenter 的 IP:';

$_LANG['uc_msg_verify_failur'] = '驗證失敗';
$_LANG['uc_msg_password_wrong'] = '創始人密碼錯誤';
$_LANG['uc_msg_data_error'] = '安裝數據錯誤';

$_LANG['ucenter_import_username'] = '會員數據導入到 UCenter';
$_LANG['uc_import_notice'] = '提醒：導入會員數據前請暫停各個應用(如Discuz!, SupeSite等)';
$_LANG['uc_members_merge'] = '會員合併方式';
$_LANG['user_startid_intro'] = '<p>此起始會員ID為%s。如原 ID 為 888 的會員將變為 %s+888 的值。</p>';
$_LANG['uc_members_merge_way1'] = '將與UC用戶名和密碼相同的用戶強制為同一用戶';
$_LANG['uc_members_merge_way2'] = '將與UC用戶名和密碼相同的用戶不導入UC用戶';
$_LANG['start_import'] = '開始導入';
$_LANG['import_user_success'] = '成功將會員數據導入到 UCenter';
$_LANG['uc_points'] = 'UCenter的積分兌換設置需要在UCenter管理後台進行';
$_LANG['uc_set_credits'] = '設置積分兌換方案';
$_LANG['uc_client_not_exists'] = 'uc_client目錄不存在，請先把uc_client目錄上傳到商城根目錄下再進行整合';
$_LANG['uc_client_not_write'] = 'uc_client/data目錄不可寫，請先把uc_client/data目錄權限設置為777';
$_LANG['uc_lang']['credits'][0][0] = '等級積分';
$_LANG['uc_lang']['credits'][0][1] = '';
$_LANG['uc_lang']['credits'][1][0] = '消費積分';
$_LANG['uc_lang']['credits'][1][1] = '';
$_LANG['uc_lang']['exchange'] = 'UCenter積分兌換';

?>