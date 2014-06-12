<?php
// ----------------------------------------------------------------
// Module name: Export Invoice
// Author: Swapna
// ----------------------------------------------------------------

define ('SS_EXPORT', 150<<8);

class hooks_export_invoice extends hooks {
	var $module_name = 'export_invoice';

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'orders':
            $app->add_lapp_function(0, "","");
              // $app->add_lapp_function(0, _("Export &Invoice"),
            //"modules/export_invoice/export_invoice_entry.php", 'SA_EXPORTINVOICE', MENU_TRANSACTION);

               $app->add_lapp_function(0, _("Export &Invoice"),
            "sales/sales_order_entry.php?NewExportInvoice=0", 'SA_EXPORTINVOICE', MENU_TRANSACTION);
                break;
        }
    }

    function install_access()
    {

        $security_sections[SS_EXPORT] = _("Export Invoice");

        $security_areas['SA_EXPORTINVOICE'] = array(SS_EXPORT|1, _("Export Invoice"));
        
        
        return array($security_areas, $security_sections);
    }

    /* This method is called on extension activation for company.   */
    function activate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'update_export_invoice_db.sql' => array('export_invoice_widgets')
        );

        return $this->update_databases($company, $updates, $check_only);
    }

    function deactivate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'drop_export_invoice_db.sql' => array('ugly_hack') // FIXME: just an ugly hack to clean database on deactivation
        );

        return $this->update_databases($company, $updates, $check_only);
    }


}

?>