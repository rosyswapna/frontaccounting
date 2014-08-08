<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_SALESCONTAINER';
$path_to_root = "../..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");



$js = "";
if ($use_popup_windows) {
	$js .= get_js_open_window(900, 500);
}
if ($use_date_picker)
	$js .= get_js_date_picker();

include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/includes/date_functions.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

//page(_($help_context = "Add and Manage Shipments"));
if (isset($_GET['CloseShipment'])) {
	$_SESSION['page_title'] = _($help_context = "Close Shipment");
}else{
	$_SESSION['page_title'] = _($help_context = "Add and Manage Shipment");
}
page($_SESSION['page_title'], false, false, "", $js);



//get shipping id if it has passed through url
if (isset($_GET['shipping_id'])) 
{
	$_POST['shipping_id'] = $_GET['shipping_id'];
}elseif (isset($_GET['ModifyShipment'])){
	$_POST['shipping_id'] = $_GET['ModifyShipment'];
}
$selected_id = get_post('shipping_id','');


if(list_updated('person_type')){
	$_POST['person_id'] = '';
	$Ajax->activate('shipment_content');

}


//---------------------------------------------------------------------------


if (isset($_GET['ModifyShipment'])) {

	$_SESSION['page_title'] = _($help_context = "Modify Shipment");

}elseif (isset($_GET['CloseShipment'])) {

	$_SESSION['page_title'] = _($help_context = "Close Shipment");

	$selected_id = $_GET['CloseShipment'];


}else{

	$_SESSION['page_title'] = _($help_context = "Add and Manage Shipments");
	
}

//---------------------------------------------------------------------------

if (isset($_POST['CancelShipment']))
	handle_cancel_shipment($_POST['shipping_id']);


//---------------------------------------------------------------------------

function close_link_row($row)
{ 
	$modify = "CloseShipment";
	if($row['shipment_status'] == SHIPMENT_STATUSCLOSE){
		return "";
	}else{
		start_row();
		echo "<td>";
		echo "<center>Click ";
		echo pager_link( _("here"),
	    "/modules/salescontainer/container_details_entry.php?$modify=" . $row['shipping_id']);
	    echo " to close this shipment</center>";
	    echo "</td>";
	    end_row();
	}
}

//---------------------------------------------------------------------------
function shipment_header($editkey = false){

	start_row();
	echo "<td>";
		start_outer_table(TABLESTYLE2);

			table_section(1);

			
			shipping_person_types_list_row(_("Person Type"),'person_type',$_POST['person_type'],true);

			switch ($_POST['person_type'])
			{
				case PT_MISC :
		    		text_row_ex(_("Name:"),'person_id', 40, 50);
		    		break;
				case PT_SUPPLIER :
		    		supplier_list_row(_("Supplier:"), 'person_id', null, false, false, false, true);
		    		//supplier_list_row(_("Supplier:"), 'person_id', $_POST['person_id'], false, false, false, true);
		    		break;
				case PT_CUSTOMER :
		    		customer_list_row(_("Customer:"), 'person_id', null, false, false, false, true);
		    		//customer_list_row(_("Customer:"), 'person_id', $_POST['person_id'], false, false, false, true);
			}

			

			table_section(2);

			if($editkey)
				vehicle_row(_("Vehicle Number").':', 'vehicle_details', _(''), $_POST['vehicle_details'],false,true,'fweight');
			else
				vehicle_row(_("Vehicle Number").':', 'vehicle_details', _(''), $_POST['vehicle_details']);
			
			container_row(_("Container No").':', 'container_no', _(''), $_POST['container_no'], '');

		end_outer_table(1);
	echo "</td>";
	end_row();
}

//------------------------------------------------------------------------------------------------------
/*edit shipping details on change shipping id in list .
you can edit only container number,person id and person type*/
function shipping_details($selected_id){

	if($selected_id){
		$myrow = get_shipping_detail($selected_id);
		$_POST['person_type'] = $myrow["person_type_id"];

		if($myrow["person_type_id"] == PT_CUSTOMER){
			$customer = get_customer($myrow["person_id"]);
			$_POST['person_id'] = $customer["debtor_no"];
		}
		
		$_POST['shipping_id'] = $myrow["shipping_id"];
		$_POST['vehicle_details']  = $myrow["vehicle_details"];
		$_POST['container_no']  = $myrow["container_no"];
		$_POST['first_weight']  = $myrow["first_weight"];
		$_POST['first_weight_date']  = $myrow["first_weight_date"];
		$_POST['shipment_status']  = $myrow["shipment_status"];		
		$_POST['second_weight']  = $myrow["second_weight"];
		$_POST['second_weight_date']  = $myrow["second_weight_date"];

		div_start('shipment_content');
		start_table(TABLESTYLE, "width=60%", 10);
		
			shipment_header();

			start_row();

				echo "<td>";
					start_outer_table(TABLESTYLE2);
						table_section(1);

						table_section_title(_("First Weight Details"));					
						if($myrow["shipment_status"] == SHIPMENT_STATUSCLOSE){

							weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '');
							weight_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date'], '');

							table_section_title(_("Second Weight Details"));
							weight_row(_("Second Weight").':', 'second_weight', _(''), $_POST['second_weight'], '',false);
							weight_row(_("Second Weight Date").':', 'second_weight_date', _(''), $_POST['second_weight_date'], '');
						}else{
							weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '');
							weight_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date']);

						}
					end_outer_table(1);
				echo "</td>";

			end_row();

			
			close_link_row($myrow);
			

			end_row();

		end_table(2);

		div_end();

		div_start('controls');
			if (!$selected_id)
			{
				submit_center_first('submit', _("Add New"), 'Add New Shipping Details');

				submit_js_confirm('CancelShipment', _('You are about to void this Document.\nDo you want to continue?'));
			}else{
				submit_center_first('submit',_('Update'),'Update Shipping details');

				submit_js_confirm('CancelShipment', _('You are about to cancel this shipment.\nDo you want to continue?'));
			}

			submit_center_last('CancelShipment', "Cancel",
		   _('Cancel Shipment or Removes Shipment'));

		div_end();
	}
	
}

//---------------------------------------------------------------------------------------------
//new shipment or edit shipment
function open_shipping_details_settings($selected_id){

	global $path_to_root, $Ajax;

	if($selected_id){

		shipping_details($selected_id);

	}else{

		$myrow = false;
		//$_POST['person_type'] = '';
		//$_POST['person_id'] = '';
		$_POST['shipping_id'] = -1;
		$_POST['vehicle_details']  = '';
		$_POST['container_no']  = '';
		$_POST['first_weight']  = '';
		$_POST['first_weight_date']  = '';	
		$_POST['shipment_status']  = SHIPMENT_STATUSOPEN;	

		div_start('shipment_content');
		
		start_table(TABLESTYLE, "width=60%", 10);
			
			shipment_header(true);

			start_row();

				echo "<td>";
					start_outer_table(TABLESTYLE2);
						table_section(1);

						table_section_title(_("First Weight Details"));					
						if($myrow["shipment_status"] == SHIPMENT_STATUSCLOSE){

						weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '');
						date_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date'], '');

						table_section_title(_("Second Weight Details"));
							weight_row(_("Second Weight").':', 'second_weight', _(''), $_POST['second_weight'], '',false);
							date_row(_("Second Weight Date").':', 'second_weight_date', _(''), $_POST['second_weight_date'], '');
						}else{
							weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '',true,'fweight');

							weight_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date'], '',true,'fweight');

						}
					end_outer_table(1);
				echo "</td>";

			end_row();

		end_table(2);

		div_end();

		div_start('controls');
			if (!$selected_id)
			{
				submit_center_first('submit', _("Add New"), 'Add New Shipping Details');

				submit_js_confirm('CancelShipment', _('You are about to void this Document.\nDo you want to continue?'));
			}else{
				submit_center_first('submit',_('Update'),'Update Shipping details');

				submit_js_confirm('CancelShipment', _('You are about to cancel this shipment.\nDo you want to continue?'));
			}

			submit_center_last('CancelShipment', "Cancel",
		   _('Cancel Shipment or Removes Shipment'));

		div_end();
	}
	
}


//close shipment 
function close_shipping_details_settings($selected_id){
	global $path_to_root;

	if($selected_id){
		$myrow = get_shipping_detail($selected_id);

		$_POST['person_type'] = $myrow["person_type_id"];

		if($myrow["person_type_id"] == PT_CUSTOMER){
			$customer = get_customer($myrow["person_id"]);
			$_POST['person_id'] = $customer["debtor_no"];
		}elseif($myrow["person_type_id"] == PT_SUPPLIER){
			$supplier = get_supplier($myrow["person_id"]);
			$_POST['person_id'] = $supplier["supplier_id"];
		}else{
			$_POST['person_id'] =$myrow["person_id"];
		}
		
		$_POST['vehicle_details']  = $myrow["vehicle_details"];
		$_POST['container_no']  = $myrow["container_no"];
		$_POST['first_weight']  = $myrow["first_weight"];
		$_POST['first_weight_date']  = sql2date($myrow["first_weight_date"]);
		

		
		$_POST['second_weight']  = $myrow["second_weight"];
		//$_POST['second_weight_date']  = sql2date($myrow["second_weight_date"]);
		
		$_POST['shipping_id'] = $myrow["shipping_id"];
		

		if($myrow["shipment_status"] == SHIPMENT_STATUSCLOSE){
			$_POST['shipment_status']  = $myrow["shipment_status"];
		}else{
			$_POST['shipment_status'] = SHIPMENT_STATUSCLOSE;
		}	

		start_table(TABLESTYLE, "width=60%", 10);

			shipment_header();

			start_row();
				echo "<td colspan='4'>";
					start_outer_table(TABLESTYLE2);
						table_section(1);
						table_section_title(_("First Weight Details"));

							label_row(_("First Weight:"), $myrow["first_weight"]);

							label_row(_("First Weight Date:"), $myrow["first_weight_date"]);
						

						table_section(2);
						table_section_title(_("Second Weight Details"));

							weight_row(_("Second Weight").':', 'second_weight', _(''), $_POST['second_weight'], '',true,'sweight');

							weight_row(_("Second Weight Date").':', 'second_weight_date', _(''), $_POST['second_weight_date'], '',true,'sweight');

					end_outer_table(1);
				
				echo "</td>";
			end_row();

		end_table(2);

		div_start('controls');
			if ($selected_id){
				submit_center_first('close', _("Update"), 'Update Shipping Details');
				//submit_center('close','Update',true);

				submit_js_confirm('CancelShipment', _('You are about to cancel this shipment.\nDo you want to continue?'));
			}

			submit_center_last('CancelShipment', "Cancel",_('Cancels Shipment or Removes Shipment'));
		div_end();
	}
	
}
//-----------------------------------------------------------------------------------------------------------------
//validation for submit action
function can_process()
{
	if (strlen($_POST['vehicle_details']) == 0) 
	{
		display_error(_("The vehicle number cannot be empty."));
		set_focus('vehicle_details');
		return false;
	} 

	if (strlen($_POST['first_weight']) == 0) 
	{
		display_error(_("The first weight cannot be empty."));
		set_focus('first_weight');
		return false;
	} 

	return true;
}
//---------------------------------------------------------------------------

function  handle_cancel_shipment($selected_id)
{
	global $path_to_root, $Ajax;
	if ($selected_id) 
	{
		delete_shipment($selected_id);
		display_notification(_("Shipping details has been cancelled."));
	}
}

//---------------------------------------------------------------------------

//form submit function for open shipment
function handle_submit(&$selected_id)
{

	global $path_to_root, $Ajax, $auto_create_branch;

	if (!can_process())
		return;

	if ($selected_id) 
	{
		//it is an existing shipping details
		update_shipping_details($selected_id, $_POST['vehicle_details'],$_POST['container_no'],$_POST['shipment_status'],$_POST['first_weight'],$_POST['first_weight_date'],$_POST['second_weight'],$_POST['second_weight_date'], $_POST['person_type'], $_POST['person_id']);
		display_notification(_("Shipping details has been updated."));

	} 
	else 
	{ 	//it is a new entry
		begin_transaction();

		add_shipping_details($_POST['vehicle_details'],$_POST['container_no'],$_POST['shipment_status'],$_POST['first_weight'],$_POST['first_weight_date'],$_POST['person_type'], $_POST['person_id']);
		$selected_id = $_POST['shipping_id'] = db_insert_id();

		commit_transaction();

		display_notification(_("A new shipping details has been added."));

		$Ajax->activate('_page_body');
	}
}
//-----------------------------------------------------------------------------

//form submit action
if (isset($_POST['submit'])) 
{
	handle_submit($selected_id);
}
//-------------------------------------------------------------------------------------------- 

//form submit action
if (isset($_POST['close'])) 
{
	global $path_to_root, $Ajax, $auto_create_branch;

	if ($selected_id) 
	{
		//it is an existing shipping details
		close_shipment($selected_id,$_POST['shipment_status'],$_POST['second_weight'],$_POST['second_weight_date']);
		display_notification(_("Shipping details has been closed."));
	}
}
//-------------------------------------------------------------------------------------------- 


	

start_form();


	if (isset($_GET['CloseShipment'])) {
		close_shipping_details_settings($selected_id);
		hidden('shipping_id');
	}else{

		if (db_has_shipping_details()) 
		{
			start_table(TABLESTYLE_NOBORDER);
			start_row();
			shipping_list_cells(_("Shipping Details Id: "), 'shipping_id', null,
				_('New shipment'), true, check_value('show_inactive'),false,false);

			

			check_cells(_("Show inactive:"), 'show_inactive', null, true);
			end_row();
			end_table();

			if (get_post('_show_inactive_update')) {
				$Ajax->activate('shipping_id');
				set_focus('shipping_id');
			}


		} 
		else 
		{
			hidden('shipping_id');
		}

		open_shipping_details_settings($selected_id);
	}

	hidden('shipment_status');

end_form();

end_page();