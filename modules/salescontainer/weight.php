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
$page_security = 'SA_WEIGHT';
$path_to_root = "../..";

include($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");

add_access_extensions();
set_ext_domain('modules/salescontainer');

function get_control_file($file, $index = false) {

	$list = gzopen($file, 'rb');
	if (!$list) return null;

	$repo = $pkg = array();
	$key = false; $value = '';
	$line = '';
	do {
		$line = rtrim($line);
		if ($line && ctype_space($line[0])) { // continuation of multiline property
			if (strlen(ltrim($line))) {
				if ($value !== '' && !is_array($value))
					$value = array($value);
				$value[] = ltrim($line);
				continue;
			}
		}
		if ($key) { // save previous property if any
			$pkg[$key] = ufmt_property($key, $value);
		}
		if (!strlen($line)) { // end of section
			if (count($pkg)) {
				if ($index !== true) {
					if ($index === false) break;
					if (!isset($pkg[$index])) {
						display_error(sprintf(_("No key field '%s' in file '%s'"), $index, $file));
						return null;
					}
					$repo[$pkg[$index]] = $pkg;
				} else
					$repo[] = $pkg;
			}
			$pkg = array(); 
			$key = null; $value = '';
			continue;
		} elseif (preg_match('/([^:]*):\s*(.*)/', $line, $m)) {
			$key = $m[1]; $value = $m[2];
			if (!strlen($key)) {
				display_error("Empty key in line $line");
				return null;
			}
		} else {
			display_error("File parse error in line $line");
			return null;
		}
		
	} while ((($line = fgets($list))!==false) || $key);
	fclose($list);

	return $index === false ? $pkg : $repo;
}

function ufmt_property($key, $value)
{
	// indexes used in output arrays
	$sub_fields = array(
//		'MenuTabs' => array('url', 'access', 'tab_id', 'title', 'section'),
//		'MenuEntries' => array('url', 'access', 'tab_id', 'title'),
	);
	if (!isset($sub_fields[$key]))
		return $value==='' ? null : $value;

	$prop = array();

	if (!is_array($value))
		$value = array($value);
	foreach($value as $line) {
		$indexes = $sub_fields[$key];
		$ret = array();
		preg_match_all('/(["])(?:\\\\?+.)*?\1|[^"\s][\S]*/', $line, $match);
		foreach($match[0] as $n => $subf) {
			if ($match[1][$n])
				$val = strtr(substr($subf, 1, -1),
					array('\\"'=>'"'));
		else
				$val = $subf;
			if (count($indexes))
				$ret[array_shift($indexes)] = $val;
			else
				$ret[] = $val;
		}
		if (count($ret))
			$prop[] = $ret;
	}
	return $prop;
}


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(400, 400);
if ($use_date_picker)
	$js .= get_js_date_picker();


//--------------------------------------------------------------------------------------------
function handle_submit()
{
	global $Ajax;

	$_SESSION['first_weight'] = $_POST['first_weight'];

	$Ajax->activate('page_body');

	display_notification("Ok Close this Popup");


}

if (isset($_POST['submit'])) 
{
	handle_submit($selected_id);
}
//--------------------------------------------------------------------------------------------

	
page(_($help_context = "Weight"), @$_REQUEST['popup'], false, "", $js); 
start_form();

if(file_exists('data.txt')){
	$rs = get_control_file('data.txt');

	start_table(TABLESTYLE_NOBORDER);

	
	foreach($rs as $key=>$val){
		start_row();

		label_cells($key." : ",$val);
		if($key == 'WEIGHT'){
			
			$weight = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
			hidden('weight', $val);
		}


		end_row();
		
	}

	end_table();

	div_start('controls');
		if($rs)
			submit_return_center('select', abs($weight), _("Select this shipping details and return to document entry."));

	div_end();

	hidden('popup', @$_REQUEST['popup']);
	end_form();
}else{
	display_error("Data not Received.");
}

end_page(@$_REQUEST['popup']);

?>