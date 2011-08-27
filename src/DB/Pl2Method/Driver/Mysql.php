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
 namespace PEAR2\DB\Pl2Method\Driver;

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
class Mysql extends \PEAR2\DB\Pl2Method\SGDBDriver
{
    /**
    * construct the Driver
    *
    * @param PDO $conn the Pdo connection instance
    *
    * @return void
    * @access public
    */
    public function __construct(\PDO $conn)
    {
        parent::__construct($conn);
    }
    /**
    * method used to set schema in SGDB
    *
    * @param $string $val the name of parameter 
    *
    * @return void
    * @access public
    */
    public function __get($val)
    {
        $this->SCHEMA = $val;
        $this->conn->query("use {$this->SCHEMA};");
        return $this;
    }
    /**
    * mapping a php method to getSPInfo/SQL stored procedure method
    *
    * @param String $fct name of the function 
    * @param Array  $values  parameters to the function
    *
    * @return PDOStatment 
    * @access public
    */
    public function __call($fct,$values)
    {
        return $this->getSP($fct, $this->specParam($values));
    }
    /**
    * look the parametros to Stored procedure
    * and format it
    *
    * @param Array $P the param array
    *
    * @return string list to composite the sql query string
    * @access public
    */
    private function specParam(Array $P)
    {
        $retorno = Array();
        foreach ($P as $idx => $param) {
            $nvalue = $this->formatValue($param);
            $retorno[]= $nvalue;
        }
        $retorno = implode(',', $retorno);
        return $retorno;
    }
    /**
    * execute the procedure in database and return a PDOStatement
    *
    * @param String $spname the name of stored procedure
    * @param String $paramstr the result from specParam
    *
    * @return PDOStatment
    * @access public
    */
    private function getSP($spname,$paramstr)
    {
        $sqlpl ="call {$this->SCHEMA}.$spname ( {$paramstr});";
        $this->statment = $this->conn->query($sqlpl);
        if (!$this->statment) {
            throw \PEAR2\DB\Pl2Method\Exception::notFoundSP($spname);
        }
        return $this->statment;
    }
    /**
    * verify if parameters are ok to function
    * 
    * @param String $fct the stored procedure name
    * @param Array $values the parameters to the SP
    *
    * @return boolean;
    * @access public
    */
    private function checkParam($fct, $values)
    {
        return $this->validateData($fct, $values, $this->getSPInfo($fct));
    }
    /**
     * retrieve the Stored procedure metadata
     *
     * @param String $spname the name of stored procedure to create 
     *
     * @return PDOStatement
     * @access public 
     **/
    public function getSPInfo($spname)
    {
        return null;
    }
}
