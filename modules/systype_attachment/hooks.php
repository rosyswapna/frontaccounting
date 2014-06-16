<?php
// ----------------------------------------------------------------
// Module name: Sales Container
// Author: Swapna
// ----------------------------------------------------------------

define ('SS_SYSTYPEATTACHMENT', 150<<8);

class hooks_systype_attachment extends hooks {
	var $module_name = 'systype_attachment';

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'system':

            $app->add_lapp_function(2, _("&Default Attachments"),
            "modules/systype_attachment/attachment.php", 'SA_ATTACHMENT', MENU_MAINTENANCE);

                break;

        }
    }

    function install_access()
    {

        $security_sections[SS_SYSTYPEATTACHMENT] = _("Default Attachments");

        $security_areas['SA_ATTACHMENT'] = array(SS_SYSTYPEATTACHMENT|1, _("Default Attachments"));

        return array($security_areas, $security_sections);
    }

/* This method is called on extension activation for company.   */
    function activate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'update_systype_attachment_db.sql' => array('systype_attachment_widgets')
        );

        return $this->update_databases($company, $updates, $check_only);
    }

    function deactivate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'drop_systype_attachment_db.sql' => array('ugly_hack') // FIXME: just an ugly hack to clean database on deactivation
        );

        return $this->update_databases($company, $updates, $check_only);
    }
}

?>