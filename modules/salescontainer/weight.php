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
$page_security = 'SA_WEIGHT';
$path_to_root = "../..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");



add_access_extensions();
set_ext_domain('modules/salescontainer');


if(!isset($_GET['data'])){
	header('Location:http://comport_reader.local');

}




$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(400, 400);
if ($use_date_picker)
	$js .= get_js_date_picker();


	
page(_($help_context = "Weight"), @$_REQUEST['popup'], false, "", $js); 


start_form();


if(isset($_GET['data'])){

	if(isset($_GET['vh'])){
		$_SESSION['vehicle'] = $_GET['vh'];
	}

	if(isset($_GET['fw'])){
		$_SESSION['fweight'] = abs($_GET['fw']);
	}

	if(isset($_GET['sw'])){
		$_SESSION['sweight'] = abs($_GET['sw']);
	}

	if(isset($_GET['dt'])){
		$_SESSION['wdate'] = $_GET['dt'];
	}

	if(isset($_SESSION['sweight']))
			$weight = $_SESSION['sweight'];
	else if(isset($_SESSION['fweight']))
			$weight = $_SESSION['fweight'];
	else
			$weight = 0;

	start_table(TABLESTYLE, "width=60%", 10);

		if(isset($_SESSION['vehicle']))
			label_row("Vehicle",$_SESSION['vehicle']);

		if(isset($_SESSION['fweight']))
			label_row("First Weight",$_SESSION['fweight']);

		if(isset($_SESSION['sweight']))
			label_row("Second Weight",$_SESSION['sweight']);

		if(isset($_SESSION['wdate']))
			label_row("Date",$_SESSION['wdate']);

	end_table();
	
	
		div_start('controls');
			
			submit_return_center('select', $_SESSION['fweight'], _("Select this shipping details and return to document entry."));
			//submit_return_center('select', $_SESSION['vehicle'], _("Select this shipping details and return to document entry."));
				

		div_end();

		hidden('popup', @$_REQUEST['popup']);
		end_form();

}else{
	display_error("Data File not Received.");
}


end_page(@$_REQUEST['popup']);

?>