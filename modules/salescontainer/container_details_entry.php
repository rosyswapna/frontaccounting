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
if ($use_date_picker)
	$js .= get_js_date_picker();

include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/includes/date_functions.inc");

//get shipping id if it has passed through url
if (isset($_GET['shipping_id'])) 
{
	$_POST['shipping_id'] = $_GET['shipping_id'];
}elseif (isset($_GET['ModifyShipment'])){
	$_POST['shipping_id'] = $_GET['ModifyShipment'];
}
$selected_id = get_post('shipping_id','');


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

function shipping_details($selected_id){
	global $path_to_root;
	if($selected_id){
		$myrow = get_shipping_detail($selected_id);
		
		$_POST['shipping_id'] = $myrow["shipping_id"];
		$_POST['customer_id'] = $myrow["debtor_no"];
		$_POST['vehicle_details']  = $myrow["vehicle_details"];
		$_POST['shipment_status']  = $myrow["shipment_status"];
		$_POST['first_weight']  = $myrow["first_weight"];
		$_POST['first_weight_date']  = $myrow["first_weight_date"];
		$_POST['second_weight']  = $myrow["second_weight"];
		if($myrow["second_weight_date"]!='0000-00-00 00:00:00')
			$_POST['second_weight_date']  = $myrow["second_weight_date"];

	}else{

		$_POST['shipping_id'] = $_POST['customer_id'] = -1;
		$_POST['vehicle_details']  = '';
		$_POST['shipment_status']  = 1;
		$_POST['first_weight']  = '';
		$_POST['first_weight_date']  = '';
		$_POST['second_weight']  = '';
		$_POST['second_weight_date']  = '';
		
	}


	

	start_table(TABLESTYLE, "width=80%", 10);
		start_row();
			customer_list_cells(_("Customer:"), 'customer_id', $_POST['customer_id'], false, false, false, true);

			vehicle_cells(_("Vehicle Number").':', 'vehicle_details', _(''), $_POST['vehicle_details'], '');

			shipment_status_cells(_("Shipment Status").':', 'shipment_status', _(''), $_POST['shipment_status']);

		end_row();

		start_row();
			echo "<td colspan='6'>";
				start_outer_table(TABLESTYLE2);
					table_section(1);
					table_section_title(_("First Weight Details"));
						first_weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '');
						date_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date'], '');

					table_section(2);
					table_section_title(_("Second Weight Details"));
						first_weight_row(_("Second Weight").':', 'second_weight', _(''), $_POST['second_weight'], '');
						date_row(_("Second Weight Date").':', 'second_weight_date', _(''), $_POST['second_weight_date'], '');
				end_outer_table(1);
			
			echo "</td>";
		end_row();

	end_table(2);

	div_start('controls');
		if (!$selected_id)
		{
			//submit_center('submit', _("Add New Shipping Details"), true, '', 'default');
			submit_center('submit', _("Add New"), true, 'Add New Shipping Details');
		}else{
			submit_center('submit','Update',true);
		}
	div_end();
	
}

function open_shipping_details_settings($selected_id){
	global $path_to_root;

	if($selected_id){

		$myrow = get_shipping_detail($selected_id);
		
		$_POST['shipping_id'] = $myrow["shipping_id"];
		$_POST['customer_id'] = $myrow["debtor_no"];
		$_POST['vehicle_details']  = $myrow["vehicle_details"];
		$_POST['first_weight']  = $myrow["first_weight"];
		$_POST['first_weight_date']  = $myrow["first_weight_date"];
		$_POST['shipment_status']  = $myrow["shipment_status"];

	}else{

		$_POST['shipping_id'] = $_POST['customer_id'] = -1;
		$_POST['vehicle_details']  = '';
		$_POST['first_weight']  = '';
		$_POST['first_weight_date']  = '';	
		$_POST['shipment_status']  = SHIPMENT_STATUSOPEN;	
	}


	start_table(TABLESTYLE, "width=60%", 10);

		start_row();

			customer_list_cells(_("Customer:"), 'customer_id', $_POST['customer_id'], false, false, false, true);

			vehicle_cells(_("Vehicle Number").':', 'vehicle_details', _(''), $_POST['vehicle_details'], '');

		end_row();

		start_row();

			echo "<td colspan='4'>";
				start_outer_table(TABLESTYLE2);
					table_section(1);
					table_section_title(_("First Weight Details"));
						first_weight_row(_("First Weight").':', 'first_weight', _(''), $_POST['first_weight'], '');
						date_row(_("First Weight Date").':', 'first_weight_date', _(''), $_POST['first_weight_date'], '');
					
				end_outer_table(1);
			echo "</td>";

		end_row();

	end_table(2);

	div_start('controls');
		if (!$selected_id)
		{
			//submit_center('submit', _("Add New Shipping Details"), true, '', 'default');
			submit_center('submit', _("Add New"), true, 'Add New Shipping Details');
		}else{
			submit_center('submit','Update',true);
		}
	div_end();
	
}

function close_shipping_details_settings($selected_id){
	global $path_to_root;

	if($selected_id){
		$myrow = get_shipping_detail($selected_id);
		
		$_POST['shipping_id'] = $myrow["shipping_id"];
		

		if($myrow["shipment_status"] == SHIPMENT_STATUSCLOSE){
			$_POST['shipment_status']  = $myrow["shipment_status"];
		}else{
			$_POST['shipment_status'] = SHIPMENT_STATUSCLOSE;
		}	

	}else{

	
	}
	

	start_table(TABLESTYLE, "width=60%", 10);
		start_row();

			label_cells(_("Customer:"), $myrow["customer"]);
			
			label_cells(_("Vehicle Number:"), $myrow["vehicle_details"]);

		end_row();

		start_row();
			echo "<td colspan='4'>";
				start_outer_table(TABLESTYLE2);
					table_section(1);
					table_section_title(_("First Weight Details"));

						label_row(_("First Weight:"), $myrow["first_weight"]);

						label_row(_("First Weight Date:"), $myrow["first_weight_date"]);
					

					table_section(2);
					table_section_title(_("Second Weight Details"));

						first_weight_row(_("Second Weight").':', 'second_weight', _(''), $_POST['second_weight'], '');

						date_row(_("Second Weight Date").':', 'second_weight_date', _(''), $_POST['second_weight_date'], '');

				end_outer_table(1);
			
			echo "</td>";
		end_row();

	end_table(2);

	div_start('controls');
		if ($selected_id)
			submit_center('close','Update',true);
	div_end();
	
}

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


//form submit function for open shipment
function handle_submit(&$selected_id)
{

	global $path_to_root, $Ajax, $auto_create_branch;

	if (!can_process())
		return;

	if ($selected_id) 
	{
		//it is an existing shipping details
		update_shipping_details($selected_id,$_POST['customer_id'],$_POST['vehicle_details'],$_POST['shipment_status'],$_POST['first_weight'],$_POST['first_weight_date']);
		display_notification(_("Shipping details has been updated."));

	} 
	else 
	{ 	//it is a new entry
		begin_transaction();

		add_shipping_details($_POST['customer_id'],$_POST['vehicle_details'],$_POST['shipment_status'],$_POST['first_weight'],$_POST['first_weight_date']);
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


add_access_extensions();
set_ext_domain('modules/salescontainer');

//page(_($help_context = "Add and Manage Shipments"));
page($_SESSION['page_title'], false, false, "", $js);	

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
				_('New shipment'), true, check_value('show_inactive'));
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