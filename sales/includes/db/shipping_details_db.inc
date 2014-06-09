<?php
/**********************************************************************
   This file provide a way to integrate with shipping_details table
***********************************************************************/

function add_shipping_details($DebtorNo, $VehicleDetails, $ContainerNo, $ShipmentStatus, $FirstWeight, $FirstWeightDate)
{
	if ($FirstWeightDate == "")
		$SQLFirstDate = "0000-00-00";
	else
		$SQLFirstDate = date2sql($FirstWeightDate);

	$sql = "INSERT INTO ".TB_PREF."shipping_details (debtor_no, vehicle_details,container_no,shipment_status,first_weight,first_weight_date) VALUES ("
		.db_escape($DebtorNo) .", " .db_escape($VehicleDetails) . ",".db_escape($ContainerNo).",".db_escape($ShipmentStatus).",".db_escape($FirstWeight).",'$SQLFirstDate')";

	//echo $sql;exit();

	db_query($sql,"The Shipping Details could not be added");
}

function update_shipping_details($ShippingId,$DebtorNo, $VehicleDetails, $ContainerNo, $ShipmentStatus, $FirstWeight, $FirstWeightDate,$SecondWeight,$SecondWeightDate)
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
		container_no=".db_escape($ContainerNo) .",
		shipment_status=".db_escape($ShipmentStatus) .",
		first_weight=".db_escape($FirstWeight) .",
		first_weight_date=".$SQLFirstDate ;
	if($ShipmentStatus == SHIPMENT_STATUSCLOSE){
		$sql .= ",second_weight=".db_escape($SecondWeight) .",
		second_weight_date=".$SQLSecondDate ;
	}

	$sql .= " WHERE shipping_id = ".db_escape($ShippingId);

	//echo $sql;exit();
		
	db_query($sql,"The Shipping Details could not be updated");
}

function close_shipment($ShippingId,$ShipmentStatus,$SecondWeight,$SecondWeightDate)
{
	if ($SecondWeightDate == "")
		$SQLSecondDate = "0000-00-00";
	else
		$SQLSecondDate = date2sql($SecondWeightDate);

	$sql = "UPDATE ".TB_PREF."shipping_details SET shipment_status=".db_escape($ShipmentStatus) .",
		second_weight=".db_escape($SecondWeight) .",
		second_weight_date='".$SQLSecondDate ."'
		WHERE shipping_id = ".db_escape($ShippingId);
		//echo $sql;exit();
	
	db_query($sql,"The Shipping Details could not closed");
}

function get_shipping_detail($shipping_id)
{
	$sql = "SELECT shipment.shipping_id,
			debtor.name as customer,
			shipment.vehicle_details,
			shipment.container_no,
			shipment.first_weight,
			shipment.first_weight_date,
			shipment.second_weight,
			shipment.second_weight_date,
			shipment.shipment_status,
			shipstatus.status_description
			FROM ".TB_PREF."shipping_details as shipment, "
			.TB_PREF."shipment_status as shipstatus,"
			.TB_PREF."debtors_master as debtor 
			WHERE shipping_id=".db_escape($shipping_id);

	$result = db_query($sql, "could not get shipping details");

	return db_fetch($result);
}

function get_sql_for_shipment_view($selected_customer,$shipment_id,$vehicle_details,$shipment_status,$customer_id=ALL_TEXT)
{
	$sql = "SELECT 
			shipment.shipping_id,
			debtor.name,
			shipment.vehicle_details,
			shipment.container_no,
			shipment.first_weight,
			shipment.first_weight_date,
			shipment.second_weight,
			shipment.second_weight_date,
			shipment.shipment_status,
			shipstatus.status_description
			FROM ".TB_PREF."shipping_details as shipment, "
			.TB_PREF."shipment_status as shipstatus,"
			.TB_PREF."debtors_master as debtor 
			WHERE shipment.debtor_no = debtor.debtor_no AND shipment.shipment_status = shipstatus.status_id";

	if($shipment_id){
		$sql .= " AND shipment.shipping_id=".db_escape($shipment_id);
	}else{

		if ($selected_customer != -1)
				$sql .= " AND shipment.debtor_no=".db_escape($selected_customer);

		if($vehicle_details)
			$sql .= " AND shipment.vehicle_details=".db_escape($vehicle_details);

		if ($shipment_status >0)
				$sql .= " AND shipment.shipment_status=".db_escape($shipment_status);

		if ($customer_id != ALL_TEXT)
			$sql .= " AND shipment.debtor_no = ".db_escape($customer_id);
	}



	$sql .= " GROUP BY shipment.shipping_id";
	return $sql;
}