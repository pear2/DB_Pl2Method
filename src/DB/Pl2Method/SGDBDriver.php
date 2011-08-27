<?php
/**
 * PEAR2\DATABASE\Pl2Method\SGDBDriver
 *
 * PHP version 5
 *
 * @category  DB
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   SVN: $Id$
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
 namespace PEAR2\DB\Pl2Method;

/**
 * SGDBDriver class
 *
 * @category  DB
 * @package   PEAR2_DATABASE_Pl2Method
 * @author    Ivo Nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 */
abstract class SGDBDriver
{
    /**
    * the PDO connection
    * @var PDOConnection $conn
    */
    protected $conn;

    /**
    * construct the driver and set the connection
    * 
    * @param PDO $conn the pdo
    *
    * @access public
    * @return void
    */
    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }
}
