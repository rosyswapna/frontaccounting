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



$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(400, 400);
if ($use_date_picker)
	$js .= get_js_date_picker();


if(isset($_GET['fweight'])){
	$task = "NewShipment";
}elseif(isset($_GET['sweight'])){
	$task = "CloseShipment";
}


if(!isset($_GET['data'])){
	header('Location:http://localhost/comport_reader?tsk='.$task);

}else{
	
	page(_($help_context = "Weight"), @$_REQUEST['popup'], false, "", $js); 


	start_form();


	if(isset($_GET['data'])){

		if(isset($_GET['vh'])){
			$vehicle = $_GET['vh'];
		}

		if(isset($_GET['fw'])){
			$fweight = abs($_GET['fw']);
		}

		if(isset($_GET['sw'])){
			$sweight = abs($_GET['sw']);
		}

		if(isset($_GET['dt'])){
			$wdate = $_GET['dt'];
		}

		if(isset($sweight))
				$weight = $sweight;
		else if(isset($fweight))
				$weight = $fweight;
		else
				$weight = 0;

		start_table(TABLESTYLE, "width=60%", 10);

			if(isset($vehicle))
				label_row("Vehicle",$vehicle);

			if(isset($fweight))
				label_row("First Weight",$fweight);

			if(isset($sweight))
				label_row("Second Weight",$sweight);

			if(isset($wdate))
				label_row("Date",$wdate);

		end_table();
		
		
			div_start('controls');
				
				submit_return_center('select', $weight, _("Select this shipping details and return to document entry."));
				//submit_return_center('select', $_SESSION['vehicle'], _("Select this shipping details and return to document entry."));
					

			div_end();

			hidden('popup', @$_REQUEST['popup']);
			end_form();

	}else{
		display_error("Data File not Received.");
	}


	end_page(@$_REQUEST['popup']);
}

?>