<?php

/*Connection Information for the database
$def_coy - the default company that is pre-selected on login

'host' - the computer ip address or name where the database is. The default is 'localhost' assuming that the web server is also the sql server.

'dbuser' - the user name under which the company database should be accessed.
  NB it is not secure to use root as the dbuser with no password - a user with appropriate privileges must be set up.

'dbpassword' - the password required for the dbuser to authorise the above database user.

'dbname' - the name of the database as defined in the RDMS being used. Typically RDMS allow many databases to be maintained under the same server.
'tbpref' - prefix on table names, or '' if not used. Always use non-empty prefixes if multiply company use the same database.
*/


$def_coy = 0;

$tb_pref_counter = 3;

$db_connections = array (
  0 => 
  array (
    'name' => 'METALS &amp; MINERALS FZE',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => '',
    'dbname' => 'frontaccounting',
    'tbpref' => '',
  ),
  1 => 
  array (
    'name' => 'ACUBE1',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => '',
    'dbname' => 'delete_fa',
    'tbpref' => '',
  ),
  2 => 
  array (
    'name' => 'acube innovations1',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => '',
    'dbname' => 'delete_fa1',
    'tbpref' => '1_',
  ),
  3 => 
  array (
    'name' => 'ACUBE2',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => '',
    'dbname' => 'delete_fa3',
    'tbpref' => '2_',
  ),
);
?>