<?php
// ----------------------------------------------------------------
// Module name: Sales Container
// Author: Swapna
// ----------------------------------------------------------------

define ('SS_CONTAINER', 150<<8);

class hooks_salescontainer extends hooks {
	var $module_name = 'salescontainer';

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'orders':
                $app->add_rapp_function(2, _('&Container Details Entry'), $path_to_root.'/modules/salescontainer/container_details_entry.php', 'SA_SALESCONTAINER',
                    MENU_TRANSACTION);
                break;
        }
    }

    function install_access()
    {

        $security_sections[SS_CONTAINER] = _("Sales Container");

        $security_areas['SA_SALESCONTAINER'] = array(SS_CONTAINER|1, _("Sales Container Entry"));
        
        return array($security_areas, $security_sections);
    }

/* This method is called on extension activation for company.   */
    function activate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'update_salescontainer_db.sql' => array('salescontainer_widgets')
        );

        return $this->update_databases($company, $updates, $check_only);
    }

    function deactivate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'drop_salescontainer_db.sql' => array('ugly_hack') // FIXME: just an ugly hack to clean database on deactivation
        );

        return $this->update_databases($company, $updates, $check_only);
    }
}

?>