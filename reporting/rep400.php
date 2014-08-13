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
$page_security = 'SA_SHIPMENTREPORT';
add_access_extensions();
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Chaitanya
// date_:	2005-05-19
// Title:	Sales Summary Report
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_db.inc");
include_once($path_to_root."/admin/db/attachments_db.inc");

//----------------------------------------------------------------------------------------------------
print_shipment();
//----------------------------------------------------------------------------------------------------

function get_shipment_details($fw_date,$sw_date,$ptype,$cid,$sid,$vehicle){


	$sql = "SELECT shipment.shipping_id,shipment.vehicle_details,
			shipment.container_no,
			shipment.driver_name,
			shipment.first_weight,
			shipment.first_weight_date,
			shipment.second_weight,
			shipment.second_weight_date,
			shipment.person_type_id,
			shipment.person_id
			FROM ".TB_PREF."shipping_details as shipment
			WHERE shipment.person_type_id = ".db_escape($ptype);
	if($fw_date)
		$sql .= " AND shipment.first_weight_date = '".date2sql($fw_date)."'";

	if($sw_date)
		$sql .= " AND shipment.second_weight_date = '".date2sql($sw_date)."'";
	
	if($ptype == PT_CUSTOMER && $cid > 0){
			$person_type = "CUSTOMER";
			$sql .= " AND shipment.person_id =".db_escape($cid);
	}elseif($type == PT_SUPPLIER && $sid > 0){
			$person_type = "SUPPLIER";
			$sql .= " AND shipment.person_id =".db_escape($sid);
	}else{
		$person_type = "MISCELLANEOUS";
	}
	
	if($vehicle)
		$sql .= " AND shipment.vehicle_details LIKE ".db_escape($vehicle)."";

	

	$rs = db_query($sql,"No Shipment Entries Found");
	$shipments = array();
	while ($row = db_fetch($rs)) {
		$person = get_person_details($row['person_type_id'],$row['person_id']);
		$shipments[] = array(
					'TICKET NUMBER' 	=> $row['shipping_id'],
					'DATE & TIME'   	=> date('d-M-Y h:i:s A'),
					'VEHICLE NUMBER' 	=> $row['vehicle_details'],
					'DRIVER\'S NAME'	=> $row['driver_name'],
					$person_type 		=> $person,
					'CONTAINER NO' 		=> $row['container_no'],
					'FIRST WEIGHT' 		=> $row['first_weight']." kg",
					'FIRST WEIGHT DATE' => $row['first_weight_date'],
					'SECOND WEIGHT' 	=> $row['second_weight']." kg",
					'SECOND WEIGHT DATE'=> $row['second_weight_date'],
					'NET WEIGHT'		=> abs($row['second_weight']-$row['first_weight'])." kg"	
					);
	}
	
	//echo "<pre>";print_r($shipments);echo "</pre>";exit();

	return $shipments;
}

function get_person_details($type,$id){

	if($type==PT_CUSTOMER){
		$customer = get_customer($id);
		return array('CUSTOMER',$customer['name']);
	}elseif($type == PT_SUPPLIER){
		$supplier = get_supplier($id);
		return array('SUPPLIER',$supplier['supp_name']);
	}else{
		return array('MISCELLANEOUS',$id);
	}
}

function print_shipment()
{
    global $path_to_root;

    
    $shp_id = $_POST['PARAM_0']; //first weight date

	$fw_date = $_POST['PARAM_1']; //first weight date
	$sw_date = $_POST['PARAM_2']; //second weight date
    $ptype = $_POST['PARAM_3']; //person type id
    $cid = $_POST['PARAM_4']; //customer id
    $sid = $_POST['PARAM_5']; //supplier id
    $vehicle = $_POST['PARAM_6']; //person id

	$orientation = $_POST['PARAM_7'];
	$destination = $_POST['PARAM_8'];


	$shipments = array();

	if($shp_id > 0){

		$single_report = true;
		$row = get_shipping_detail($shp_id);

		list($person_type,$person) = get_person_details($row['person_type_id'],$row['person_id']);

		$shipments[] = array(
					'TICKET NUMBER' 	=> $row['shipping_id'],
					'DATE & TIME'   	=> date('d-M-Y h:i:s A'),
					'VEHICLE NUMBER' 	=> $row['vehicle_details'],
					'DRIVER\'S NAME'	=> $row['driver_name'],
					$person_type 		=> $person,
					'CONTAINER NO' 		=> $row['container_no'],
					'FIRST WEIGHT' 		=> $row['first_weight']." kg",
					'FIRST WEIGHT DATE' => $row['first_weight_date'],
					'SECOND WEIGHT' 	=> $row['second_weight']." kg",
					'SECOND WEIGHT DATE'=> $row['second_weight_date'],
					'NET WEIGHT'		=> abs($row['second_weight']-$row['first_weight'])." kg"	
					);	
		//echo "<pre>";print_r($shipments);echo "</pre>";exit();		

	}else{
		$single_report = false;
		$shipments = get_shipment_details($fw_date,$sw_date,$ptype,$cid,$sid,$vehicle);	

	}

	
	
	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$orientation = ($orientation ? 'L' : 'P');
    $dec = user_price_dec();

	//$cols = array(0, 100, 260, 300, 350, 425, 430, 515);
	$cols = array(4, 60, 225, 300, 325, 385, 450, 515);
	
	$aligns = array('left',	'left',	'right', 'left', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	

    $rep = new FrontReport(_('Local Purchase'), "Local Purchase", user_pagesize(), 9, $orientation);

    $rep->SetHeaderType('ShipmentReportHeader');

    if ($orientation == 'L')
    	recalculate_cols($cols);
   // $rep->SetHeaderType('');
    $rep->Font();
    $rep->Info($params, $cols, null, $aligns);
    $rep->NewPage();
    $rep->Font();
    
    $ccol = $rep->cols[0] + 4;
	$cncol = $ccol + 110;

	$c2col = $ccol-350;
	$cn2col = $c2col + 110;

	$rep->NewLine(2);

    foreach($shipments as $shipment)
	{
		$i=0;

		foreach($shipment as $key=>$value){
			$i++;
			if($i%2 == 0){
				$rep->Font('bold');
				$rep->Text($c2col,$key);
				$rep->Font();
				$rep->Text($cn2col, ": ".$value);
				$rep->NewLine(2);
				
			}else{
				$rep->Font('bold');
				$rep->Text($ccol,$key);
				$rep->Font();
				$rep->Text($cncol, ": ".$value);
				
			}
			
		}

		if($single_report){

			$rep->NewLine(5);

			$rep->Font('bold');
			$rep->Text($ccol,"OPERATOR'S SIGN");
			$rep->Font();
			$rep->Text($cncol, ": ...................... ");

			$rep->Font('bold');
			$rep->Text($c2col,"DRIVER'S SIGN");
			$rep->Font();
			$rep->Text($cn2col, ": ...................... ");
			$rep->NewLine(2);		

		}else{
			$rep->NewLine();
			$rep->NewLine();
			//$rep->Line1($rep->row,0,$ccol);
			$rep->NewLine(2);
		}

	}
	
    $rep->End();
}

?>