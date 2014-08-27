<?php
// ----------------------------------------------------------------
// Module name: Other Report
// Author: Swapna
// ----------------------------------------------------------------

define ('SS_OTHRREP', 250<<8);

class hooks_other_report extends hooks {
	var $module_name = 'other_report';

    function install_options($app) {
        global $path_to_root;

        
    }

    function install_access()
    {

        $security_sections[SS_OTHRREP] = _("Other Reports");
        $security_areas['SA_EODREP'] = array(SS_OTHRREP|1, _("EOD Report"));        
        return array($security_areas, $security_sections);
    }

/* This method is called on extension activation for company.   */
    function activate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'update_other_report_db.sql' => array('other_report_widgets')
        );

        return $this->update_databases($company, $updates, $check_only);
    }

    function deactivate_extension($company, $check_only=true)
    {
        global $db_connections;

        $updates = array(
            'drop_other_report_db.sql' => array('ugly_hack') // FIXME: just an ugly hack to clean database on deactivation
        );

        return $this->update_databases($company, $updates, $check_only);
    }
}

?>
