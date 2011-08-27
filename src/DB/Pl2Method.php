<?php
/**
 * PEAR2\DATABASE\Pl2Method\Pl2Method
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
 * Main class for PEAR2_DATABASE_Pl2Method
 *
 * @category  Database
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo Nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
class Pl2Method
{
    /**
    * return the PL instance to pdo connection
    *
    * @param PDO $conn the PDO instance
    *
    * @return \PEAR2\DB\Pl2Method\SGDBDriver the instance of a driver
    * @access public
    */
    public static function getInstance(\PDO $conn) 
    {
        return self::getDriverInstance($conn);
    }
    /**
     * the Strategy definer
     *
     * @param PDO $conn the instance of PDO
     *
     * @return SGDBDriver
     * @access Private
     * @throws \PEAR2\DB\Pl2Method\Exception
     */
    private static function getDriverInstance(\PDO $conn)
    {
        $drivername = ucfirst($conn->getAttribute(\PDO::ATTR_DRIVER_NAME));
        $driverName = "\\PEAR2\\DB\\Pl2Method\\Driver\\".$drivername);
        try{
            $driver = New $driverName($conn);
        }catch(ErrorException $E){
            throw PEAR2\DB\Pl2Method\Exception::unsupportedDriver($driverName);
        }
        return $driver;
    }
}
