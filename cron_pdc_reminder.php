<?php
$path_to_root = "./";

$page_security = 'SA_CRON_PDC';


include_once($path_to_root . "/config_db.php");

//constant from includes/types.inc
//trans types
define('ST_BANKPAYMENT', 1);
define('ST_BANKDEPOSIT', 2);
define('ST_CUSTPAYMENT', 12);
define('ST_SUPPAYMENT', 22);

//	Payment types/person types
define('PT_MISC', 0);
define('PT_WORKORDER', 1);
define('PT_CUSTOMER', 2);
define('PT_SUPPLIER', 3);
define('PT_QUICKENTRY', 4);
define('PT_DIMESION', 5);



$company = 0;

$connection = $db_connections[$company];

$db = mysql_connect($connection["host"], $connection["dbuser"], $connection["dbpassword"]);
		mysql_select_db($connection["dbname"], $db);

$tbpref = $connection["tbpref"];

$today = date('Y-m-d');

$sql = "SELECT bt.*, act.*
		FROM {$tbpref}bank_trans bt, {$tbpref}bank_accounts act
		WHERE act.id=bt.bank_act AND bt.cheque = '1' AND cheque_date = '{$today}'					
		ORDER BY bt.cheque_date ASC";


$result = mysql_query($sql,$db);
	

$subject = "PDC Reminder";

$mail = 0;

while($row = mysql_fetch_assoc($result)){
	

	if($row['type'] == ST_CUSTPAYMENT){ //customer payment , send mail to customer
		$person_id = $row['person_id']; 
		$person_type = 'customer';	
		
		
	}elseif($row['type'] == ST_SUPPAYMENT){ //payment to supplier , send mail to customer

		$person_id = $row['person_id']; 
		$person_type = 'supplier';

		
	}

	$person_sql = "SELECT cp.* ,cc.type as person_type
					FROM crm_persons cp
					LEFT JOIN crm_contacts cc ON cc.person_id = cp.id
					WHERE cc.type = '{$person_type}' AND cc.entity_id = '{$person_id}' GROUP BY cc.entity_id";

	$person_res = mysql_query($person_sql,$db);
	if(mysql_num_rows($person_res) > 0){
		$person = mysql_fetch_assoc($person_res);
		
		if($person['email'] != ""){

			$to = $person['email'];

			if($person['person_type'] == 'customer'){
				$text = "Dear ". $person['ref'];
			}else{
				$text = "Dear Administrator,";
			}

			$headers = "From: webmaster@example.com \r\n";
			$headers .= "Reply-To: webmaster@example.com \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message = '<html><body>';
			$message .= '<p>'.$text.'</p>';
			$message .= '</body></html>';
			echo $message;//exit();

			//if(mail($to, $subject, $message, $headers))
				$mail++;

		}
	}

	
}

if($mail > 0)
	echo $mail." mail send From PDC Reminder";
else
	echo "error in sending mail";






?>