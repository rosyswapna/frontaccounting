<?php

$page_security = 'SA_EODREP';
add_access_extensions();
// ----------------------------------------------------------------
// Creator:	SWAPNA
// date_:	2014-08-27
// Title:	END OF DAY REPORT(INVENTORY SALES AND PURCHASE REPORT,LIST OF JOURNAEL ENTR
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/includes/ui/ui_view.inc");

include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");

//----------------------------------------------------------------------------------------------------

print_eod();

//----------------------------------------------------------------------

function getPurchTransactions($from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description AS cat_description,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description, ".TB_PREF."stock_master.inactive,
			".TB_PREF."stock_moves.loc_code,
			".TB_PREF."suppliers.supplier_id,
			".TB_PREF."suppliers.supp_name AS supplier_name,
			".TB_PREF."stock_moves.tran_date,
			".TB_PREF."stock_moves.qty AS qty,
			".TB_PREF."stock_moves.price*(1-".TB_PREF."stock_moves.discount_percent) AS price
		FROM ".TB_PREF."stock_master,
			".TB_PREF."stock_category,
			".TB_PREF."suppliers,
			".TB_PREF."stock_moves
		WHERE ".TB_PREF."stock_master.stock_id=".TB_PREF."stock_moves.stock_id
		AND ".TB_PREF."stock_master.category_id=".TB_PREF."stock_category.category_id
		AND ".TB_PREF."stock_moves.person_id=".TB_PREF."suppliers.supplier_id
		AND ".TB_PREF."stock_moves.tran_date>='$from'
		AND ".TB_PREF."stock_moves.tran_date<='$to'
		AND (".TB_PREF."stock_moves.type=".ST_SUPPRECEIVE." OR ".TB_PREF."stock_moves.type=".ST_SUPPCREDIT.")
		AND (".TB_PREF."stock_master.mb_flag='B' OR ".TB_PREF."stock_master.mb_flag='M')";
		
		$sql .= " ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."suppliers.supp_name, ".TB_PREF."stock_master.stock_id, ".TB_PREF."stock_moves.tran_date";
    return db_query($sql,"No transactions were returned");

}
//-------------------------------------------------------------

function get_supp_inv_reference($supplier_id, $stock_id, $date)
{
	$sql = "SELECT ".TB_PREF."supp_trans.supp_reference
		FROM ".TB_PREF."supp_trans,
			".TB_PREF."supp_invoice_items,
			".TB_PREF."grn_batch,
			".TB_PREF."grn_items
		WHERE ".TB_PREF."supp_trans.type=".TB_PREF."supp_invoice_items.supp_trans_type
		AND ".TB_PREF."supp_trans.trans_no=".TB_PREF."supp_invoice_items.supp_trans_no
		AND ".TB_PREF."grn_items.grn_batch_id=".TB_PREF."grn_batch.id
		AND ".TB_PREF."grn_items.item_code=".TB_PREF."supp_invoice_items.stock_id
		AND ".TB_PREF."supp_trans.supplier_id=".db_escape($supplier_id)."
		AND ".TB_PREF."supp_invoice_items.stock_id=".db_escape($stock_id)."
		AND ".TB_PREF."supp_trans.tran_date=".db_escape($date);
    $result = db_query($sql,"No transactions were returned");
    $row = db_fetch_row($result);
    if (isset($row[0]))
    	return $row[0];
    else
    	return '';
} 
//----------------------------------------------------------------------

function getSalesTransactions($from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_category.description AS cat_description,
			".TB_PREF."stock_master.stock_id,
			".TB_PREF."stock_master.description, ".TB_PREF."stock_master.inactive,
			".TB_PREF."stock_moves.loc_code,
			".TB_PREF."debtor_trans.debtor_no,
			".TB_PREF."debtors_master.name AS debtor_name,
			".TB_PREF."stock_moves.tran_date,
			SUM(-".TB_PREF."stock_moves.qty) AS qty,
			SUM(-".TB_PREF."stock_moves.qty*".TB_PREF."stock_moves.price*(1-".TB_PREF."stock_moves.discount_percent)) AS amt,
			SUM(-IF(".TB_PREF."stock_moves.standard_cost <> 0, ".TB_PREF."stock_moves.qty * ".TB_PREF."stock_moves.standard_cost, ".TB_PREF."stock_moves.qty *(".TB_PREF."stock_master.material_cost + ".TB_PREF."stock_master.labour_cost + ".TB_PREF."stock_master.overhead_cost))) AS cost
		FROM ".TB_PREF."stock_master,
			".TB_PREF."stock_category,
			".TB_PREF."debtor_trans,
			".TB_PREF."debtors_master,
			".TB_PREF."stock_moves
		WHERE ".TB_PREF."stock_master.stock_id=".TB_PREF."stock_moves.stock_id
		AND ".TB_PREF."stock_master.category_id=".TB_PREF."stock_category.category_id
		AND ".TB_PREF."debtor_trans.debtor_no=".TB_PREF."debtors_master.debtor_no
		AND ".TB_PREF."stock_moves.type=".TB_PREF."debtor_trans.type
		AND ".TB_PREF."stock_moves.trans_no=".TB_PREF."debtor_trans.trans_no
		AND ".TB_PREF."stock_moves.tran_date>='$from'
		AND ".TB_PREF."stock_moves.tran_date<='$to'
		AND (".TB_PREF."debtor_trans.type=".ST_CUSTDELIVERY." OR ".TB_PREF."stock_moves.type=".ST_CUSTCREDIT.")
		AND (".TB_PREF."stock_master.mb_flag='B' OR ".TB_PREF."stock_master.mb_flag='M')";
		$sql .= " GROUP BY ".TB_PREF."stock_master.stock_id, ".TB_PREF."debtors_master.name ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_master.stock_id, ".TB_PREF."debtors_master.name";
    return db_query($sql,"No transactions were returned");

}

//----------------------------------------------------------------------------------------------------

function print_eod()
{
	global $path_to_root, $systypes_array;

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$email = $_POST['PARAM_2'];
	$orientation = $_POST['PARAM_3'];
	$destination = $_POST['PARAM_4'];

	if ($destination)
		include_once($path_to_root . "/reporting/includes/excel_report.inc");
	else
		include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$cols = array(0, 100, 240, 300, 400, 460, 520, 580);

	$headers = array(_('Type/Account'), _('Reference').'/'._('Account Name'), _('Date/Dim.'),
	_('Person/Item/Memo'), _('Debit'), _('Credit'));

	$aligns = array('left', 'left', 'left', 'left', 'right', 'right');

	$params =   array( 	0 => array('from' => $from,'to' => $to));
	
	$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);

	
	$rep->title = _('EOD Report');	
	$rep->InfoSearch = _("List of Journal Entries between $from - $to");
	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->contactData = array(0=>array('email'=>$rep->company['email']));
	
	$rep->NewPage();

	$trans = get_gl_transactions($from, $to, -1, null, 0, 0);

	$typeno = $type = 0;
	$debit = $credit = 0.0;
	$totdeb = $totcre = 0.0;
	while ($myrow=db_fetch($trans))
	{
		if ($type != $myrow['type'] || $typeno != $myrow['type_no'])
		{
			if ($typeno != 0)
			{
				$rep->Line($rep->row += 6);
				$rep->NewLine();
				$rep->AmountCol(4, 5, $debit, $dec);
				$rep->AmountCol(5, 6, abs($credit), $dec);
				$totdeb += $debit;
				$totcre += $credit;
				$debit = $credit = 0.0;
				$rep->Line($rep->row -= 4);
				$rep->NewLine();
			}
			$typeno = $myrow['type_no'];
			$type = $myrow['type'];
			$TransName = $systypes_array[$myrow['type']];
			$rep->TextCol(0, 1, $TransName . " # " . $myrow['type_no']);
			$rep->TextCol(1, 2, get_reference($myrow['type'], $myrow['type_no']));
			$rep->DateCol(2, 3, $myrow['tran_date'], true);
			$coms =  payment_person_name($myrow["person_type_id"],$myrow["person_id"]);
			$memo = get_comments_string($myrow['type'], $myrow['type_no']);
			if ($memo != '')
			{
				if ($coms == "")
					$coms = $memo;
				else
					$coms .= " / ".$memo;
			}		
			$rep->TextColLines(3, 6, $coms);
			$rep->NewLine();
		}
		$rep->TextCol(0, 1, $myrow['account']);
		$rep->TextCol(1, 2, $myrow['account_name']);
		$dim_str = get_dimension_string($myrow['dimension_id']);
		$dim_str2 = get_dimension_string($myrow['dimension2_id']);
		if ($dim_str2 != "")
			$dim_str .= "/".$dim_str2;
		$rep->TextCol(2, 3, $dim_str);
		$rep->TextCol(3, 4, $myrow['memo_']);
		if ($myrow['amount'] > 0.0) {
			$debit += $myrow['amount'];
			$rep->AmountCol(4, 5, abs($myrow['amount']), $dec);
		}    
		else {
			$credit += $myrow['amount'];
			$rep->AmountCol(5, 6, abs($myrow['amount']), $dec);
		}    
		$rep->NewLine(1, 2);
	}
	if ($typeno != 0)
	{
		$rep->Line($rep->row += 6);
		$rep->NewLine();
		$rep->AmountCol(4, 5, $debit, $dec);
		$rep->AmountCol(5, 6, abs($credit), $dec);
		$totdeb += $debit;
		$totcre += $credit;
		$rep->Line($rep->row -= 4);
		$rep->NewLine();
		$rep->TextCol(0, 4, _("Total"));
		$rep->AmountCol(4, 5, $totdeb, $dec);
		$rep->AmountCol(5, 6, abs($totcre), $dec);
		$rep->Line($rep->row -= 4);
	}
	
	//purchase report
	$rep->title = _('EOD Report');
	$rep->InfoSearch = _("Inventory Purchasing Report between $from - $to");
	

	$cols = array(0, 60, 180, 225, 275, 400, 420, 465,	520);

	$headers = array(_('Category'), _('Description'), _('Date'), _('#'), _('Supplier'), _('Qty'), _('Unit Price'), _('Total'));
	if ($fromsupp != '')
		$headers[4] = '';

	$aligns = array('left',	'left',	'left', 'left', 'left', 'left', 'right', 'right');

	$params =   array( 	0 => array('from' => $from,'to' => $to));

	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$res = getPurchTransactions($from, $to);

	$total = $total_supp = $grandtotal = 0.0;
	$total_qty = 0.0;
	$catt = $stock_description = $stock_id = $supplier_name = '';
	while ($trans=db_fetch($res))
	{
		if ($stock_description != $trans['description'])
		{
			if ($stock_description != '')
			{
				if ($supplier_name != '')
				{
					$rep->NewLine(2, 3);
					$rep->TextCol(0, 1, _('Total'));
					$rep->TextCol(1, 4, $stock_description);
					$rep->TextCol(4, 5, $supplier_name);
					$rep->AmountCol(5, 7, $total_qty, get_qty_dec($stock_id));
					$rep->AmountCol(7, 8, $total_supp, $dec);
					$rep->Line($rep->row - 2);
					$rep->NewLine();
					$total_supp = $total_qty = 0.0;
					$supplier_name = $trans['supplier_name'];
				}	
			}
			$stock_id = $trans['stock_id'];
			$stock_description = $trans['description'];
		}

		if ($supplier_name != $trans['supplier_name'])
		{
			if ($supplier_name != '')
			{
				$rep->NewLine(2, 3);
				$rep->TextCol(0, 1, _('Total'));
				$rep->TextCol(1, 4, $stock_description);
				$rep->TextCol(4, 5, $supplier_name);
				$rep->AmountCol(5, 7, $total_qty, get_qty_dec($stock_id));
				$rep->AmountCol(7, 8, $total_supp, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine();
				$total_supp = $total_qty = 0.0;
			}
			$supplier_name = $trans['supplier_name'];
		}
		if ($catt != $trans['cat_description'])
		{
			if ($catt != '')
			{
				$rep->NewLine(2, 3);
				$rep->TextCol(0, 1, _('Total'));
				$rep->TextCol(1, 7, $catt);
				$rep->AmountCol(7, 8, $total, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine();
				$rep->NewLine();
				$total = 0.0;
			}
			$rep->TextCol(0, 1, $trans['category_id']);
			$rep->TextCol(1, 6, $trans['cat_description']);
			$catt = $trans['cat_description'];
			$rep->NewLine();
		}

		$curr = get_supplier_currency($trans['supplier_id']);
		$rate = get_exchange_rate_from_home_currency($curr, sql2date($trans['tran_date']));
		$trans['price'] *= $rate;
		$rep->NewLine();
		$trans['supp_reference'] = get_supp_inv_reference($trans['supplier_id'], $trans['stock_id'], $trans['tran_date']);
		$rep->fontSize -= 2;
		$rep->TextCol(0, 1, $trans['stock_id']);
		if ($fromsupp == ALL_TEXT)
		{
			$rep->TextCol(1, 2, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);
			$rep->TextCol(2, 3, sql2date($trans['tran_date']));
			$rep->TextCol(3, 4, $trans['supp_reference']);
			$rep->TextCol(4, 5, $trans['supplier_name']);
		}
		else
		{
			$rep->TextCol(1, 2, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);
			$rep->TextCol(2, 3, sql2date($trans['tran_date']));
			$rep->TextCol(3, 4, $trans['supp_reference']);
		}	
		$rep->AmountCol(5, 6, $trans['qty'], get_qty_dec($trans['stock_id']));
		$rep->AmountCol(6, 7, $trans['price'], $dec);
		$amt = $trans['qty'] * $trans['price'];
		$rep->AmountCol(7, 8, $amt, $dec);
		$rep->fontSize += 2;
		$total += $amt;
		$total_supp += $amt;
		$grandtotal += $amt;
		$total_qty += $trans['qty'];
	}
	if ($stock_description != '')
	{
		if ($supplier_name != '')
		{
			$rep->NewLine(2, 3);
			$rep->TextCol(0, 1, _('Total'));
			$rep->TextCol(1, 4, $stock_description);
			$rep->TextCol(4, 5, $supplier_name);
			$rep->AmountCol(5, 7, $total_qty, get_qty_dec($stock_id));
			$rep->AmountCol(7, 8, $total_supp, $dec);
			$rep->Line($rep->row - 2);
			$rep->NewLine();
			$rep->NewLine();
			$total_supp = $total_qty = 0.0;
			$supplier_name = $trans['supplier_name'];
		}	
	}
	if ($supplier_name != '')
	{
		$rep->NewLine(2, 3);
		$rep->TextCol(0, 1, _('Total'));
		$rep->TextCol(1, 4, $stock_description);
		$rep->TextCol(4, 5, $supplier_name);
		$rep->AmountCol(5, 7, $total_qty, get_qty_dec($stock_id));
		$rep->AmountCol(7, 8, $total_supp, $dec);
		$rep->Line($rep->row - 2);
		$rep->NewLine();
		$rep->NewLine();
	}

	$rep->NewLine(2, 3);
	$rep->TextCol(0, 1, _('Total'));
	$rep->TextCol(1, 7, $catt);
	$rep->AmountCol(7, 8, $total, $dec);
	$rep->Line($rep->row - 2);
	$rep->NewLine();
	$rep->NewLine(2, 1);
	$rep->TextCol(0, 7, _('Grand Total'));
	$rep->AmountCol(7, 8, $grandtotal, $dec);

	$rep->Line($rep->row  - 4);
	$rep->NewLine();

	//purchase report ends here
	

	//sales report
	$rep->title = _('EOD Report');
	$rep->InfoSearch = _("Inventory Sales Report between $from - $to");

	$cols = array(0, 90, 210, 250, 300, 375, 450,	515);
	$headers = array(_('Description'), _('Customer'), _('Qty'), _('Trans Date'), _('Sales'), _('Cost'), _('Contribution'));

	if ($fromcust != '')
		$headers[2] = '';

	$aligns = array('left',	'left',	'left', 'right', 'right', 'right', 'right');

	$params =   array(0 => array('from' => $from,'to' => $to));

	if ($orientation == 'L')
		recalculate_cols($cols);

	

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns,0, $header2);
	$rep->NewPage();


	$res = getSalesTransactions($from, $to);
	$total = $grandtotal = 0.0;
	$total1 = $grandtotal1 = 0.0;
	$total2 = $grandtotal2 = 0.0;
	$catt = '';
	while ($trans=db_fetch($res))
	{
		if ($catt != $trans['cat_description'])
		{
			if ($catt != '')
			{
				$rep->NewLine(2, 3);
				$rep->TextCol(0, 4, _('Total'));
				$rep->AmountCol(4, 5, $total, $dec);
				$rep->AmountCol(5, 6, $total1, $dec);
				$rep->AmountCol(6, 7, $total2, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine();
				$rep->NewLine();
				$total = $total1 = $total2 = 0.0;
			}
			//$rep->TextCol(0, 1, $trans['category_id']);
			//$rep->TextCol(1, 6, $trans['cat_description']);

			$rep->TextCol(0, 1, $trans['cat_description']);

			$catt = $trans['cat_description'];
			$rep->NewLine();
		}

		$curr = get_customer_currency($trans['debtor_no']);
		$rate = get_exchange_rate_from_home_currency($curr, sql2date($trans['tran_date']));
		$trans['amt'] *= $rate;
		$cb = $trans['amt'] - $trans['cost'];
		$rep->NewLine();
		$rep->fontSize -= 2;
		//$rep->TextCol(0, 1, $trans['stock_id']);
		if ($fromcust == ALL_TEXT)
		{
			$rep->TextCol(0, 1, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);
			$rep->TextCol(1, 2, $trans['debtor_name']);
		}
		else
			$rep->TextCol(0, 2, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);
		$rep->AmountCol(2, 3, $trans['qty'], get_qty_dec($trans['stock_id']));
		$rep->DateCol(3, 4, $trans['tran_date'], $dec);
		$rep->AmountCol(4, 5, $trans['amt'], $dec);
		$rep->AmountCol(5, 6, $trans['cost'], $dec);
		$rep->AmountCol(6, 7, $cb, $dec);
		$rep->fontSize += 2;
		$total += $trans['amt'];
		$total1 += $trans['cost'];
		$total2 += $cb;
		$grandtotal += $trans['amt'];
		$grandtotal1 += $trans['cost'];
		$grandtotal2 += $cb;
	}
	$rep->NewLine(2, 3);
	$rep->TextCol(0, 4, _('Total'));
	$rep->AmountCol(4, 5, $total, $dec);
	$rep->AmountCol(5, 6, $total1, $dec);
	$rep->AmountCol(6, 7, $total2, $dec);
	$rep->Line($rep->row - 2);
	$rep->NewLine();
	$rep->NewLine(2, 1);
	$rep->TextCol(0, 4, _('Grand Total'));
	$rep->AmountCol(4, 5, $grandtotal, $dec);
	$rep->AmountCol(5, 6, $grandtotal1, $dec);
	$rep->AmountCol(6, 7, $grandtotal2, $dec);

	$rep->Line($rep->row  - 4);
	$rep->NewLine();
	//sales report ends here
	
	if ($email == 1)
		$rep->End($email,'EOD Report');
	else
		$rep->End();
}

//-----------------------------------------------------------




?>
