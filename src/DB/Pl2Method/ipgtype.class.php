<?
abstract class ipgtype{
/*
  this class is to manipulate pg types. This recognize this types and transform them into php=>pg and pg=>php.
  supported type's:
  Numeric
  Character
  Boolean
  Date/time
*/

const _CHARACTER	= 0x002;
const _BOOLEAN		= 0x003;
const _DATE		= 0x004;
const _TIME		= 0x005;
const _INT		= 0x007;
const _FLOAT		= 0x008;
protected $DEBUG = false;
protected $DataT = Array(0x002=>array('character varying','character','text'),
					   0x008=>array('double precision'),
					   0x005=>array('time without time zone','time with time zone'),
					   0x007=>array('integer', 'smallint','bigint')
					   );
  private function getType($val){
	if(is_int($val))
		return self::_INT;
	else if (is_float($val))
		return self::_FLOAT;
	else if (self::isDate($val))
		return self::_DATE;
	else if (self::isTime($val))
		return self::_TIME;
	else if (self::isDateTime($val))
		return self::_DATETIME;
	else if  (is_string($val))
		return self::_CHARACTER;
  }
  private function isDate($value){
	return (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) ;
  }
  private function isTime($value){
        return (strtotime($value)!==false) ;
  }
  private function isDateTime(){

  }
 protected function formatValue($value){

	switch($this->getType($value)){
	  case self::_CHARACTER :
	   $retorno = $this->getCharacter($value);
	   $typestr = $this->DataT[self::_CHARACTER];
	   break;
	  case self::_BOOLEAN :
	   $retorno = $value;
	   $typestr = $this->DataT[self::_BOOLEAN];
  	   break;
	 case self::_INT :
	   $retorno = $value;
	   $typestr = $this->DataT[self::_INT];
	   break;
	 case self::_FLOAT :
	   $retorno = $value;
	   $typestr = $this->DataT[self::_FLOAT];
	   break;
	 case self::_DATE :
	    $retorno = "'".$value."'";
	    $typestr = $this->DataT[self::_DATE];
	  break;
	 case self::_TIME :
	    $retorno =  "'".$value."'";
	    $typestr = $this->DataT[self::_TIME];
	  break;
	 default:
	  $retorno =  "'".$value."'";
	  $typestr = "not Recognized";
	  break;
	}
	if ($this->DEBUG) printf( "valor: %-20s\t=>\t%20.20s\t\t%-10s\n",$value,$retorno,implode("|",$typestr) );
	return $retorno;
 }
 private function getCharacter($value){
	return "'".addslashes($value)."'";
 }

 private function genSignature_Args($sText){
	$a = Array();
	$t = Array();
 	$sText = explode(',',$sText);
	foreach ($sText as $param){
		$d= explode(" ",trim($param),2);
		array_push($a,$d[0]);
		array_push($t,$d[1]);
	}
	return Array('arg_n'=>$a,'arg_t'=>$t);
 }
 private function validadeArg($arg, $expectT){

 }
 protected function validateData($fctname, $args, $descInfo){
		$info = $this->genSignature_Args($descInfo[3]);
		foreach($args as $k => $arg){
			echo "aqui vai valor: {$info['arg_t'][$k]} : ".$this->getType($arg)."\n";
			if( !in_array( $info['arg_t'][$k], $this->DataT[$this->getType($arg)])){
				echo "invalid data";
				return false;
			}
		}
			echo "valido daodos \n";
			return true;
 }
}
?>
