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

	$dataArray = array();


	
	

	if(isset($_GET['vh'])){
		$dataArray['vehicle_details'] = $_GET['vh'];
	}

	if(isset($_GET['fw'])){
		$dataArray['first_weight'] = abs($_GET['fw']);
		$dataArray['first_weight_date'] = date('d-m-Y H:i:s');
		/*if(isset($_GET['dt'])){
			$dataArray['first_weight_date'] = $_GET['dt'];
		}*/
	}

	if(isset($_GET['sw'])){
		$dataArray['second_weight'] = abs($_GET['sw']);
		$dataArray['second_weight_date'] = date('d-m-Y H:i:s');
		/*if(isset($_GET['dt'])){
			$dataArray['second_weight_date'] = $_GET['dt'];
		}*/
	}

		
	if($dataArray){
		

		start_table(TABLESTYLE, "width=60%", 10);

			if(isset($dataArray['vehicle_details']))
				label_row("Vehicle",$dataArray['vehicle_details']);

			if(isset($dataArray['first_weight']))
				label_row("First Weight",$dataArray['first_weight']);

			if(isset($dataArray['first_weight_date']))
				label_row("First Weight Date",$dataArray['first_weight_date']);

			if(isset($dataArray['second_weight']))
				label_row("Second Weight",$dataArray['second_weight']);

			if(isset($dataArray['second_weight_date']))
				label_row("Second Weight Date",$dataArray['second_weight_date']);

			if(isset($wdate))
				label_row("Date",$wdate);

		end_table();
		
		
		div_start('controls');

			submit_multi_return_center('select', $dataArray, _("Select this shipping details and return to document entry."));
		
		div_end();

		hidden('popup', @$_REQUEST['popup']);
	}else{
		display_error("Error in retrieving data.");
	}
	end_form();

	end_page(@$_REQUEST['popup']);
}

?>
