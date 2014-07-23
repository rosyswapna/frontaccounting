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
include_once($path_to_root."/admin/db/attachments_db.inc");

//----------------------------------------------------------------------------------------------------
print_shipment();
//----------------------------------------------------------------------------------------------------

function get_shipment_details($fw_date,$sw_date,$customer,$vehicle_no){
	$sql = "SELECT debtor.name as customer,
			shipment.vehicle_details as vehicle,
			shipment.container_no,
			shipment.first_weight as fweight,
			shipment.first_weight_date as fdate,
			shipment.second_weight as sweight,
			shipment.second_weight_date sdate
			FROM ".TB_PREF."shipping_details as shipment, "
			.TB_PREF."debtors_master as debtor 
			WHERE shipment.debtor_no = debtor.debtor_no";
	$sql .= " AND shipment.first_weight_date = '".date2sql($fw_date)."'";

	$sql .= " AND shipment.second_weight_date = '".date2sql($sw_date)."'";
	
	$sql .= " AND shipment.debtor_no =".db_escape($customer);
	
	$sql .= " AND shipment.vehicle_details LIKE ".db_escape($vehicle_no)."";

	return db_query($sql,"No Shipment Entries Found");
}

function print_shipment()
{
    global $path_to_root;

	$fw_date = $_POST['PARAM_0']; //first weight date
	$sw_date = $_POST['PARAM_1']; //second weight date
    $customer = $_POST['PARAM_2']; //customer id
    $vehicle_no = $_POST['PARAM_3']; //vehicle details
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];

	$res = get_shipment_details($fw_date,$sw_date,$customer,$vehicle_no);
	
	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$orientation = ($orientation ? 'L' : 'P');
    $dec = user_price_dec();

	//$cols = array(0, 100, 260, 300, 350, 425, 430, 515);
	$cols = array(4, 60, 225, 300, 325, 385, 450, 515);
	
	$aligns = array('left',	'left',	'right', 'left', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$th = array(_('Customer'), _('Vehicle'), _('First Weight'), _('First Weight Date'), _('Second Weight'), '', _('Second Weight Date'));

    $rep = new FrontReport(_('Shipment Report'), "ShipmentReport", user_pagesize(), 9, $orientation);
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

    while ($shipment=db_fetch($res))
	{
		$rep->Font('bold');
		$rep->Text($ccol,'Customer :');
		$rep->Font();
		$rep->Text($cncol, $shipment['customer']);

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
		//$rep->Line($rep->row - 2);
		$rep->Line1($rep->row,0,$ccol);
		$rep->NewLine(2);
	}
	
	
	
    $rep->End();
}

?>