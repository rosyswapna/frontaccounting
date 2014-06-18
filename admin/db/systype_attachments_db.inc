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

//----------------------------------------------------------------------------------------

function add_systype_attachment($filterType, $description,
	$filename, $unique_name, $filesize, $filetype)
{
	$sql = "INSERT INTO ".TB_PREF."systype_attachments (type_no, description, filename, unique_name,
		filesize, filetype) VALUES (".db_escape($filterType).","
		.db_escape($description).", "
		.db_escape($filename).", ".db_escape($unique_name).", ".db_escape($filesize)
		.", ".db_escape($filetype).")";
	db_query($sql, "Attachment could not be inserted");		
}
//----------------------------------------------------------------------------------------

function update_systype_attachment($selected_id, $filterType, $description,
	$filename, $unique_name, $filesize, $filetype)
{
	$sql = "UPDATE ".TB_PREF."systype_attachments SET
		type_no=".db_escape($filterType).",";
		
	if ($filename != "")
	{
		$sql .= "filename=".db_escape($filename).",
		unique_name=".db_escape($unique_name).",
		filesize=".db_escape($filesize).",
		filetype=".db_escape($filetype).",";
	}	
	$sql .= "description=".db_escape($description)." ";
	$sql .= " WHERE id=".db_escape($selected_id);
	db_query($sql, "Attachment could not be updated");		
}

//----------------------------------------------------------------------------------------

function delete_systype_attachment($id)
{
	$sql = "DELETE FROM ".TB_PREF."systype_attachments WHERE id = ".db_escape($id);
	db_query($sql, "Could not delete attachment");
}

//----------------------------------------------------------------------------------------


function get_systype_attached_documents($type)
{
	$sql = "SELECT * FROM ".TB_PREF."systype_attachments WHERE type_no=".db_escape($type)
	." ORDER BY id DESC";
	return db_query($sql, "Could not retrieve attachments");
}

function get_systype_attachment($id)
{
	$sql = "SELECT * FROM ".TB_PREF."systype_attachments WHERE id=".db_escape($id);
	$result = db_query($sql, "Could not retrieve attachments");
	return db_fetch($result);
}

function has_systype_attachment($type)
{
	$sql = "SELECT DISTINCT * FROM ".TB_PREF."systype_attachments WHERE type_no=".db_escape($type);
	$result = db_query($sql, "Could not retrieve attachments");
	$myrow = db_fetch($result);
	if ($myrow === false)
		return 0;
	else
		return $myrow['id'];
}

function get_sql_for_systype_attached_documents($type)
{
	return "SELECT description, filename, filesize, filetype, id, type_no FROM ".TB_PREF."systype_attachments WHERE type_no=".db_escape($type)
	." ORDER BY id DESC";
}

function get_systype_attachments($ids = array())
{
	$attachments = array();

	foreach ($ids as $id) {
		$attachments[] = get_systype_attachment($id);
	}

	return $attachments;
}

//entry into attachments table
function add_invoice_attachments($invoice_no = false, $attachments = array())
{
	if($invoice_no){

		$date = date2sql(Today());

		if(count($attachments) > 0){

			$sql = "INSERT INTO ".TB_PREF."attachments (type_no, trans_no, description, filename, unique_name,filesize, filetype, tran_date) VALUES";

			foreach ($attachments as $attachment) {
				$sql .= "(".db_escape($attachment['type_no']).","
						.db_escape($invoice_no).",".db_escape($attachment['description']).", "
						.db_escape($attachment['filename']).", ".db_escape($attachment['unique_name']).", ".db_escape($attachment['filesize'])
						.", ".db_escape($attachment['filetype']).", '$date'),";
			}

			$sql = substr($sql, 0,-1);

			
			db_query($sql, "Attachments could not be inserted");	

		}
		
	}
	
		
}


?>