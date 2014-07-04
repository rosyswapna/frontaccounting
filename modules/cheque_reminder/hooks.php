<?php
// ----------------------------------------------------------------
// Module name: cheque reminder
// Author: Swapna
// ----------------------------------------------------------------

define ('SS_CHEQUEREMINDER', 150<<8);

class hooks_cheque_reminder extends hooks {
	var $module_name = 'cheque_reminder';

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'GL':

            $app->add_lapp_function(1, _("&Cheque Reminder"),
            "modules/cheque_reminder/cheque_reminders.php", 'SA_CQREMINDER', MENU_INQUIRY);

                break;

        }
    }

    function install_access()
    {

        $security_sections[SS_CHEQUEREMINDER] = _("Cheque Reminder");

        $security_areas['SA_CQREMINDER'] = array(SS_CHEQUEREMINDER|1, _("Cheque Reminders"));

        return array($security_areas, $security_sections);
    }

/* This method is called on extension activation for company.   */
    function activate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'update_cheque_reminder_db.sql' => array('cheque_reminder_widgets')
        );

        return $this->update_databases($company, $updates, $check_only);
    }

    function deactivate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'drop_cheque_reminder_db.sql' => array('ugly_hack') // FIXME: just an ugly hack to clean database on deactivation
        );

        return $this->update_databases($company, $updates, $check_only);
    }
}

?>