<?php


$path_to_root = "../..";
$page_security = 'SA_CQREMINDER';

include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/ui.inc");

add_access_extensions();


$js = "";

if ($use_popup_windows) {
	$js .= get_js_open_window(900, 500);
}



function show_trans_table()
{
	global $systypes_array;

	$bank_trans_result = get_bank_trans(null, null, null, null,1,'pdc');

	$k = 0;

	start_table(TABLESTYLE, "width=60%");

   		$th = array(_("#"), _("Transaction Type"), _("Cheque Date"),  _("Cheque Number"), _("Amount"), _("Trans Date"));

	   	table_header($th);
	   	
	   		while($row = db_fetch_assoc($bank_trans_result)){
	   			alt_table_row_color($k);
	   			//label_cell($row['trans_no']);
	   			label_cell(get_trans_view_str($row['type'], $row['trans_no']));
	   			label_cell($systypes_array[$row['type']]);
	   			label_cell($row['cheque_date']);
	   			label_cell($row['cheque_no']);
	   			label_cell(price_format(abs($row['amount'])));
	   			//label_cell($row['amount']);
	   			label_cell($row['trans_date']);
	   			end_row();
	   		}

	   	

	end_table(1);
}

page(_($help_context = "Cheque Reminders"), false, false, "", $js);



show_trans_table();


start_form();
	
	
	
	


end_form();

end_page();

?>