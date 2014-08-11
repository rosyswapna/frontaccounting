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


	$sql = "SELECT shipment.vehicle_details,
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
			$sql .= " AND shipment.person_id =".db_escape($cid);
	}elseif($type == PT_SUPPLIER && $sid > 0){
			$sql .= " AND shipment.person_id =".db_escape($sid);
	}
	
	if($vehicle)
		$sql .= " AND shipment.vehicle_details LIKE ".db_escape($vehicle)."";

	

	$rs = db_query($sql,"No Shipment Entries Found");
	$shipments = array();
	while ($row = db_fetch($rs)) {
		$person = get_person_details($row['person_type_id'],$row['person_id']);
		$shipments[] = array(
					'person' => $person,
					'vehicle' => $row['vehicle_details'],
					'container_no' => $row['container_no'],
					'fweight' => $row['first_weight'],
					'sweight' => $row['second_weight'],
					'fdate' => $row['first_weight_date'],
					'sdate' => $row['second_weight_date'],
					);
	}
	
	//echo "<pre>";print_r($shipments);echo "</pre>";exit();

	return $shipments;
}

function get_person_details($type,$id){

	if($type==PT_CUSTOMER){
		$customer = get_customer($id);
		return $customer['name'];
	}elseif($type == PT_SUPPLIER){
		$supplier = get_supplier($id);
		return $supplier['supp_name'];
	}else{
		return $id;
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

		

		$person = get_person_details($row['person_type_id'],$row['person_id']);
		
		
		$shipments[] = array(
					'person' => $person,
					'vehicle' => $row['vehicle_details'],
					'container_no' => $row['container_no'],
					'driver_name' => $row['driver_name'],
					'fweight' => $row['first_weight'],
					'sweight' => $row['second_weight'],
					'fdate' => $row['first_weight_date'],
					'sdate' => $row['second_weight_date'],
					);
					

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
	$cncol = $ccol + 70;

	$c2col = $ccol-290;
	$cn2col = $c2col + 70;

   foreach($shipments as $shipment)
	{
		$rep->Font('bold');
		$rep->Text($ccol,'Name :');
		$rep->Font();
		$rep->Text($cncol, $shipment['person']);

		$rep->Font('bold');
		$rep->Text($c2col,'Vehicle No :');
		$rep->Font();
		$rep->Text($cn2col, $shipment['vehicle']);
		$rep->NewLine();

		$rep->Font('bold');
		$rep->Text($c2col,'Container No :');
		$rep->Font();
		$rep->Text($cn2col, $shipment['container_no']);

		$rep->NewLine(2);

		$rep->Font('bold');
		$rep->Text($ccol, 'First Weight :');
		$rep->Font();
		$rep->Text($cncol, $shipment['fweight']);

		$rep->Font('bold');
		$rep->Text($c2col, 'Second Weight :');
		$rep->Font();
		$rep->Text($cn2col, $shipment['sweight']);

		$rep->NewLine();

		$rep->Font('bold');
		$rep->Text($ccol,'Date :');
		$rep->Font();
		$rep->Text($cncol, $shipment['fdate']);

		$rep->Font('bold');
		$rep->Text($c2col,'Date :');
		$rep->Font();
		$rep->Text($cn2col, $shipment['sdate']);
		$rep->NewLine();
		
		

		$rep->Font('bold');
		$rep->Text($c2col, 'Net Weight :');
		$rep->Font();
		$rep->Text($cn2col, abs($shipment['sweight']-$shipment['fweight']));


		if($single_report){

			//$rep->row = $rep->bottomMargin+200;
			$rep->NewLine(5);

			$rep->Text($ccol, "For ".$shipment['driver_name'].",");
			$rep->NewLine(4);
			$sign_row = $rep->row;

			$rep->Line1($sign_row,0,$ccol,$ccol+150);
			$rep->Line1($sign_row,0,$cncol+200,$cncol+350);

			

			$rep->NewLine();
			$rep->Text($ccol, "Driver's Signature");
			$rep->Text($cncol+200, "Operator's Signature");
			

			

		}else{
			$rep->NewLine();
			$rep->Line1($rep->row,0,$ccol);
			$rep->NewLine(2);
		}

		
	}
	
	
	
    $rep->End();
}

?>