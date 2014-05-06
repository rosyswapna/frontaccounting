<?php
/**********************************************************************
   This file provide a way to integrate with shipping_details table
***********************************************************************/
function add_shipping_details($DebtorNo, $VehicleNo)
{
	$sql = "INSERT INTO ".TB_PREF."shipping_details (debtor_no, vehicle_no) VALUES ("
		.db_escape($DebtorNo) .", " .db_escape($VehicleNo) . ")";

	db_query($sql,"The Shipping Details could not be added");
}

function update_shipping_details($ShippingId, $DebtorNo, $VehicleNo)
{
	$sql = "UPDATE ".TB_PREF."shipping_details SET debtor_no=" . db_escape($DebtorNo) . ", 
		vehicle_no=".db_escape($VehicleNo) ."
		WHERE shipping_id = ".db_escape($ShippingId);
	

	db_query($sql,"The Shipping Details could not be updated");
}

function get_shipping_detail($shipping_id)
{
	$sql = "SELECT * FROM ".TB_PREF."shipping_details WHERE shipping_id=".db_escape($shipping_id);

	$result = db_query($sql, "could not get shipping details");

	return db_fetch($result);
}