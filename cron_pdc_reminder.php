<?php

//constant from includes/types.inc
//trans types
define('ST_BANKPAYMENT', 1);
define('ST_BANKDEPOSIT', 2);
define('ST_CUSTPAYMENT', 12);
define('ST_SUPPAYMENT', 22);

$trans_types = array(
		ST_BANKPAYMENT => "Bank Payment",
		ST_BANKDEPOSIT => "Bank Deposit",
		ST_CUSTPAYMENT => "Customer Payment",
		ST_SUPPAYMENT  => "Supplier Payment");

//	Payment types/person types
define('PT_MISC', 0);
define('PT_WORKORDER', 1);
define('PT_CUSTOMER', 2);
define('PT_SUPPLIER', 3);
define('PT_QUICKENTRY', 4);
define('PT_DIMESION', 5);

$person_types = array(
		PT_MISC => "Miscellaneous",
		PT_WORKORDER => "Worker",
		PT_CUSTOMER => "Customer",
		PT_SUPPLIER => "Supplier",
		PT_QUICKENTRY => "Quick Entry",
		PT_DIMESION => "Dimension"
	);
//----------------------------------------------------------------------------------------------------

//functions
function get_connection()
{
	$path_to_root = "./";
	include_once($path_to_root . "/config_db.php");
	global $db,$tbpref;

	$company = 0;
	$connection = $db_connections[$company];

	$db = mysql_connect($connection["host"], $connection["dbuser"], $connection["dbpassword"]);
		mysql_select_db($connection["dbname"], $db);

	$tbpref = $connection["tbpref"];

	return $db;
}


function send_mail($person = array(),$days=0,$trans_details = array()){

	global $trans_types,$person_types;

	$mail = false;

	$type = $trans_details['type'];

	if(isset($person['email']) and $person['email'] != ""){

		$from = "webmaster@example.com";
		$to = $person['email'];
		$subject = "PDC Reminder";

		if($person['person_type'] == 'customer'){
			$salutation = "Dear ". $person['ref'];
		}else{
			$salutation = "Dear Administrator,";
		}
		
		switch($days){
			case 0:$message = "This is the fourth Reminder for ".$trans_types[$type]." of ".$person['ref'];break;
			case 1:$message = "This is the third Reminder for ".$trans_types[$type]." of ".$person['name'];break;
			case 2:$message = "This is the second Reminder for ".$trans_types[$type]." of ".$person['name'];break;
			case 7:$message = "This is the first reminder for ".$trans_types[$type]." of ".$person['name'];break;
			default:$message = "";
		}
		$html = '<html><body>
				<p>'.$salutation.',</p>
				<p>'.$message.'</p>
				<table border=1  cellspacing=0 >
					<tr>
						<th width="200" align="left">Voucher No.</th>
						<td>'.$trans_details['voucher_no'].'</td>
					</tr>
					<tr>
						<th align="left">Transaction Type</th>
						<td>'.$trans_details['trans_type'].'</td>
					</tr>
					<tr>
						<th align="left">'.$person_types[$trans_details['pt_type']].'</th>
						<td>'.$person['ref'].'</td>
					</tr>
					<tr>
						<th align="left">Date of Transaction</th>
						<td>'.$trans_details['trans_date'].'</td>
					</tr>
					<tr>
						<th align="left">Amount</th>
						<td>'.$trans_details['amount'].'</td>
					</tr>
					<tr>
						<th align="left">Memo</th>
						<td>Cheque Date :'.$trans_details['cheque_date'].'<br/>'.$trans_details['memo'].'</td>
					</tr>
				</table>
				</body></html>';

		$headers = "From: ".$from." \r\n";
		$headers .= "Reply-To: ".$from." \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		//echo $html;


		if(mail($to, $subject, $html, $headers))
			$mail = true;
	}
	return $mail;
	
}

function get_person_type($type = -1,$person_type_id = -1)
{
	switch($type){

		case  ST_CUSTPAYMENT: 
				//customer payment , send mail to customer
				$person_type = 'customer';	
				break;

		case  ST_SUPPAYMENT:
				//payment to supplier , send mail to customer
				$person_type = 'supplier';
				break;

		case ST_BANKPAYMENT:	
			 ST_BANKDEPOSIT:
				
				switch($person_type_id){
					case PT_MISC :
					case PT_QUICKENTRY :
					case PT_WORKORDER :
										break;
					case PT_CUSTOMER:
										$person_type = 'customer';
										break;
					case PT_SUPPLIER:
										$person_type = 'supplier';
										break;
					default:
				}
				break;

		default:$person_type=false;
	
	}
	return $person_type;
}

function get_contact_result($person_type,$person_id)
{
	global $db;
	//get contact details
	$sql = "SELECT cp.* ,cc.type as person_type
					FROM crm_persons cp
					LEFT JOIN crm_contacts cc ON cc.person_id = cp.id
					WHERE cc.type = '{$person_type}' AND cc.entity_id = '{$person_id}' GROUP BY cc.entity_id";

	return mysql_query($sql,$db);
}



//----------------------------------------------------------------------------------------------------

get_connection();
 
$today = date('Y-m-d');


$sql = "SELECT bt.*, act.*, cm.memo_ as memo
		FROM {$tbpref}bank_trans as bt

		LEFT JOIN {$tbpref}comments as  cm ON bt.type =cm.type AND bt.trans_no = cm.id 

		LEFT JOIN {$tbpref}bank_accounts act ON act.id = bt.bank_act

		WHERE  bt.cheque = '1' AND cheque_date >= '{$today}' 

		ORDER BY bt.cheque_date ASC";
		
//echo $sql;exit();
$result = mysql_query($sql,$db);

$pdc_for_days = array(0,1,2,7); //reminder on the day, 1 day before, 2 days before and  7 days before PDC
$mail = 0;//count mail send

while($row = mysql_fetch_assoc($result)){
	//echo "<pre>";print_r($row);echo "</pre>";
	$trans_details = array(
					'type'	=> $row['type'],
					'voucher_no' => $row['trans_no'],//Gl payment #
					'trans_type' => $trans_types[$row['type']],
					'trans_date' => $row['trans_date'],
					'amount'	=> abs($row['amount']),
					'memo'	=> nl2br($row['memo']),
					'cheque_date' => $row['cheque_date'],
					'pt_type' => $row['person_type_id']
					);

	
	$days_before_pdc = strtotime($row['cheque_date'])-strtotime($today);
	$days = floor($days_before_pdc/3600/24);
	
	if(in_array($days, $pdc_for_days)){
		$person_id = $row['person_id']; 
		$person_type = get_person_type($row['type'],$row['person_type_id']);

		if($person_type){
			//get contact details
			$person_res = get_contact_result($person_type,$person_id);

			if(mysql_num_rows($person_res) > 0){
				$person = mysql_fetch_assoc($person_res);
				$mail += send_mail($person,$days,$trans_details);
			}
		}
	}	
	
}


echo "Total ".$mail." mail send from PDC Reminder";

//----------------------------------------------------------------------------------------------------














?>