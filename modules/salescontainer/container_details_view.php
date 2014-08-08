<?php

$page_security = 'SA_SALESCONTAINERSVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');


if (get_post('type'))
	$trans_type = $_POST['type'];

//--------------------------------------------------------------------------------------------
echo $trans_type;
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




//---------------------------------------------------------------------------------------------

if(list_updated('person_type')){
	$_POST['person_id'] = '';
	$Ajax->activate('shipment_header');

}


//	Query format functions
function view_link($dummy, $shipment_id)
{
	global $trans_type;
	return  get_shipment_view_str($shipment_id,$dummy['shipping_id']);
	
}

function edit_link($row)
{	
	$modify = "ModifyShipment";
	
	/*
	if($row['shipment_status'] == SHIPMENT_STATUSOPEN){
		return pager_link( _($row['status_description']),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id']);
	}elseif($row['shipment_status'] == SHIPMENT_STATUSCLOSE){
		return pager_link( _($row['status_description']),
    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id']);
	}
	*/
	return $row['status_description'];
	
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

function prt_link($row)
{
	global $trans_type;
	return print_link(_("Print"),400,array('PARAM_0'=>$row['shipping_id']),'',ICON_PRINT);
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

div_start('shipment_header');

start_outer_table(TABLESTYLE2);

		table_section(1);

		ref_row(_("#:"), 'shipment_id', '',null, '', true);

		table_section(2);
		shipping_person_types_list_row(_("Person Type"),'person_type',$_POST['person_type'],true);

		switch ($_POST['person_type'])
		{
			case PT_MISC :
	    		text_row_ex(_("Name:"),'person_id', 10, 50);
	    		break;
			case PT_SUPPLIER :
	    		supplier_list_row(_("Supplier:"), 'person_id', null, true, false, false, true);
	    		//supplier_list_row(_("Supplier:"), 'person_id', $_POST['person_id'], false, false, false, true);
	    		break;
			case PT_CUSTOMER :
	    		customer_list_row(_("Customer:"), 'person_id', null, true, false, false, true);
	    		//customer_list_row(_("Customer:"), 'person_id', $_POST['person_id'], false, false, false, true);
	    		break;
	    	
		}

		
		table_section(3);
		vehicle_row(_("Vehicle Number").':', 'vehicle_details', _(''), null, '');
		
		
		table_section(4);
		shipment_status_list_row(_("Shipment Status").':', 'shipment_status', null, true, true);
		
		table_section(5);
		submit_row('SearchShipments', _("Search"),'',_('Select documents'), 'default');

	
end_outer_table(1);

div_end();
//---------------------------------------------------------------------------------------------------


//	shipments inquiry table
//

$sql = get_sql_for_shipment_view(@$_POST['shipment_id'],@$_POST['vehicle_details'],@$_POST['shipment_status'],$_POST['person_type'],@$_POST['person_id']);

$cols = array(
		_(" #") => array('fun'=>'view_link'),
		_("Customer/Supplier")=>array('type'=>'name'),
		_("Vehicle Details"),
		_("Container No"),
		_("First Weight"), 
		_("First Weight Date")=>array('type'=>'datetime'),  
		_("Second Weight"),
		_("Second Weight Date")=>array('type'=>'datetime'),
		_("Status")	=> array('fun' => 'edit_link')	
	);


array_append($cols,array(
					array('insert'=>true, 'fun'=>'prt_link'),
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