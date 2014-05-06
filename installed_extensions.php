<?php

/* List of installed additional extensions. If extensions are added to the list manually
	make sure they have unique and so far never used extension_ids as a keys,
	and $next_extension_id is also updated. More about format of this file yo will find in 
	FA extension system documentation.
*/

$next_extension_id = 7; // unique id for next installed extension

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
  5 => 
  array (
    'name' => 'Company Dashboard',
    'package' => 'dashboard',
    'version' => '2.3.15-5',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/dashboard',
  ),
  6 => 
  array (
    'name' => 'Asset register',
    'package' => 'asset_register',
    'version' => '2.3.3-9',
    'type' => 'extension',
    'active' => false,
    'path' => 'modules/asset_register',
  ),
);
?>