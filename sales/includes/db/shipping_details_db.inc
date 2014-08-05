<?php
/**********************************************************************
   This file provide a way to integrate with shipping_details table
***********************************************************************/

function add_shipping_details($VehicleDetails, $ContainerNo, $ShipmentStatus, $FirstWeight, $FirstWeightDate, $PersonType, $PersonId)
{
	if ($FirstWeightDate == "")
		$SQLFirstDate = "0000-00-00";
	else
		$SQLFirstDate = date2sql($FirstWeightDate);

	$sql = "INSERT INTO ".TB_PREF."shipping_details (vehicle_details,container_no,shipment_status,first_weight,first_weight_date,person_type_id,person_id) VALUES ("
		.db_escape($VehicleDetails) . ",".db_escape($ContainerNo).",".db_escape($ShipmentStatus).",".db_escape($FirstWeight).",'$SQLFirstDate', " .db_escape($PersonType) . ",".db_escape($PersonId).")";

	//echo $sql;exit();

	db_query($sql,"The Shipping Details could not be added");
}

function update_shipping_details($ShippingId, $VehicleDetails, $ContainerNo, $ShipmentStatus, $FirstWeight, $FirstWeightDate,$SecondWeight,$SecondWeightDate, $PersonType, $PersonId)
{
	if ($FirstWeightDate == "")
		$SQLFirstDate = "0000-00-00";
	else
		$SQLFirstDate = date2sql($FirstWeightDate);

	if ($SecondWeightDate == "")
		$SQLSecondDate = "0000-00-00";
	else
		$SQLSecondDate = date2sql($SecondWeightDate);

	$sql = "UPDATE ".TB_PREF."shipping_details SET vehicle_details=".db_escape($VehicleDetails) .",
		container_no=".db_escape($ContainerNo) .",
		shipment_status=".db_escape($ShipmentStatus) .",
		person_type_id=".db_escape($PersonType) .",
		person_id=".db_escape($PersonId) .",
		first_weight=".db_escape($FirstWeight) .",
		first_weight_date='".$SQLFirstDate."'" ;
	if($ShipmentStatus == SHIPMENT_STATUSCLOSE){
		$sql .= ",second_weight=".db_escape($SecondWeight) .",
		second_weight_date='".$SQLSecondDate."'" ;
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

function get_shipping_detail($shipping_id,$person_type)
{
	$sql = "SELECT shipment.shipping_id,
			shipment.vehicle_details,
			shipment.container_no,
			shipment.first_weight,
			shipment.first_weight_date,
			shipment.second_weight,
			shipment.second_weight_date,
			shipment.shipment_status,
			shipment.person_type_id,
			shipment.person_id,
			shipstatus.status_description
			FROM ".TB_PREF."shipping_details as shipment, "
			.TB_PREF."shipment_status as shipstatus 
			WHERE shipping_id=".db_escape($shipping_id);

	$result = db_query($sql, "could not get shipping details");

	return db_fetch($result);
}

function get_sql_for_shipment_view($shipment_id,$vehicle_details,$shipment_status,$person_type_id,$person_id)
{
	$where = " AND shipment.person_type_id = ".db_escape($person_type_id);
	
	if($person_type_id == PT_CUSTOMER)	{
		$select = "person.name as name,";
		$from = " ,".TB_PREF."debtors_master person";
		$where .=" AND person.debtor_no = shipment.person_id";	
	}elseif($person_type_id == PT_SUPPLIER)	{
		$select = "person.supp_name as name,";
		$from = " ,".TB_PREF."suppliers person";
		$where .=" AND person.supplier_id = shipment.person_id";	
	}else{
		$select = "person_id as name,";
		$from = "";	
	}

	if($shipment_id){
		
		$where .= " AND shipment.shipping_id=".db_escape($shipment_id);
	}else{

		if($person_id)
			$where .= " AND shipment.person_id = ".db_escape($person_id);

		if($vehicle_details)
			$where .= " AND shipment.vehicle_details=".db_escape($vehicle_details);

		if ($shipment_status >0)
				$where .= " AND shipment.shipment_status=".db_escape($shipment_status);
	}
	

	$sql = "SELECT 
			shipment.shipping_id,".$select."
			shipment.vehicle_details,
			shipment.container_no,
			shipment.first_weight,
			shipment.first_weight_date,
			shipment.second_weight,
			shipment.second_weight_date,
			shipment.shipment_status,
			shipment.person_type_id,
			shipment.person_id,
			shipstatus.status_description
			FROM ".TB_PREF."shipping_details as shipment, "
			.TB_PREF."shipment_status as shipstatus".$from;


	$sql .= " WHERE shipment.shipment_status = shipstatus.status_id".$where;



	$sql .= " GROUP BY shipment.shipping_id";

	//echo $sql;exit();
	return $sql;
}

function delete_shipment($shippingId)
{
	$sql = "DELETE FROM ".TB_PREF."shipping_details WHERE shipping_id=" . db_escape($shippingId);
	db_query($sql, "Shipment delete");
}