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
include($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");

//get shipping id if it has passed through url
if (isset($_GET['shipping_id'])) 
{
	$_POST['shipping_id'] = $_GET['shipping_id'];
}
$selected_id = get_post('shipping_id','');
//---------------------------------------------------------------------------

function shipping_details_settings($selected_id){
	global $path_to_root;
	if($selected_id){
		$myrow = get_shipping_detail($selected_id);
		$_POST['shipping_id'] = $myrow["shipping_id"];
		$_POST['customer_id'] = $myrow["debtor_no"];
		$_POST['vehicle_no']  = $myrow["vehicle_no"];
	}else{
		
			$_POST['shipping_id'] = $_POST['customer_id'] = -1;
			$_POST['vehicle_no'] = '';
		
	}

	start_table(TABLESTYLE, "width=80%", 10);
		start_row();
			customer_list_row(_("Customer:"), 'customer_id', $_POST['customer_id'], false, false, false, true);

			vehicle_row(_("Vehicle Number").':', 'vehicle_no', _(''), $_POST['vehicle_no'], '');

		end_row();
	end_table(1);
	if (!$selected_id)
	{
		submit_center('submit', _("Add New"), true, '', 'default');
		//submit_center('submit','Add New',true);
	}else{
		submit_center('submit','Update',true);
	}
	
}

//validation for submit action
function can_process()
{
	if (strlen($_POST['vehicle_no']) == 0) 
	{
		display_error(_("The vehicle number cannot be empty."));
		set_focus('vehicle_no');
		return false;
	} 

	return true;
}
//---------------------------------------------------------------------------


//form submit function
function handle_submit(&$selected_id)
{
	global $path_to_root, $Ajax, $auto_create_branch;

	if (!can_process())
		return;

	if ($selected_id) 
	{
		//it is an existing shipping details
		update_shipping_details($_POST['shipping_id'],$_POST['customer_id'],$_POST['vehicle_no']);
		display_notification(_("Shipping details has been updated."));

	} 
	else 
	{ 	//it is a new entry
		begin_transaction();

		add_shipping_details($_POST['customer_id'],$_POST['vehicle_no']);
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


add_access_extensions();
set_ext_domain('modules/salescontainer');

page(_($help_context = "Container Details Entry"));	

start_form();

if (db_has_shipping_details()) 
{
	start_table(TABLESTYLE_NOBORDER);
		start_row();
			shipping_list_row(_("Shipping Details Id: "), 'shipping_id', null,
			_('New shipping details'), true, check_value('show_inactive'));
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

shipping_details_settings($selected_id);

end_form();

end_page();