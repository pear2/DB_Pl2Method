<?php
/**
 * PEAR2\DATABASE\Pl2Method\Driver\Pgsql
 *
 * PHP version 5
 *
 * @category  DB
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo nascimento <ivo@o8o.com.br>
 * @copyright 2011 Your Name
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   SVN: $Id$
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */

/**
 * PostgreSQL Driver class for PEAR2_DATABASE_Pl2Method
 *
 * @category  Database
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo Nascimento <handle@php.net>
 * @copyright 2011 Your Name
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
namespace PEAR2\DB\Pl2Method\Driver;
class Pgsql extends \PEAR2\DB\Pl2Method\SGDBDriver
{
	public function __construct(\PDO $conn)
	{
		parent::__construct($conn);
	}
  public function __get($val)
  {
    $this->SCHEMA = $val;
	$this->conn->query("set search_path to {$this->SCHEMA};");
	return $this;
  }
  public function __call($fct,$values)
  {
/*	if (isset($values) && $values[0]==self::_INFO)
	return $this->getSPInfo($fct);
	if ( $this->checkParam($fct,$values) )
*/		return $this->getSP($fct,$this->specParam($values));
//	else
//		throw new Exception("Tipo dos Argumentos não conferem com parametros esperados.",1000);
  }
  private function specParam(Array $P)
  {
	$retorno = Array();
	foreach ($P as $idx => $param)
	{
		$nvalue = $this->formatValue($param);
		$retorno[]= $nvalue;
	}
	$retorno = implode(',',$retorno);
	return $retorno;
  }
  private function getSP($spname,$paramstr)
  {
	$sqlpl ="select * from {$this->SCHEMA}.$spname ( {$paramstr});";
    $this->statment = $this->conn->query($sqlpl);
    if (!$this->statment)
    	throw \PEAR2\DB\Pl2Method\Exception::notFoundSP( $spname);
    return $this->statment;
  }
  private function checkParam($fct, $values)
  {
	return $this->validateData($fct, $values, $this->getSPInfo($fct));
  }
 public function getSPInfo($spname)
 {
	$sqlstr = "SELECT n.nspname as \"Schema\",
	  p.proname as \"Name\",
	  CASE WHEN p.proretset THEN 'setof ' ELSE '' END ||
	  pg_catalog.format_type(p.prorettype, NULL) as \"Result data type\",
	  CASE WHEN proallargtypes IS NOT NULL THEN
	    pg_catalog.array_to_string(ARRAY(
	      SELECT
	        CASE
	          WHEN p.proargmodes[s.i] = 'i' THEN ''
	          WHEN p.proargmodes[s.i] = 'o' THEN 'OUT '
	          WHEN p.proargmodes[s.i] = 'b' THEN 'INOUT '
	        END ||
	        CASE
	          WHEN COALESCE(p.proargnames[s.i], '') = '' THEN ''
	          ELSE p.proargnames[s.i] || ' '
	        END ||
	        pg_catalog.format_type(p.proallargtypes[s.i], NULL)
	      FROM
	        pg_catalog.generate_series(1, pg_catalog.array_upper(p.proallargtypes, 1)) AS s(i)
	    ), ', ')
	  ELSE
	    pg_catalog.array_to_string(ARRAY(
	      SELECT
	        CASE
	          WHEN COALESCE(p.proargnames[s.i+1], '') = '' THEN ''
	          ELSE p.proargnames[s.i+1] || ' '
	          END ||
	        pg_catalog.format_type(p.proargtypes[s.i], NULL)
	      FROM
	        pg_catalog.generate_series(0, pg_catalog.array_upper(p.proargtypes, 1)) AS s(i)
	    ), ', ')
	  END AS \"Argument data types\"
	FROM pg_catalog.pg_proc p
	     LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace
	WHERE p.prorettype <> 'pg_catalog.cstring'::pg_catalog.regtype
	      AND (p.proargtypes[0] IS NULL
	      OR   p.proargtypes[0] <> 'pg_catalog.cstring'::pg_catalog.regtype)
	      AND NOT p.proisagg
	  AND p.proname ~ '^({$spname})$'
	  AND pg_catalog.pg_function_is_visible(p.oid)
	ORDER BY 1, 2, 3, 4;";
		$this->Info = $this->PDO->query($sqlstr);//"select * from soma2(10,20);");
		return $this->Info->fetch(PDO::FETCH_NUM);
	}
}
