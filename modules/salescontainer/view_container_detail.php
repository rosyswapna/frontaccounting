<?php
/*
	view container details as popup
*/

$page_security = 'SA_SALESCONTAINERVIEW';
$path_to_root = "../..";

include_once($path_to_root . "/sales/includes/cart_class.inc");

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");

include_once($path_to_root . "/sales/includes/sales_ui.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 600);

page(_($help_context = "View Shipment"), true, false, "", $js);


	if (isset($_GET["shp_no"]))
	{
		$shp_id = $_GET["shp_no"];
	}
	elseif (isset($_POST["shp_no"]))
	{
		$shp_id = $_POST["shp_no"];
	}

	$myrow = get_shipping_detail($shp_id);

	display_heading(sprintf(_("Shipment #%d"),$_GET['shp_no']));

	echo "<br>";

	start_table(TABLESTYLE2, "width=95%", 5);

		start_row();

			label_cells(_("Customer Name"), $myrow['customer'], "class='tableheader2'");

			label_cells(_("Vehicle Details"), $myrow['vehicle_details'], "class='tableheader2'");

		end_row();

		start_row();

			label_cells(_("First Weight"), $myrow['first_weight'], "class='tableheader2'");
			
			label_cells(_("First Weight Date"), $myrow['first_weight_date'], "class='tableheader2'");

		end_row();

		start_row();

			label_cells(_("Second Weight"), $myrow['second_weight'], "class='tableheader2'");
			
			label_cells(_("Second Weight Date"), $myrow['second_weight_date'], "class='tableheader2'");

		end_row();

	end_table();


end_page(true, false, false, $_GET['trans_type'], $_GET['trans_no']);


?>