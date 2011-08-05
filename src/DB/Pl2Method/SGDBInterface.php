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
namespace PEAR2\DB\Pl2Method;
interface SGDBInterface
{
	public function __get($val);
	public function __call($fct,$values);
	public function getSPInfo($spname);
}
