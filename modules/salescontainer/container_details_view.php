<?php

$page_security = 'SA_SALESCONTAINERVIEW';
$path_to_root = "../..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

page(_($help_context = "Shipments"));

start_table();
	start_row();
		echo "<td>hai</td>";
	end_row();
end_table();


end_page();

?>