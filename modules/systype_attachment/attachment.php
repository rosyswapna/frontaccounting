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
$path_to_root = "../..";
$page_security = 'SA_ATTACHMENT';

include_once($path_to_root . "/includes/db_pager.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/admin/db/systype_attachments_db.inc");
include_once($path_to_root . "/admin/db/transactions_db.inc");

add_access_extensions();

if (isset($_GET['vw']))
	$view_id = $_GET['vw'];
else
	$view_id = find_submit('view');
if ($view_id != -1)
{
	$row = get_attachment($view_id);
	if ($row['filename'] != "")
	{
		if(in_ajax()) {
			$Ajax->popup($_SERVER['PHP_SELF'].'?vw='.$view_id);
		} else {
			$type = ($row['filetype']) ? $row['filetype'] : 'application/octet-stream';	
    		header("Content-type: ".$type);
    		header('Content-Length: '.$row['filesize']);
	    	//if ($type == 'application/octet-stream')
    		//	header('Content-Disposition: attachment; filename='.$row['filename']);
    		//else
	 			header("Content-Disposition: inline");
	    	echo file_get_contents(company_path(). "/attachments/".$row['unique_name']);
    		exit();
		}
	}	
}
if (isset($_GET['dl']))
	$download_id = $_GET['dl'];
else
	$download_id = find_submit('download');

if ($download_id != -1)
{
	$row = get_systype_attachment($download_id);
	if ($row['filename'] != "")
	{
		if(in_ajax()) {
			$Ajax->redirect($_SERVER['PHP_SELF'].'?dl='.$download_id);
		} else {
			$type = ($row['filetype']) ? $row['filetype'] : 'application/octet-stream';	
    		header("Content-type: ".$type);
	    	header('Content-Length: '.$row['filesize']);
    		header('Content-Disposition: attachment; filename='.$row['filename']);
    		echo file_get_contents(company_path()."/attachments/".$row['unique_name']);
	    	exit();
		}
	}	
}

$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(800, 500);
page(_($help_context = "Attach Documents"), false, false, "", $js);

simple_page_mode(true);
//----------------------------------------------------------------------------------------
if (isset($_GET['filterType'])) // catch up external links
	$_POST['filterType'] = $_GET['filterType'];
if (isset($_GET['trans_no']))
	$_POST['trans_no'] = $_GET['trans_no'];

if ($Mode == 'ADD_ITEM' || $Mode == 'UPDATE_ITEM')
{
	if ($Mode == 'ADD_ITEM' && (!isset($_FILES['filename']) || $_FILES['filename']['size'] == 0))
		display_error(_("Select attachment file."));
	else {
		//$content = base64_encode(file_get_contents($_FILES['filename']['tmp_name']));
		$tmpname = $_FILES['filename']['tmp_name'];

		$dir =  company_path()."/attachments";
		if (!file_exists($dir))
		{
			mkdir ($dir,0777);
			$index_file = "<?php\nheader(\"Location: ../index.php\");\n?>";
			$fp = fopen($dir."/index.php", "w");
			fwrite($fp, $index_file);
			fclose($fp);
		}

		$filename = basename($_FILES['filename']['name']);
		$filesize = $_FILES['filename']['size'];
		$filetype = $_FILES['filename']['type'];

		// file name compatible with POSIX
		// protect against directory traversal
		if ($Mode == 'UPDATE_ITEM')
		{
		    $row = get_systype_attachment($selected_id);
		    if ($row['filename'] == "")
        		exit();
			$unique_name = $row['unique_name'];
			if ($filename && file_exists($dir."/".$unique_name))
				unlink($dir."/".$unique_name);
		}
		else
			$unique_name = uniqid('');

		//save the file
		move_uploaded_file($tmpname, $dir."/".$unique_name);

		if ($Mode == 'ADD_ITEM')
		{
			add_systype_attachment($_POST['filterType'], $_POST['description'],
				$filename, $unique_name, $filesize, $filetype);
			display_notification(_("Attachment has been inserted.")); 
		}
		else
		{
			update_systype_attachment($selected_id, $_POST['filterType'], $_POST['description'],
				$filename, $unique_name, $filesize, $filetype); 
			display_notification(_("Attachment has been updated.")); 
		}
	}
	refresh_pager('trans_tbl');
	$Ajax->activate('_page_body');
	$Mode = 'RESET';
}

if ($Mode == 'Delete')
{
	$row = get_systype_attachment($selected_id);
	$dir =  company_path()."/attachments";
	if (file_exists($dir."/".$row['unique_name']))
		unlink($dir."/".$row['unique_name']);
	delete_systype_attachment($selected_id);	
	display_notification(_("Attachment has been deleted.")); 
	$Mode = 'RESET';
}

if ($Mode == 'RESET')
{
	unset($_POST['trans_no']);
	unset($_POST['description']);
	$selected_id = -1;
}

function viewing_controls()
{
	global $selected_id;
	
    start_table(TABLESTYLE_NOBORDER);

	start_row();
	systypes_list_cells(_("Type:"), 'filterType', null, true);
	if (list_updated('filterType'))
		$selected_id = -1;;

	end_row();
    end_table(1);

}

function trans_view($trans)
{
	return get_trans_view_str($trans["type_no"], $trans["trans_no"]);
}

function edit_link($row)
{
  	return button('Edit'.$row["id"], _("Edit"), _("Edit"), ICON_EDIT);
}

function view_link($row)
{
  	return button('view'.$row["id"], _("View"), _("View"), ICON_VIEW);
}

function download_link($row)
{
  	return button('download'.$row["id"], _("Download"), _("Download"), ICON_DOWN);
}

function delete_link($row)
{
  	return button('Delete'.$row["id"], _("Delete"), _("Delete"), ICON_DELETE);
}

function display_rows($type)
{
	$sql = get_sql_for_systype_attached_documents($type);
	$cols = array(
	    _("Description") => array('name'=>'description'),
	    _("Filename") => array('name'=>'filename'),
	    _("Size") => array('name'=>'filesize'),
	    _("Filetype") => array('name'=>'filetype'),
	    _("Date Uploaded") => array('name'=>'tran_date', 'type'=>'date'),
	    	array('insert'=>true, 'fun'=>'edit_link'),
	    	array('insert'=>true, 'fun'=>'view_link'),
	    	array('insert'=>true, 'fun'=>'download_link'),
	    	array('insert'=>true, 'fun'=>'delete_link')
	    );	
		$table =& new_db_pager('trans_tbl', $sql, $cols);

		$table->width = "60%";

		display_db_pager($table);
}

//----------------------------------------------------------------------------------------

start_form(true);

display_note(_("Set Attachments for system transactions.Eg, In Export Invoice you can select attachments for futher process"), 0, 1);

viewing_controls(); //select sys type list

display_rows($_POST['filterType']); //list of attachments

br(2);

start_table(TABLESTYLE2);

if ($selected_id != -1)
{
	if ($Mode == 'Edit')
	{
		$row = get_systype_attachment($selected_id);
		$_POST['description']  = $row["description"];
		hidden('unique_name', $row['unique_name']);
	}	
	hidden('selected_id', $selected_id);
}

text_row_ex(_("Description").':', 'description', 40);
file_row(_("Attached File") . ":", 'filename', 'filename');

end_table(1);

submit_add_or_update_center($selected_id == -1, '', 'process');

end_form();

end_page();

?>
