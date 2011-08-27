<?php
include_once '../src/DB/Pl2method.php';
include_once '../src/DB/Pl2method/Exception.php';
include_once '../src/DB/Pl2Method/SGDBDriver.php';
include_once '../src/DB/Pl2Method/SGDBInterface.php';
include_once '../src/DB/Pl2Method/Driver/Mysql.php';
error_reporting(E_ALL);
/*
 * For this examples I use a Database Called test
 * and create these Stored Procedure
 delimiter $$
 mysql> create Procedure teste()
     -> begin
     -> select 1+1;
     -> end $$ delimiter;
     
	call test.teste();
 *
 */
$m = new PDO("mysql:host=127.0.0.1; dbname=test", "root", "mysql");
$pl2method = \PEAR2\DB\Pl2Method\Pl2Method::getInstance($m);
// here you have a PDOStatement
$returned = $pl2method->test->teste_();
var_dump( $returned );
// You can FetchAll()
var_dump($returned->FetchAll());




?>