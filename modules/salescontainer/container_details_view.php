<?php

$page_security = 'SA_SALESCONTAINERVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

page(_($help_context = "Shipments"));

//--------------------------------------------------------------------------------------------

if (isset($_GET['selected_customer']))
{
	$selected_customer = $_GET['selected_customer'];
}
elseif (isset($_POST['selected_customer']))
{
	$selected_customer = $_POST['selected_customer'];
}
else
	$selected_customer = -1;


//---------------------------------------------------------------------------------------------
//	Query format functions
function view_link($dummy, $shipment_id)
{
	//return  get_shipment_view_str($order_no);
	return $shipment_id;
}

function edit_link($row)
{	
	$modify = "ModifyShipment";
	return pager_link( _("Edit"),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id'], ICON_EDIT);
	
}

function close_link($row)
{
	$modify = "CloseShipment";
	return pager_link( _("Close Shipment"),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id'], ICON_OK);
}
//----------------------------------------------------------------------------------------------------

start_form();

start_table(TABLESTYLE_NOBORDER,'width=80%');

	start_row();

		ref_cells(_("#:"), 'shipment_id', '',null, '', true);

		customer_list_cells(_("Select a customer: "), 'customer_id', null, true, true);

		vehicle_cells(_("Vehicle Number").':', 'vehicle_details', _(''), null, '');

		//vehicle_list_cells(_("Select a Vehicle Number: "), 'customer_id', null, true, true);

		shipment_status_cells(_("Shipment Status").':', 'shipment_status', _(''), null);

	

		

		submit_cells('SearchShipments', _("Search"),'',_('Select documents'), 'default');

	end_row();

end_form();

//---------------------------------------------------------------------------------------------------


//	shipments inquiry table
//
$sql = get_sql_for_shipment_view($selected_customer,@$_POST['shipment_id'],@$_POST['vehicle_details'],@$_POST['shipment_status']);

$cols = array(
		_("Shipment #"),// => array('fun'=>'view_link'),
		_("Customer") => array('type' => 'debtor.name') ,
		_("Vehicle Details"),
		_("First Weight"), 
		_("First Weight Date")=>array('type'=>'date'),  
		_("Second Weight"),
		_("Second Weight Date")=>array('type'=>'date'),
		_("Status")		
	);

array_append($cols,array(
					array('insert'=>true, 'fun'=>'edit_link'),
					array('insert'=>true, 'fun'=>'close_link')));

$table =& new_db_pager('shipments_tbl', $sql, $cols);

$table->width = "90%";

display_db_pager($table);



end_page();

?>