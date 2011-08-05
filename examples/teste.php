<?php
include_once '../src/DB/Pl2method.php';
include_once '../src/DB/Pl2Method/SGDBDriver.php';
include_once '../src/DB/Pl2Method/SGDBInterface.php';
include_once '../src/DB/Pl2Method/Driver/Pgsql.php';
error_reporting(E_ALL);
/*
 * For this examples I use a Database Called artigo
 * and create these Stored Procedure
  create or replace function teste() returns varchar(10) as
	$$
	begin
	return 'teste';
	end;
	$$
	language 'plpgsql';

	select * from public.teste();
 *
 */

$a =  new PDO("pgsql:host=localhost;port=5432;dbname=artigo;user=postgres;password=root");
$pl2method = \PEAR2\DB\Pl2Method\Pl2Method::getInstance($a);
// here you have a PDOStatement
$returned = $pl2method->public->teste();
var_dump( $returned );
// You can FetchAll()
var_dump($returned->FetchAll());




?>