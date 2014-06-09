<?php

/**
 * ECSHOP auction language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: auction.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* menu */
$_LANG['auction_list'] = 'Auction list';
$_LANG['add_auction'] = 'Add auction Events';
$_LANG['edit_auction'] = 'Edit Auction Events';
$_LANG['auction_log'] = 'Record auction bid';
$_LANG['continue_add_auction'] = 'Continue to add auctions';
$_LANG['back_auction_list'] = 'Return a list of auctions';
$_LANG['add_auction_ok'] = 'Add auction success';
$_LANG['edit_auction_ok'] = 'Editor auction success';
$_LANG['settle_deposit_ok'] = 'Successfully deal with the freezing of the margin';

/* list */
$_LANG['act_is_going'] = 'Show only the ongoing activities';
$_LANG['act_name'] = 'Activity name auction';
$_LANG['goods_name'] = 'Trade names';
$_LANG['start_time'] = 'Start Time';
$_LANG['end_time'] = 'The End Time';
$_LANG['deposit'] = 'Margin';
$_LANG['start_price'] = 'Starting price';
$_LANG['end_price'] = 'Buy It Now';
$_LANG['amplitude'] = 'The rate of increase';
$_LANG['auction_not_exist'] = 'You want to operate the auction does not exist';
$_LANG['auction_cannot_remove'] = 'The auction has already received a bid, should not delete';
$_LANG['js_languages']['batch_drop_confirm'] = 'Are you sure you want to delete the selected auction activities?';
$_LANG['batch_drop_ok'] = 'Operation is complete (it has been bid auction should not delete)';
$_LANG['no_record_selected'] = 'Record no choice';

/* info */
$_LANG['label_act_name'] = 'Auction Activity Name:';
$_LANG['notice_act_name'] = 'If blank, check the auction merchandise name (the name used only for the background, the future will not be displayed)';
$_LANG['label_act_desc'] = 'Events described in the auction:';
$_LANG['label_search_goods'] = 'According to the merchandise code, merchandise name, or Item Search';
$_LANG['label_goods_name'] = 'Auction merchandise Name:';
$_LANG['label_start_time'] = 'Auction Start Time:';
$_LANG['label_end_time'] = 'Auction Ending Time:';
$_LANG['label_status'] = 'Current status:';
$_LANG['label_start_price'] = 'Starting price:';
$_LANG['label_end_price'] = 'Buy It Now price:';
$_LANG['label_no_top'] = 'No cap';
$_LANG['label_amplitude'] = 'The rate of increase:';
$_LANG['label_deposit'] = 'Margin:';
$_LANG['bid_user_count'] = 'Buyers have been %s bid';
$_LANG['settle_frozen_money'] = 'How to deal with the freezing of funds buyers?';
$_LANG['unfreeze'] = 'Thaw margin';
$_LANG['deduct'] = 'After discounting the effect of margin';
$_LANG['invalid_status'] = 'Current status is incorrect';
$_LANG['no_deposit'] = 'No deposit required treatment';
$_LANG['unfreeze_auction_deposit'] = 'Thaw auctions margin:%s';
$_LANG['deduct_auction_deposit'] = 'After discounting the effect of the bond auctions:%s';

$_LANG['auction_status'][PRE_START] = 'Has not started yet';
$_LANG['auction_status'][UNDER_WAY] = 'Ongoing';
$_LANG['auction_status'][FINISHED] = 'Has ended';
$_LANG['auction_status'][SETTLED] = 'Has ended';

$_LANG['pls_search_goods'] = 'Please search for merchandise';
$_LANG['search_result_empty'] = 'Did not find merchandise, please re-english';

$_LANG['pls_select_goods'] = 'Please select merchandise auction';
$_LANG['goods_not_exist'] = 'You want to auction merchandise does not exist';

$_LANG['js_languages']['start_price_not_number'] = 'Starting price type is not correct (figure)';
$_LANG['js_languages']['end_price_not_number'] = 'A price type is not correct (figure)';
$_LANG['js_languages']['end_gt_start'] = 'A price greater than the starting price should be';
$_LANG['js_languages']['amplitude_not_number'] = 'The rate of increase is not formatted correctly (figure)';
$_LANG['js_languages']['deposit_not_number'] = 'Margin is not formatted correctly (figure)';
$_LANG['js_languages']['start_lt_end'] = 'Auction start time should not exceed the end of time';
$_LANG['js_languages']['search_is_null'] = 'Did not search any products, please re-search';

/* log */
$_LANG['bid_user'] = 'Buyer';
$_LANG['bid_price'] = 'Bid';
$_LANG['bid_time'] = 'Time';
$_LANG['status'] = 'State';

?>