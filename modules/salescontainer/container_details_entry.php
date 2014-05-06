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
$page_security = 'SA_SALESCONTAINER';
$path_to_root = "../..";
include($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/sales/includes/sales_ui.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

page(_($help_context = "Container Details Entry"));	

start_form();
	start_table(TABLESTYLE, "width=80%", 10);
			start_row();
				customer_list_row(_("Customer:"), 'customer_id', null, false, true, false, true);

				ref_row(_("Vehicle Number").':', 'vehicle_no', _(''), null, '');

			end_row();
	end_table(1);

	submit_center('update_container','Update',true);
end_form();

end_page();