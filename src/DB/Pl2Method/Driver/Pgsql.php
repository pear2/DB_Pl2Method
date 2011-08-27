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
class Pgsql extends \PEAR2\DB\Pl2Method\SGDBDriver
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
        $this->conn->query("set search_path to {$this->SCHEMA};");
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
        $sqlpl ="select * from {$this->SCHEMA}.$spname ( {$paramstr});";
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
                pg_catalog.generate_series(1, 
                    pg_catalog.array_upper(p.proallargtypes, 1)) AS s(i)
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
                pg_catalog.generate_series(0, 
                    pg_catalog.array_upper(p.proargtypes, 1)) AS s(i)
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
            $this->Info = $this->PDO->query($sqlstr);
            return $this->Info->fetch(PDO::FETCH_NUM);
    }
}
