<?php
//customized by swapna

$path_to_root = "..";
$page_security = 'SA_SALESCONTAINER';

include_once($path_to_root . "/sales/includes/cart_class.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/manufacturing.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");
include_once($path_to_root . "/taxes/tax_calc.inc");



$js = '';

if ($use_popup_windows) {
	$js .= get_js_open_window(900, 500);
}

if ($use_date_picker) {
	$js .= get_js_date_picker();
}

if (isset($_GET['SalesContainer'])) {
	$_SESSION['page_title'] = _($help_context = "Sales Container Entry");
}

page($_SESSION['page_title'], false, false, "", $js);
//-----------------------------------------------------------------------------

start_form();
start_table(TABLESTYLE2, "width=80%", 5);
	start_row();
		label_cells(_("Sales Type"), '', "class='tableheader2'");
	end_row();	
end_table();
ecd_form();
end_page();

?>