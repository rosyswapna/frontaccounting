<?php

/* List of installed additional extensions. If extensions are added to the list manually
	make sure they have unique and so far never used extension_ids as a keys,
	and $next_extension_id is also updated. More about format of this file yo will find in 
	FA extension system documentation.
*/

$next_extension_id = 9; // unique id for next installed extension

$installed_extensions = array (
  4 => 
  array (
    'package' => 'salescontainer',
    'name' => 'salescontainer',
    'version' => 'zz-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/salescontainer',
    'active' => false,
  ),
  7 => 
  array (
    'package' => 'export_invoice',
    'name' => 'export_invoice',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/export_invoice',
    'active' => false,
  ),
  8 => 
  array (
    'package' => 'systype_attachment',
    'name' => 'systype_attachment',
    'version' => '-',
    'available' => '',
    'type' => 'extension',
    'path' => 'modules/systype_attachment',
    'active' => false,
  ),
);
?>