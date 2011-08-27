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
class Mysql extends \PEAR2\DB\Pl2Method\SGDBDriver
{
	public function __construct(\PDO $conn)
	{
		parent::__construct($conn);
	}
  public function __get($val)
  {
    $this->SCHEMA = $val;
	$this->conn->query("use {$this->SCHEMA};");
	return $this;
  }
  public function __call($fct,$values)
  {
	return $this->getSP($fct,$this->specParam($values));
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
	$sqlpl ="call {$this->SCHEMA}.$spname ( {$paramstr});";
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
 		return NULL;
	}
}
