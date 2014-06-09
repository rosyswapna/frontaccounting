<?php

$page_security = 'SA_SALESCONTAINERSVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

//page(_($help_context = "Shipments"));

if (get_post('type'))
	$trans_type = $_POST['type'];

//--------------------------------------------------------------------------------------------

if (!@$_GET['popup'])
{
	$js = "";
	if ($use_popup_windows)
		$js .= get_js_open_window(900, 600);
	if ($use_date_picker)
		$js .= get_js_date_picker();
		//page($_SESSION['page_title'], false, false, "", $js);
	page("Shipments", false, false, "", $js);
}

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
	global $trans_type;
	return  get_shipment_view_str($shipment_id,$dummy['shipping_id']);
	
}

function edit_link($row)
{	
	$modify = "ModifyShipment";
	//return pager_link( _("Edit"),
   // "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id'], ICON_EDIT);
	if($row['shipment_status'] == SHIPMENT_STATUSOPEN){
		return pager_link( _($row['status_description']),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id']);
	}elseif($row['shipment_status'] == SHIPMENT_STATUSCLOSE){
		return pager_link( _($row['status_description']),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id']);
	}
	
}

function close_link($row)
{
	$modify = "CloseShipment";
	if($row['shipment_status'] == SHIPMENT_STATUSCLOSE){
		return "";
	}else{
		return pager_link( _("Close Shipment"),
	    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id'], ICON_OK);
	}
}

function change_tpl()
{
	global	$Ajax;

	$Ajax->activate('shipments_tbl');
}
//----------------------------------------------------------------------------------------------------

//	shipment details
//
if (get_post('_shipment_id_changed') || get_post('_vehicle_details_changed') ) // enable/disable selection controls
{
	$disable = get_post('shipment_id') !== '' || get_post('vehicle_details') !== '';

	$Ajax->activate('shipments_tbl');
}
//---------------------------------------------------------------------------------------------------

if (!@$_GET['popup'])
	start_form();

start_table(TABLESTYLE_NOBORDER,'width=80%');

	start_row();

		ref_cells(_("#:"), 'shipment_id', '',null, '', true);
		if (!@$_GET['popup'])
			customer_list_cells(_("Select a customer: "), 'customer_id', null, true, true);

		vehicle_cells(_("Vehicle Number").':', 'vehicle_details', _(''), null, '');

		//vehicle_list_cells(_("Select a Vehicle Number: "), 'customer_id', null, true, true);

		shipment_status_list_cells(_("Shipment Status").':', 'shipment_status', null, true, true);
		

		submit_cells('SearchShipments', _("Search"),'',_('Select documents'), 'default');

	end_row();

end_table(1);


//---------------------------------------------------------------------------------------------------


//	shipments inquiry table
//
$sql = get_sql_for_shipment_view($selected_customer,@$_POST['shipment_id'],@$_POST['vehicle_details'],@$_POST['shipment_status'],$_POST['customer_id']);

$cols = array(
		_("Shipment #") => array('fun'=>'view_link'),
		_("Customer") => array('type' => 'debtor.name') ,
		_("Vehicle Details"),
		_("Container No"),
		_("First Weight"), 
		_("First Weight Date")=>array('type'=>'date'),  
		_("Second Weight"),
		_("Second Weight Date")=>array('type'=>'date'),
		_("Status")	=> array('fun' => 'edit_link')	
	);


array_append($cols,array(
					//array('insert'=>true, 'fun'=>'edit_link'),
					array('insert'=>true, 'fun'=>'close_link')));


$table =& new_db_pager('shipments_tbl', $sql, $cols);

$table->width = "90%";

display_db_pager($table);

submit_center('Update', _("Update"), true, '', null);

if (!@$_GET['popup'])
{
	end_form();
	end_page();
}

?>