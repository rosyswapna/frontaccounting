<?php
/**********************************************************************
   this report file earlier used for export invoice 
   now this file not used
   export invoice from rep116
***********************************************************************/
$page_security = $_POST['PARAM_0'] == $_POST['PARAM_1'] ?
	'SA_SALESTRANSVIEW' : 'SA_SALESBULKREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Swapna
// date_:	2014-06-18
// Title:	Print Invoices
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");
include_once($path_to_root."/admin/db/attachments_db.inc");

//----------------------------------------------------------------------------------------------------

print_export_invoices();

//----------------------------------------------------------------------------------------------------

function print_export_invoices()
{
	global $path_to_root, $alternative_tax_include_on_docs, $suppress_tax_rates, $no_zero_lines_amount;
	
	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$currency = $_POST['PARAM_2'];
	$email = $_POST['PARAM_3'];
	$pay_service = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$customer = $_POST['PARAM_6'];
	$orientation = $_POST['PARAM_7'];

	if (!$from || !$to) return;

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

 	$fno = explode("-", $from);
	$tno = explode("-", $to);
	$from = min($fno[0], $tno[0]);
	$to = max($fno[0], $tno[0]);

	$cols = array(4, 60, 225, 300, 325, 385, 450, 515);
	

	// $headers in doctext.inc
	$aligns = array('left',	'left',	'right', 'left', 'right', 'right', 'right');

	$params = array('comments' => $comments);

	$cur = get_company_Pref('curr_default');

	if ($email == 0)
		$rep = new FrontReport(_('INVOICE'), "InvoiceBulk", user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);
	for ($i = $from; $i <= $to; $i++)
	{
			if (!exists_customer_trans(ST_EXPORTINVOICE, $i))
				continue;
			$sign = 1;
			$myrow = get_customer_trans($i, ST_EXPORTINVOICE);


			if($customer && $myrow['debtor_no'] != $customer) {
				continue;
			}
			
			$sales_order = get_sales_order_header($myrow["order_"], ST_SALESORDER);
			if($sales_order['bank_account_id'] > 0){
				$acid = $sales_order['bank_account_id'];
			}else{
				$acid = $myrow['curr_code'];
			}

			//shipment details
			$shipping = get_shipping_detail($sales_order["shipping_id"]);

			//attachments
			$attachments_desc = array();
			$result_attachments = get_attached_documents(ST_EXPORTINVOICE,$myrow["trans_no"]);
			while($attachment = mysql_fetch_assoc($result_attachments)){
				$attachments_desc[] = ($attachment['description'] != '')?$attachment['description']:$attachment['filename'];
			}
			

			//bank account
			$baccount = get_default_bank_account($acid);
			$params['bankaccount'] = $baccount['id'];

			$branch = get_branch($myrow["branch_code"]);

			if ($email == 1)
			{
				$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
				$rep->title = _('INVOICE');
				$rep->filename = "Invoice" . $myrow['reference'] . ".pdf";
			}	
			$rep->SetHeaderType('Header5');
			$rep->currency = $cur;
			$rep->Font();
			$rep->Info($params, $cols, null, $aligns);

			$contacts = get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no'], true);
			$baccount['payment_service'] = $pay_service;

			//transacion details ( items )
			$result = get_customer_trans_details(ST_EXPORTINVOICE, $i);
   			$trans = array();//$Totals = array();
			$TotalDiscount = $TotalAmount = $GrossAmount = 0;
			 		 
			$myrow2 = db_fetch($result);
			if ($myrow2["quantity"] == 0)
				continue;

			$Discount = $myrow2["discount_percent"]*100;
    		$Total = $myrow2["unit_price"] * $myrow2["quantity"];
    		$Net = round2($sign * ((1 - $myrow2["discount_percent"]) * $myrow2["unit_price"] * $myrow2["quantity"]),
			   user_price_dec());

	  		$TotalDiscount += $Discount;
	  		$TotalAmount += $Total;
			$GrossAmount += $Net;

    		$DisplayPrice = number_format2($myrow2["unit_price"],$dec);
    		$DisplayQty = number_format2($sign*$myrow2["quantity"],get_qty_dec($myrow2['stock_id']));
    		$DisplayTotal = number_format2($Total,$dec);


	  		$trans = array('1',$myrow2['StockDescription'],$sales_order['packing'],$DisplayQty,$DisplayPrice);	
							

			$rep->SetCommonData($myrow, $branch, $sales_order, $baccount, ST_EXPORTINVOICE, $contacts,$shipping);

			$DisplayGrossAmount = number_format2($GrossAmount,$dec);
			$DisplayTotalAmount = number_format2($TotalAmount,$dec);
			$DisplayTotalDiscount = number_format2($TotalDiscount,$dec);
			$DisplayWords = price_in_words($myrow['Total'], ST_EXPORTINVOICE);
			
			$rep->formData['words'] = $DisplayWords;
			$rep->formData['total_amount'] = $DisplayTotalAmount;
			$rep->formData['adv_disc'] = $DisplayTotalDiscount;
			$rep->formData['gross_amount'] = $DisplayGrossAmount;

			$rep->formData['item'] = $trans;
			$rep->formData['attachments'] = $attachments_desc;
			

			$rep->NewPage();
			
			if ($email == 1)
			{
				$rep->End($email);
			}
	}
	if ($email == 0)
		$rep->End();
}

?>
