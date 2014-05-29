<?php
/**********************************************************************
   This file provide a way to integrate with shipping_details table
***********************************************************************/
function add_shipping_details($DebtorNo, $VehicleDetails, $ShipmentStatus, $FirstWeight, $FirstWeightDate, $SecondWeight, $SecondWeightDate)
{
	if ($FirstWeightDate == "")
		$SQLFirstDate = "0000-00-00";
	else
		$SQLFirstDate = date2sql($FirstWeightDate);

	if ($SecondWeightDate == "")
		$SQLSecondDate = "0000-00-00";
	else
		$SQLSecondDate = date2sql($SecondWeightDate);

	$sql = "INSERT INTO ".TB_PREF."shipping_details (debtor_no, vehicle_details,shipment_status,first_weight,first_weight_date,second_weight,second_weight_date) VALUES ("
		.db_escape($DebtorNo) .", " .db_escape($VehicleDetails) . ",".db_escape($ShipmentStatus).",".db_escape($FirstWeight).",'$SQLFirstDate',".db_escape($SecondWeight).",'$SQLSecondDate')";

	//echo $sql;exit();

	db_query($sql,"The Shipping Details could not be added");
}

function update_shipping_details($ShippingId,$DebtorNo, $VehicleDetails, $ShipmentStatus, $FirstWeight, $FirstWeightDate, $SecondWeight, $SecondWeightDate)
{
	if ($FirstWeightDate == "")
		$SQLFirstDate = "0000-00-00";
	else
		$SQLFirstDate = date2sql($FirstWeightDate);

	if ($SecondWeightDate == "")
		$SQLSecondDate = "0000-00-00";
	else
		$SQLSecondDate = date2sql($SecondWeightDate);

	$sql = "UPDATE ".TB_PREF."shipping_details SET debtor_no=" . db_escape($DebtorNo) . ", 
		vehicle_details=".db_escape($VehicleDetails) .",
		shipment_status=".db_escape($ShipmentStatus) .",
		first_weight=".db_escape($FirstWeight) .",
		first_weight_date=".$SQLFirstDate .",
		second_weight=".db_escape($SecondWeight) .",
		second_weight_date=".$SQLSecondDate ."
		WHERE shipping_id = ".db_escape($ShippingId);
	

	db_query($sql,"The Shipping Details could not be updated");
}

function get_shipping_detail($shipping_id)
{
	$sql = "SELECT * FROM ".TB_PREF."shipping_details WHERE shipping_id=".db_escape($shipping_id);

	$result = db_query($sql, "could not get shipping details");

	return db_fetch($result);
}