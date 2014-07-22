<?php
$path_to_root = "../..";
$page_security = 'SA_SHIPMENTREPORT';
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/reporting/includes/reports_classes.inc");

//----------------------------------------------------------------------------------------------------

add_access_extensions();
set_ext_domain('modules/salescontainer');

page(_($help_context = "Reports and Analysis"), false, false, "", $js);

$reports = new BoxReports;

$reports->addReport(RC_INVENTORY, 309,_('Shipment Report'),
	array(	_('Start Date') => 'DATEBEGINM',
			_('End Date') => 'DATEENDM',
			_('Inventory Category') => 'CATEGORIES',
			_('Comments') => 'TEXTBOX',
			_('Orientation') => 'ORIENTATION',
			_('Destination') => 'DESTINATION'));

add_custom_reports($reports);

echo $reports->getDisplay();

end_page();

?>