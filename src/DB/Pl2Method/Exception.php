<?php

/**
 * PHP version 5
 *
 * @category  Console
 * @package   PEAR2\DB\Pl2Method
 * @author    Ivo Nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   SVN: $Id$
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 * @since     File available since release 0.1.0
 */
namespace PEAR2\DB\Pl2Method;

/**
 * Class for exceptions raised by the PEAR2\DB\Pl2Method package.
 *
 * @category  DB
 * @package   PEAR2\DB\Pl2Method
 * @author    Ivo Nascimento <ivo@o8o.com.br>
 * @copyright 2011 Ivo Nascimento
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   Release: @package_version@
 * @link      http://svn.php.net/repository/pear2/PEAR2_DATABASE_Pl2Method
 * @since     File available since release 0.1.0
 */

class Exception extends \Exception
{

    // unsupportedDriver() {{{
    /**
     * Launch a expcetion because the PDO Connection has not support yet
     *
     * @param string $name name of SGDB System
     *
     * @return PEAR2\DB\Pl2Method\Exception exception instance
     */
    public static function unsupportedDriver($name)
    {
        return New Exception("The {$name} SGDB has no support yet");
    }
    // }}}

    // notFoundSP() {{{

    /**
     * Launch a expcetion because the Stored Procedure dont exist
     *
     * @param string $spname stored Procedure Name
     *
     * @return PEAR2\DB\Pl2Method\Exception exception instance
     */
    public static function notFoundSP($spname)
    {
        return New Exception("The Stored Procedure {$spname} was not found in ");
    }
    // }}}
}