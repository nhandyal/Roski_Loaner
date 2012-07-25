<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$db = array(
            'host'      =>      'loaner.db.7601663.hostedresource.com',
            'username'  =>      'loaner',
            'password'  =>      'F1ne@rts',
            'database'  =>      'loaner'
        );

$connectionString = mysql_connect("loaner.db.7601663.hostedresource.com", "loaner", "F1ne@rts") or die (">>>".mysql_error());
mysql_select_db("loaner", $connectionString) or die ("---".mysql_error());
?>
