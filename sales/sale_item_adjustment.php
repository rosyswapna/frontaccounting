<?php
$page_security = 'SA_CUSTOMER';
$path_to_root = "..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc");
$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "Item Adjustment"), @$_REQUEST['popup'], false, "", $js); 


	display_error(_("Selected Item not in Stock."));
	

	start_form();

		hidden('popup', @$_REQUEST['popup']);

		div_start('controls');

			 submit_mix_material_return("mix_material_select", MIX_MATERIAL,"Get Mix Material into sales delivery");

			
		div_end();

	end_form();


end_page(@$_REQUEST['popup']);
?>
