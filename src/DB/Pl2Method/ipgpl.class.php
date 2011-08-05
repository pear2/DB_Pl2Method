<?
/*
autor: Ivo Nascimento. <ivo.nascimento at ianntech.com.br>
*/
require_once 'ipgtype.class.php';
class ipg extends ipgtype {
  const _INFO = 'I';
  private $SCHEMA = "public";
  private $PDO;
  private $statment;
  private $Info;
  private $SPparamName = Array();
  private $SPparamType = Array();
  public function __construct( Array $Info,$user=null,$password=null ){
	if (isset($Info['DEBUG'])) $this->DEBUG = $Info['DEBUG'];
	if (isset($Info['pdo']))
		$this->PDO = clone($Info['pdo']);
	else if (isset($Info['dsn'])){
		if ($user == null || $password == null)
		 return false;
		else{
		 $e = $this->Connect($Info['dsn'], $user, $password);
		 if ($e instanceof Exception) throw $e;
		}
	}
  }
  private function Connect($dsn,$user, $password){
	try{
	  $this->PDO = new PDO("pgsql:{$dsn}", $user, $password);
	}catch (PDOException $E){
	return $E;
	}
  }
  public function __get($val) {
        $this->SCHEMA = $val;
	$this->PDO->query("set search_path to {$this->SCHEMA};");
	return $this;
}
  public function __call($fct,$values){
	if (isset($values) && $values[0]==self::_INFO)
	return $this->getSPInfo($fct);
	if ( $this->checkParam($fct,$values) )
		return $this->getSP($fct,$this->specParam($values));
	else
		throw new Exception("Tipo dos Argumentos não conferem com parametros esperados.",1000);

  }
  private function specParam(Array $P){
	$retorno = Array();

	foreach ($P as $idx => $param){
		$nvalue = $this->formatValue($param);
		$retorno[]= $nvalue;
	}
	$retorno = implode(',',$retorno);
	return $retorno;
  }
  private function getSP($spname,$paramstr){
	$sqlpl ="select * from {$this->SCHEMA}.$spname ( {$paramstr});";
        $this->statment = $this->PDO->query($sqlpl);
        return $this->statment;
  }
  private function checkParam($fct, $values){
		return $this->validateData($fct, $values, $this->getSPInfo($fct));
  }
 public function getSPInfo($spname){
 	echo "recolhendo dados da stored procedure '$spname'.\n";
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
