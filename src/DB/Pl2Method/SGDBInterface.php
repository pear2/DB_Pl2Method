<?php
/**
 * PEAR2\DATABASE\Pl2Method\SGDBInterface
 *
 * PHP version 5
 *
 * @category  DB
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo Nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   SVN: $Id$
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
 namespace PEAR2\DB\Pl2Method;

/**
 * SGDBInterface class
 *
 * @category  DB
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo Nascimento <handle@php.net>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
interface SGDBInterface
{
    /**
    * method used to set schema in SGDB
    *
    * @param $string $val the name of parameter 
    *
    * @return void
    * @access public
    */
    public function __get($val);
    
    /**
    * mapping a php method to getSPInfo/SQL stored procedure method
    *
    * @param String $fct name of the function 
    * @param Array  $values  parameters to the function
    *
    * @return PDOStatment 
    * @access public
    */
    public function __call($fct,$values);
    
    /**
    * execute the procedure in database and return a PDOStatement
    *
    * @param String $spname the name of stored procedure
    *
    * @return PDOStatment
    * @access public
    */
    public function getSPInfo($spname);
}
