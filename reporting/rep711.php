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
include_once($path_to_root . "/sales/includes/db/customers_db.inc");
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

		//EXCLUDE MIX MATERIAL FROM ITEM DETAILS
		$sql .= " AND stock_moves.stock_adjust = 0";

		$sql .= " GROUP BY ".TB_PREF."stock_master.stock_id, ".TB_PREF."debtors_master.name ORDER BY ".TB_PREF."stock_master.category_id,
			".TB_PREF."stock_master.stock_id, ".TB_PREF."debtors_master.name";
    return db_query($sql,"No transactions were returned");

}

//customer balances functions start

function get_cust_open_balance($debtorno, $to)
{
	if($to)
		$to = date2sql($to);

    $sql = "SELECT SUM(IF(t.type = ".ST_SALESINVOICE." OR t.type = ".ST_BANKPAYMENT.",
    	(t.ov_amount + t.ov_gst + t.ov_freight + t.ov_freight_tax + t.ov_discount), 0)) AS charges,
    	SUM(IF(t.type <> ".ST_SALESINVOICE." AND t.type <> ".ST_BANKPAYMENT.",
	    	(t.ov_amount + t.ov_gst + t.ov_freight + t.ov_freight_tax + t.ov_discount) * -1, 0)) AS credits,
		SUM(t.alloc) AS Allocated,
		SUM(IF(t.type = ".ST_SALESINVOICE." OR t.type = ".ST_BANKPAYMENT.",
			(t.ov_amount + t.ov_gst + t.ov_freight + t.ov_freight_tax + t.ov_discount - t.alloc),
	    	((t.ov_amount + t.ov_gst + t.ov_freight + t.ov_freight_tax + t.ov_discount) * -1 + t.alloc))) AS OutStanding
		FROM ".TB_PREF."debtor_trans t
    	WHERE t.debtor_no = ".db_escape($debtorno)
		." AND t.type <> ".ST_CUSTDELIVERY;
    if ($to)
    	$sql .= " AND t.tran_date < '$to'";
	$sql .= " GROUP BY debtor_no";
	$sql .= " ORDER BY t.tran_date ASC";

    $result = db_query($sql,"No transactions were returned");
    return db_fetch($result);
}

function get_cust_transactions($debtorno, $from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);

    $sql = "SELECT ".TB_PREF."debtor_trans.*,
		(".TB_PREF."debtor_trans.ov_amount + ".TB_PREF."debtor_trans.ov_gst + ".TB_PREF."debtor_trans.ov_freight + 
		".TB_PREF."debtor_trans.ov_freight_tax + ".TB_PREF."debtor_trans.ov_discount)
		AS TotalAmount, ".TB_PREF."debtor_trans.alloc AS Allocated,
		((".TB_PREF."debtor_trans.type = ".ST_SALESINVOICE.")
		AND ".TB_PREF."debtor_trans.due_date < '$to') AS OverDue
    	FROM ".TB_PREF."debtor_trans
    	WHERE ".TB_PREF."debtor_trans.tran_date >= '$from'
		AND ".TB_PREF."debtor_trans.tran_date <= '$to'
		AND ".TB_PREF."debtor_trans.debtor_no = ".db_escape($debtorno)."
		AND ".TB_PREF."debtor_trans.type <> ".ST_CUSTDELIVERY."
    	ORDER BY ".TB_PREF."debtor_trans.tran_date ASC";

    return db_query($sql,"No transactions were returned");
}

//customer balances functions ends


//supplier balances functions start
function get_sup_open_balance($supplier_id, $to)
{
	$to = date2sql($to);

    $sql = "SELECT SUM(IF(".TB_PREF."supp_trans.type = ".ST_SUPPINVOICE." OR ".TB_PREF."supp_trans.type = ".ST_BANKDEPOSIT.", 
    	(".TB_PREF."supp_trans.ov_amount + ".TB_PREF."supp_trans.ov_gst + ".TB_PREF."supp_trans.ov_discount), 0)) AS charges,
    	SUM(IF(".TB_PREF."supp_trans.type <> ".ST_SUPPINVOICE." AND ".TB_PREF."supp_trans.type <> ".ST_BANKDEPOSIT.", 
    	(".TB_PREF."supp_trans.ov_amount + ".TB_PREF."supp_trans.ov_gst + ".TB_PREF."supp_trans.ov_discount), 0)) AS credits,
		SUM(".TB_PREF."supp_trans.alloc) AS Allocated,
		SUM(IF(".TB_PREF."supp_trans.type = ".ST_SUPPINVOICE." OR ".TB_PREF."supp_trans.type = ".ST_BANKDEPOSIT.",
		(".TB_PREF."supp_trans.ov_amount + ".TB_PREF."supp_trans.ov_gst + ".TB_PREF."supp_trans.ov_discount - ".TB_PREF."supp_trans.alloc),
		(".TB_PREF."supp_trans.ov_amount + ".TB_PREF."supp_trans.ov_gst + ".TB_PREF."supp_trans.ov_discount + ".TB_PREF."supp_trans.alloc))) AS OutStanding
		FROM ".TB_PREF."supp_trans
    	WHERE ".TB_PREF."supp_trans.tran_date < '$to'
		AND ".TB_PREF."supp_trans.supplier_id = '$supplier_id' GROUP BY supplier_id ORDER BY ".TB_PREF."supp_trans.tran_date";

    $result = db_query($sql,"No transactions were returned");
    return db_fetch($result);
}

function getsupTransactions($supplier_id, $from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);

    $sql = "SELECT ".TB_PREF."supp_trans.*,
				(".TB_PREF."supp_trans.ov_amount + ".TB_PREF."supp_trans.ov_gst + ".TB_PREF."supp_trans.ov_discount)
				AS TotalAmount, ".TB_PREF."supp_trans.alloc AS Allocated,
				((".TB_PREF."supp_trans.type = ".ST_SUPPINVOICE.")
					AND ".TB_PREF."supp_trans.due_date < '$to') AS OverDue
    			FROM ".TB_PREF."supp_trans
    			WHERE ".TB_PREF."supp_trans.tran_date >= '$from' AND ".TB_PREF."supp_trans.tran_date <= '$to' 
    			AND ".TB_PREF."supp_trans.supplier_id = '$supplier_id' AND ".TB_PREF."supp_trans.ov_amount!=0
    				ORDER BY ".TB_PREF."supp_trans.tran_date";

    $TransResult = db_query($sql,"No transactions were returned");

    return $TransResult;
}
//supplier balances functions ends

//bank statement start
function get_bank_balance_to($to)
{
	$to = date2sql($to);
	$sql = "SELECT SUM(amount) as prev_balance,act.* FROM ".TB_PREF."bank_trans trans,".TB_PREF."bank_accounts act WHERE trans.trans_date < '$to' AND act.id = trans.bank_act GROUP BY trans.bank_act";

	return db_query($sql, "The starting balance on hand could not be calculated");
}

function get_bank_transactions($from, $to, $account)
{
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT ".TB_PREF."bank_trans.* FROM ".TB_PREF."bank_trans
		WHERE ".TB_PREF."bank_trans.bank_act = '$account'
		AND trans_date >= '$from'
		AND trans_date <= '$to'
		ORDER BY trans_date ASC,".TB_PREF."bank_trans.id";

	return db_query($sql,"The transactions for '$account' could not be retrieved");
}
//bank statement ends


//functions for costed inventory movement start
function fetch_items($category=0)
{
		$sql = "SELECT stock_id, stock.description AS name,
				stock.category_id,
				units,material_cost,
				cat.description
			FROM ".TB_PREF."stock_master stock LEFT JOIN ".TB_PREF."stock_category cat ON stock.category_id=cat.category_id
				WHERE mb_flag <> 'D'";
		if ($category != 0)
			$sql .= " AND cat.category_id = ".db_escape($category);
		$sql .= " ORDER BY stock.category_id, stock_id";

    return db_query($sql,"No transactions were returned");
}

function trans_qty($stock_id, $location=null, $from_date, $to_date, $inward = true)
{
	if ($from_date == null)
		$from_date = Today();

	$from_date = date2sql($from_date);	

	if ($to_date == null)
		$to_date = Today();

	$to_date = date2sql($to_date);

	$sql = "SELECT ".($inward ? '' : '-')."SUM(qty) FROM ".TB_PREF."stock_moves
		WHERE stock_id=".db_escape($stock_id)."
		AND tran_date >= '$from_date' 
		AND tran_date <= '$to_date'";

	if ($location != '')
		$sql .= " AND loc_code = ".db_escape($location);

	if ($inward)
		$sql .= " AND qty > 0 ";
	else
		$sql .= " AND qty < 0 ";

	$result = db_query($sql, "QOH calculation failed");

	$myrow = db_fetch_row($result);	

	return $myrow[0];

}

//----------------------------------------------------------------------------------------------------

function trans_qty_unit_cost($stock_id, $location=null, $from_date, $to_date, $inward = true)
{
	if ($from_date == null)
		$from_date = Today();

	$from_date = date2sql($from_date);	

	if ($to_date == null)
		$to_date = Today();

	$to_date = date2sql($to_date);

	$sql = "SELECT AVG (price)   FROM ".TB_PREF."stock_moves
		WHERE stock_id=".db_escape($stock_id)."
		AND tran_date >= '$from_date' 
		AND tran_date <= '$to_date'";

	if ($location != '')
		$sql .= " AND loc_code = ".db_escape($location);

	if ($inward)
		$sql .= " AND qty > 0 ";
	else
		$sql .= " AND qty < 0 ";

	$result = db_query($sql, "QOH calculation failed");

	$myrow = db_fetch_row($result);	

	return $myrow[0];

}
//function for costed inventory movement report ends

//----------------------------------------------------------------------------------------------------

function print_eod()
{
	global $path_to_root, $systypes_array;

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$email = $_POST['PARAM_2'];
	$orientation = $_POST['PARAM_3'];
	$destination = $_POST['PARAM_4'];
	$no_zeros = $_POST['PARAM_5'];


	if ($no_zeros) $nozeros = _('Yes');
	else $nozeros = _('No');
	
	if(strtotime($from) == strtotime($to))
		$title = 'EOD Report ('.$from.')';
	else
		$title = 'EOD Report ('.$from .'-'. $to.')';

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

	$params =   array(0 => array('from' => $from,'to' => $to),
			1 => array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => ''));
	
	$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
/*

	//$rep->$Title = "EOD Report";
	$rep->title = _($title);	
	$rep->InfoSearch = _("List of Journal Entries");
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
*/
	
	//purchase report
	$rep->title = _($title);	
	$rep->InfoSearch = _("Inventory Purchasing Report");
	

	$cols = array(0, 60, 180, 225, 275, 400, 420, 465,	520);

	$headers = array(_('Category'), _('Description'), _('Date'), _('#'), _('Supplier'), _('Qty'), _('Unit Price'), _('Total'));
	if ($fromsupp != '')
		$headers[4] = '';

	$aligns = array('left',	'left',	'left', 'left', 'left', 'left', 'right', 'right');

	$params =   array( 0 => array('from' => $from,'to' => $to),
			1=>array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => '')
			);

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
		
		if ($catt != $trans['cat_description'])
		{
			if ($catt != '')
			{
				$rep->NewLine(2, 3);
				$rep->TextCol(0, 4, _('Total'));
				$rep->TextCol(1, 7, $catt);
				$rep->AmountCol(7, 8, $total, $dec);
				$rep->Line($rep->row - 2);
				$rep->NewLine();
				$rep->NewLine();
				$total = $total1 = $total2 = 0.0;
			}
			$rep->TextCol(0, 5, $trans['cat_description']);
			$catt = $trans['cat_description'];
			$rep->NewLine();
		}
		
		$curr = get_supplier_currency($trans['supplier_id']);
		$rate = get_exchange_rate_from_home_currency($curr, sql2date($trans['tran_date']));
		$trans['price'] *= $rate;
		$rep->NewLine();
		$trans['supp_reference'] = get_supp_inv_reference($trans['supplier_id'], $trans['stock_id'], $trans['tran_date']);
		$rep->fontSize -= 2;
		
		if ($fromsupp == ALL_TEXT)
		{
			$rep->TextCol(0, 1, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);		
			$rep->TextCol(1, 2, $trans['supplier_name']);
			$rep->TextCol(2, 3, sql2date($trans['tran_date']));
			$rep->TextCol(3, 4, $trans['supp_reference']);
			
		}
		else
		{
			$rep->TextCol(1, 2, $trans['description'].($trans['inactive']==1 ? " ("._("Inactive").")" : ""), -1);
			$rep->TextCol(2, 3, sql2date($trans['tran_date']));
			$rep->TextCol(3, 4, $trans['supp_reference']);
		}	
		$rep->AmountCol(4, 5, $trans['qty'], get_qty_dec($trans['stock_id']));
		$rep->AmountCol(5, 6, $trans['price'], $dec);
		$amt = $trans['qty'] * $trans['price'];
		$rep->AmountCol(6, 7, $amt, $dec);
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
	$rep->title = _($title);	
	$rep->InfoSearch = _("Inventory Sales Report");

	$cols = array(0, 90, 210, 250, 300, 375, 450,	515);
	$headers = array(_('Description'), _('Customer'), _('Qty'), _('Trans Date'), _('Sales'), _('Cost'), _('Contribution'));

	if ($fromcust != '')
		$headers[2] = '';

	$aligns = array('left',	'left',	'left', 'right', 'right', 'right', 'right');

	$params =   array(0 => array('from' => $from,'to' => $to),1=>array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => ''));

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


	//customer balances start here
	$rep->title = _($title);
	$rep->InfoSearch = _("Customer Balances");

	$orientation = ($orientation ? 'L' : 'P');
	
	$cust = _('All');
	
    	$dec = user_price_dec();
	
	$convert = true;
	$currency = _('Balances in Home Currency');
	

	if ($no_zeros) $nozeros = _('Yes');
	else $nozeros = _('No');

	$cols = array(0, 100, 130, 190,	250, 320, 385, 450,	515);

	$headers = array(_('Trans Type'), _('#'), _('Date'), _('Due Date'), _('Charges'), _('Credits'),
		_('Allocated'), 	_('Outstanding'));

	$aligns = array('left',	'left',	'left',	'left',	'right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
    				    1 => array('text' => _('Period'), 'from' => $from, 		'to' => $to),
    				    2 => array('text' => _('Customer'), 'from' => $cust,   	'to' => ''),
    				    3 => array('text' => _('Currency'), 'from' => $currency, 'to' => ''),
				    4 => array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => '')
				    );

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns,0, $header2);
	$rep->NewPage();

	$grandtotal = array(0,0,0,0);

	$sql = "SELECT debtor_no, name, curr_code FROM ".TB_PREF."debtors_master ";
	
	$sql .= " ORDER BY name";
	$result = db_query($sql, "The customers could not be retrieved");
	$num_lines = 0;

	while ($myrow = db_fetch($result))
	{
		if (!$convert && $currency != $myrow['curr_code']) continue;
		
		$accumulate = 0;
		$rate = $convert ? get_exchange_rate_from_home_currency($myrow['curr_code'], Today()) : 1;
		$bal = get_cust_open_balance($myrow['debtor_no'], $from, $convert);
		$init[0] = $init[1] = 0.0;
		$init[0] = round2(abs($bal['charges']*$rate), $dec);
		$init[1] = round2(Abs($bal['credits']*$rate), $dec);
		$init[2] = round2($bal['Allocated']*$rate, $dec);
			
		$init[3] = round2($bal['OutStanding']*$rate, $dec);

		$res = get_cust_transactions($myrow['debtor_no'], $from, $to);


		if ($no_zeros && db_num_rows($res) == 0) continue;

 		$num_lines++;
		$rep->fontSize += 2;
		$rep->TextCol(0, 2, $myrow['name']);
		if ($convert)
			$rep->TextCol(2, 3,	$myrow['curr_code']);
		$rep->fontSize -= 2;
		$rep->TextCol(3, 4,	_("Open Balance"));
		$rep->AmountCol(4, 5, $init[0], $dec);
		$rep->AmountCol(5, 6, $init[1], $dec);
		$rep->AmountCol(6, 7, $init[2], $dec);
		$rep->AmountCol(7, 8, $init[3], $dec);
		$total = array(0,0,0,0);
		for ($i = 0; $i < 4; $i++)
		{
			$total[$i] += $init[$i];
			$grandtotal[$i] += $init[$i];
		}
		$rep->NewLine(1, 2);
		if (db_num_rows($res)==0)
			continue;
		$rep->Line($rep->row + 4);
		while ($trans = db_fetch($res))
		{
			if ($no_zeros && floatcmp($trans['TotalAmount'], $trans['Allocated']) == 0) continue;
			$rep->NewLine(1, 2);
			$rep->TextCol(0, 1, $systypes_array[$trans['type']]);
			$rep->TextCol(1, 2,	$trans['reference']);
			$rep->DateCol(2, 3,	$trans['tran_date'], true);
			if ($trans['type'] == ST_SALESINVOICE)
				$rep->DateCol(3, 4,	$trans['due_date'], true);
			$item[0] = $item[1] = 0.0;
			if ($trans['type'] == ST_CUSTCREDIT || $trans['type'] == ST_CUSTPAYMENT || $trans['type'] == ST_BANKDEPOSIT)
				$trans['TotalAmount'] *= -1;
			if ($trans['TotalAmount'] > 0.0)
			{
				$item[0] = round2(abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(4, 5, $item[0], $dec);
				$accumulate += $item[0];
			}
			else
			{
				$item[1] = round2(Abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(5, 6, $item[1], $dec);
				$accumulate -= $item[1];
			}
			$item[2] = round2($trans['Allocated'] * $rate, $dec);
			$rep->AmountCol(6, 7, $item[2], $dec);
			if ($trans['type'] == ST_SALESINVOICE || $trans['type'] == ST_BANKPAYMENT)
				$item[3] = $item[0] + $item[1] - $item[2];
			else	
				$item[3] = $item[0] - $item[1] + $item[2];
			if ($show_balance)	
				$rep->AmountCol(7, 8, $accumulate, $dec);
			else	
				$rep->AmountCol(7, 8, $item[3], $dec);
			for ($i = 0; $i < 4; $i++)
			{
				$total[$i] += $item[$i];
				$grandtotal[$i] += $item[$i];
			}
			if ($show_balance)
				$total[3] = $total[0] - $total[1];
		}
		$rep->Line($rep->row - 8);
		$rep->NewLine(2);
		$rep->TextCol(0, 3, _('Total'));
		for ($i = 0; $i < 4; $i++)
			$rep->AmountCol($i + 4, $i + 5, $total[$i], $dec);
   		$rep->Line($rep->row  - 4);
   		$rep->NewLine(2);
	}
	$rep->fontSize += 2;
	$rep->TextCol(0, 3, _('Grand Total'));
	$rep->fontSize -= 2;
	if ($show_balance)
		$grandtotal[3] = $grandtotal[0] - $grandtotal[1];
	for ($i = 0; $i < 4; $i++)
		$rep->AmountCol($i + 4, $i + 5, $grandtotal[$i], $dec);
	$rep->Line($rep->row  - 4);
	$rep->NewLine();

	//customer balances ends here


	//supplier balances start here
	$rep->title = _($title);	
	$rep->InfoSearch = _("Supplier Balances");
	
	$orientation = ($orientation ? 'L' : 'P');
	
	$supp = _('All');
	
    	$dec = user_price_dec();

	$convert = true;
	$currency = _('Balances in Home currency');
	

	if ($no_zeros) $nozeros = _('Yes');
	else $nozeros = _('No');

	$cols = array(0, 100, 130, 190,	250, 320, 385, 450,	515);

	$headers = array(_('Trans Type'), _('#'), _('Date'), _('Due Date'), _('Charges'),
		_('Credits'), _('Allocated'), _('Outstanding'));

	$aligns = array('left',	'left',	'left',	'left',	'right', 'right', 'right', 'right');

    	$params =   array( 	0 => $comments,
    			1 => array('text' => _('Period'), 'from' => $from, 'to' => $to),
    			2 => array('text' => _('Supplier'), 'from' => $supp, 'to' => ''),
    			3 => array(  'text' => _('Currency'),'from' => $currency, 'to' => ''),
			4 => array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => ''));

   	

    	$rep->Font();
    	$rep->Info($params, $cols, $headers, $aligns);
    	$rep->NewPage();

	$total = array();
	$grandtotal = array(0,0,0,0);

	$sql = "SELECT supplier_id, supp_name AS name, curr_code FROM ".TB_PREF."suppliers";
	
	$sql .= " ORDER BY supp_name";
	$result = db_query($sql, "The customers could not be retrieved");

	while ($myrow=db_fetch($result))
	{
		if (!$convert && $currency != $myrow['curr_code'])
			continue;
		$accumulate = 0;
		$bal = get_sup_open_balance($myrow['supplier_id'], $from);
		$init[0] = $init[1] = 0.0;
		$init[0] = round2(abs($bal['charges']), $dec);
		$init[1] = round2(Abs($bal['credits']), $dec);
		$init[2] = round2($bal['Allocated'], $dec);
		$init[3] = round2($bal['OutStanding'], $dec);

		$total = array(0,0,0,0);
		for ($i = 0; $i < 4; $i++)
		{
			$total[$i] += $init[$i];
			$grandtotal[$i] += $init[$i];
		}
		$res = getsupTransactions($myrow['supplier_id'], $from, $to);
		if ($no_zeros && db_num_rows($res) == 0) continue;

		$rep->fontSize += 2;
		$rep->TextCol(0, 2, $myrow['name']);
		if ($convert) $rep->TextCol(2, 3,	$myrow['curr_code']);
		$rep->fontSize -= 2;
		$rep->TextCol(3, 4,	_("Open Balance"));
		$rep->AmountCol(4, 5, $init[0], $dec);
		$rep->AmountCol(5, 6, $init[1], $dec);
		$rep->AmountCol(6, 7, $init[2], $dec);
		$rep->AmountCol(7, 8, $init[3], $dec);
		$rep->NewLine(1, 2);
		if (db_num_rows($res)==0) continue;

		$rep->Line($rep->row + 4);
		while ($trans=db_fetch($res))
		{
			if ($no_zeros && floatcmp(abs($trans['TotalAmount']), $trans['Allocated']) == 0) continue;
			$rate = $convert ? get_exchange_rate_from_home_currency($myrow['curr_code'], Today()) : 1;

			$rep->NewLine(1, 2);
			$rep->TextCol(0, 1, $systypes_array[$trans['type']]);
			$rep->TextCol(1, 2,	$trans['reference']);
			$rep->DateCol(2, 3,	$trans['tran_date'], true);
			if ($trans['type'] == ST_SUPPINVOICE)
				$rep->DateCol(3, 4,	$trans['due_date'], true);
			$item[0] = $item[1] = 0.0;
			if ($trans['TotalAmount'] > 0.0)
			{
				$item[0] = round2(abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(4, 5, $item[0], $dec);
				$accumulate += $item[0];
			}
			else
			{
				$item[1] = round2(abs($trans['TotalAmount']) * $rate, $dec);
				$rep->AmountCol(5, 6, $item[1], $dec);
				$accumulate -= $item[1];
			}
			$item[2] = round2($trans['Allocated'] * $rate, $dec);
			$rep->AmountCol(6, 7, $item[2], $dec);
			if ($trans['TotalAmount'] > 0.0)
				$item[3] = $item[0] - $item[2];
			else	
				$item[3] = ($item[1] - $item[2]) * -1;
			if ($show_balance)	
				$rep->AmountCol(7, 8, $accumulate, $dec);
			else	
				$rep->AmountCol(7, 8, $item[3], $dec);
			for ($i = 0; $i < 4; $i++)
			{
				$total[$i] += $item[$i];
				$grandtotal[$i] += $item[$i];
			}
			if ($show_balance)
				$total[3] = $total[0] - $total[1];
		}
		$rep->Line($rep->row - 8);
		$rep->NewLine(2);
		$rep->TextCol(0, 3,	_('Total'));
		for ($i = 0; $i < 4; $i++)
		{
			$rep->AmountCol($i + 4, $i + 5, $total[$i], $dec);
			$total[$i] = 0.0;
		}
    	$rep->Line($rep->row  - 4);
    	$rep->NewLine(2);
	}
	$rep->fontSize += 2;
	$rep->TextCol(0, 3,	_('Grand Total'));
	$rep->fontSize -= 2;
	if ($show_balance)
		$grandtotal[3] = $grandtotal[0] - $grandtotal[1];
	for ($i = 0; $i < 4; $i++)
		$rep->AmountCol($i + 4, $i + 5,$grandtotal[$i], $dec);
	$rep->Line($rep->row  - 4);
	$rep->NewLine();

	//supplier bances ends here


	//bank statemetn for default currency account
	$rep->title = _($title);
	$rep->InfoSearch = _("Bank Statement");

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$cols = array(0, 90, 110, 170, 225, 350, 400, 460, 520);

	$aligns = array('left',	'left',	'left',	'left',	'left',	'right', 'right', 'right');

	$headers = array(_('Type'),	_('#'),	_('Reference'), _('Date'), _('Person/Item'),
		_('Debit'),	_('Credit'), _('Balance'));

	
	

	$rep->Font();
	$rep->Info($params, $cols, $headers, $aligns);
	$rep->NewPage();

	$ac_prev_balance = get_bank_balance_to($from);
	
	
	while($account = db_fetch($ac_prev_balance)){

		$prev_balance = $account['prev_balance'];
	
		$act = $account['bank_account_name']." - ".$account['bank_curr_code']." - ".$account['bank_account_number'];
	   	

		$trans = get_bank_transactions($from, $to, $account['id']);

		$rows = db_num_rows($trans);
		if ($prev_balance != 0.0 || $rows != 0)
		{
			$rep->Font('bold');
			$rep->TextCol(0, 3,	$act);
			$rep->TextCol(3, 5, _('Opening Balance'));
			if ($prev_balance > 0.0)
				$rep->AmountCol(5, 6, abs($prev_balance), $dec);
			else
				$rep->AmountCol(6, 7, abs($prev_balance), $dec);
			$rep->Font();
			$total = $prev_balance;
			$rep->NewLine(2);
			$total_debit = $total_credit = 0;
			if ($rows > 0)
			{
				// Keep a running total as we loop through
				// the transactions.
			
				while ($myrow=db_fetch($trans))
				{
					if ($zero == 0 && $myrow['amount'] == 0.0)
						continue;
					$total += $myrow['amount'];

					$rep->TextCol(0, 1, $systypes_array[$myrow["type"]]);
					$rep->TextCol(1, 2,	$myrow['trans_no']);
					$rep->TextCol(2, 3,	$myrow['ref']);
					$rep->DateCol(3, 4,	$myrow["trans_date"], true);
					$rep->TextCol(4, 5,	payment_person_name($myrow["person_type_id"],$myrow["person_id"], false));
					if ($myrow['amount'] > 0.0)
					{
						$rep->AmountCol(5, 6, abs($myrow['amount']), $dec);
						$total_debit += abs($myrow['amount']);
					}
					else
					{
						$rep->AmountCol(6, 7, abs($myrow['amount']), $dec);
						$total_credit += abs($myrow['amount']);
					}
					$rep->AmountCol(7, 8, $total, $dec);
					$rep->NewLine();
					if ($rep->row < $rep->bottomMargin + $rep->lineHeight)
					{
						$rep->Line($rep->row - 2);
						$rep->NewPage();
					}
				}
				$rep->NewLine();
			}
		
			// Print totals for the debit and credit columns.
			$rep->TextCol(3, 5, _("Total Debit / Credit"));
			$rep->AmountCol(5, 6, $total_debit, $dec);
			$rep->AmountCol(6, 7, $total_credit, $dec);
			$rep->NewLine(2);

			$rep->Font('bold');
			$rep->TextCol(3, 5,	_("Ending Balance"));
			if ($total > 0.0)
				$rep->AmountCol(5, 6, abs($total), $dec);
			else
				$rep->AmountCol(6, 7, abs($total), $dec);
			$rep->Font();
			$rep->Line($rep->row - $rep->lineHeight + 4);
			$rep->NewLine(2, 1);
		
			// Print the difference between starting and ending balances.
			$net_change = ($total - $prev_balance); 
			$rep->TextCol(3, 5, _("Net Change"));
			if ($total > 0.0)
				$rep->AmountCol(5, 6, $net_change, $dec, 0, 0, 0, 0, null, 1, True);
			else
				$rep->AmountCol(6, 7, $net_change, $dec, 0, 0, 0, 0, null, 1, True);
		}
		$rep->NewLine();
	}
	//bank statement ends here

	//costed inventory movement report start
	$rep->title = _($title);	
	$rep->InfoSearch = _("Costed Inventory Movements");
	
	$orientation = ($orientation ? 'L' : 'P');
	
	$cat = _('All');

	$loc = _('All');

	$cols = array(0, 60, 130, 160, 185, 210, 250, 275, 300, 340, 365, 390, 430, 455, 480, 520);

	$headers = array(_('Category'), _('Description'),	_('UOM'), '', '', _('OpeningStock'), '', '',_('StockIn'), '', '', _('Delivery'), '', '', _('ClosingStock'));
	$headers2 = array("", "", "", _("QTY"), _("Rate"), _("Value"), _("QTY"), _("Rate"), _("Value"), _("QTY"), _("Rate"), _("Value"), _("QTY"), _("Rate"), _("Value"));

	$aligns = array('left',	'left',	'left', 'right', 'right', 'right', 'right','right' ,'right', 'right', 'right','right', 'right', 'right', 'right');

    $params =   array( 	0 => $comments,
			1 => array('text' => _('Period'), 'from' => $from_date, 'to' => $to_date),
    			2 => array('text' => _('Category'), 'from' => $cat, 'to' => ''),
			3 => array('text' => _('Location'), 'from' => $loc, 'to' => ''),
			4 => array('text' => _('Suppress Zeros'), 'from' => $nozeros, 'to' => '')
			);


    	$rep->Font();
   	$rep->Info($params, $cols, $headers2, $aligns, $cols, $headers, $aligns);
    	$rep->NewPage();

	$result = fetch_items($category);

	$catgor = '';
	while ($myrow=db_fetch($result))
	{
		if ($catgor != $myrow['description'])
		{
			$rep->NewLine(2);
			$rep->fontSize += 2;
			$rep->TextCol(0, 3, $myrow['category_id'] . " - " . $myrow['description']);
			$catgor = $myrow['description'];
			$rep->fontSize -= 2;
			$rep->NewLine();
		}
		$rep->NewLine();
		$rep->TextCol(0, 1,	$myrow['stock_id']);
		$rep->TextCol(1, 2, $myrow['name']);
		$rep->TextCol(2, 3, $myrow['units']);
		$qoh_start= $inward = $outward = $qoh_end = 0; 
		
		$qoh_start += get_qoh_on_date($myrow['stock_id'], $location, add_days($from_date, -1));
		$qoh_end += get_qoh_on_date($myrow['stock_id'], $location, $to_date);
		
		$inward += trans_qty($myrow['stock_id'], $location, $from_date, $to_date);
		$outward += trans_qty($myrow['stock_id'], $location, $from_date, $to_date, false);
		$unitCost=$myrow['material_cost'];
		$rep->AmountCol(3, 4, $qoh_start, get_qty_dec($myrow['stock_id']));
		$rep->AmountCol(4, 5, $myrow['material_cost']);
		$rep->AmountCol(5, 6, $qoh_start*$unitCost, get_qty_dec($myrow['stock_id']));
		
		if($inward>0){
			$rep->AmountCol(6, 7, $inward, get_qty_dec($myrow['stock_id']));
			$unitCost_IN=	trans_qty_unit_cost($myrow['stock_id'], $location, $from_date, $to_date);
			$rep->AmountCol(7, 8, $unitCost_IN,get_qty_dec($myrow['stock_id']));
			$rep->AmountCol(8, 9, $inward*$unitCost_IN, get_qty_dec($myrow['stock_id']));
		}
		
		if($outward>0){
			$rep->AmountCol(9, 10, $outward, get_qty_dec($myrow['stock_id']));
		
			$unitCost_out=	trans_qty_unit_cost($myrow['stock_id'], $location, $from_date, $to_date, false);
			$rep->AmountCol(10, 11, $unitCost_out,get_qty_dec($myrow['stock_id']));
			$rep->AmountCol(11, 12, $outward*$unitCost_out, get_qty_dec($myrow['stock_id']));
		}
		
		$rep->AmountCol(12, 13, $qoh_end, get_qty_dec($myrow['stock_id']));
		$rep->AmountCol(13, 14, $myrow['material_cost'],get_qty_dec($myrow['stock_id']));
		$rep->AmountCol(14, 15, $qoh_end*$unitCost, get_qty_dec($myrow['stock_id']));
		
		$rep->NewLine(0, 1);
	}
	$rep->Line($rep->row  - 4);

	$rep->NewLine();

	//costed inventory movement report ends
	

	
	
	if ($email == 1)
		$rep->End($email,'EOD Report');
	else
		$rep->End();
}

//-----------------------------------------------------------




?>
