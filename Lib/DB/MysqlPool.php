<?php
/**
 * 
 * 数据库连接池
 * @author liuchangbin
 * 
 */
class DB_MysqlPool
{
	/**
	 * @var	MysqlPool
	 */
	protected $dbEngine;
	
	/**
	 * 唯一ID
	 * @var	int
	 */
	protected $uniqueId;
	
	/**
	 * 所有游戏库的配置
	 * @var	array
	 */
	protected static $dbConfigs = null;
	
	
	/**
	 * 游戏数据库引擎
	 * @var	MysqlDb[]
	 */
	protected static $dbEngines = array();
	
	
	protected static $dbObject = null;
	/**
	 * 获取数据库单例
	 * @param	int $userId	用户ID
	 * @return	dbobj
	 */
	public static function & getInstance( $unId = 0 )
	{

		if( self::$dbObject[$unId] == null )
		{
			 self::$dbObject[$unId] = new self( $unId );
		}
		return self::$dbObject[$unId];
	}
	
	/**
	 * 实例化数据库类
	 * @param	int	$userId	用户ID
	 */
	protected function __construct( $unId = 0 )
	{
		$this->uniqueId = $unId;
	}

	
	/**
	 * 数据新增接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function add( $tableName , $value , $condition = array() )
	{	
		$value = $value + $condition;
		$value['uid'] = $this->uniqueId;
		$keys = array_keys( $value );
		$sql = "INSERT INTO `{$tableName}` (`" . implode( "` , `" , $keys ) . "`) VALUES (\"" . implode( '" , "' , $value ) . "\")";
		
		$conditionStr = implode( "_", $condition );
		ObjectStorage::registerSql( $this->uniqueId , "insert" , $tableName ,  $conditionStr  , $sql );
	}
	
	/**
	 * 数据修改接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function update( $tableName , $value , $condition = array() )
	{
		$sql = "UPDATE `{$tableName}` SET ";
		foreach ( $value as $key => $item )
		{
			$sql .= "`{$key}` = '{$item}',";
		}
		
		$sql .= "`uid` = {$this->uniqueId} WHERE `uid` = {$this->uniqueId}";
	
		foreach ( $condition as $key => $item )
		{
			$sql .= " AND `{$key}` = '{$item}'";   
		}

		$conditionStr = implode( "_", $condition );
		ObjectStorage::registerSql( $this->uniqueId , "update" , $tableName , $conditionStr , $sql );
	}
	
	/**
	 * 数据删除接口
	 * @param	string $tableName		数据表名
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function delete( $tableName , $condition = array() )
	{
		$sql = "DELETE FROM `{$tableName}` WHERE `uid` = {$this->uniqueId}";
		foreach ( $condition as $key => $item )
		{
			$sql .= " AND `{$key}` = '{$item}'";   
		}
		$conditionStr = implode( "_", $condition );
		
		ObjectStorage::registerSql( $this->uniqueId , "delete" , $tableName , $conditionStr ,  $sql  );
	
	}
	
	/**
	 * 数据单项查询接口(只能根据用户ID查询)
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @return	array
	 */
	public function find( $tableName )
	{
		$sql = "SELECT * FROM `{$tableName}` WHERE `uid` = {$this->uniqueId} LIMIT 1";
		$result = $this->findQuery( $sql );
		return $result[0];
	}
	
	/**
	 * 数据多项查询接口
	 *
	 * @param	string $tableName		数据表名
	 * @param	array $returnItems		需要的字段
	 * @return	array
	 */
	public function findAll( $tableName , $returnItems )
	{
		$sql = "SELECT * FROM `{$tableName}` WHERE `uid` = {$this->uniqueId}";
		$result = $this->findQuery( $sql );
		return $result;
	}
	

	/**
	 * 执行sql
	 * @param unknown $rawsqls
	 * @param string $cgiServer
	 * @return boolean
	 */
	public function execute( $mutiSqls , $cgiServer = null )
	{
		$config = Common::getConfig( 'mysqlPool' );
		$fastcgiServer = $cgiServer ? $cgiServer : $config['data'][1];	
		if( count($mutiSqls ) == 0 )
			return true;
		
		$strsqls = $this->sqlAssemble( $mutiSqls );
		$url = "$fastcgiServer?mod=execsql&act=direct&sqlnum=".count($mutiSqls);		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Expect:'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $strsqls);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		
		return ($result !== 'Error');
	}
	
	

	public function findQuery( $rawsql , $cgiServer = null )
	{
		$config = Common::getConfig( 'mysqlPool' );
		$fastcgiServer = $cgiServer ? $cgiServer : $config['data'][1];
		$strsql = "sql=".urlencode($rawsql);
	
		$url = "$fastcgiServer?mod=querysql&act=direct";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Expect:'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $strsql);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		if( $result === false )
			return array();
		
		if( $result !== 'NULL' && $result !== 'Error' )
		{
			$result = $this->sqlretParse($result);
		}
		else 
		{
			$result = array();
		}
		return $result;
	}
	
	
	public function sqlAssemble( $mutiSqls )
	{
		$strsqls = array();
		for($i=0;$i<count( $mutiSqls );$i++)
		{
			$strsqls[] = "sql$i=".urlencode( $mutiSqls[$i] );
		}
	
		return implode("&",$strsqls);
	}
	
	
	public  function sqlretParse($sqlret)
	{
		$result = array();
		$lines = explode("\n",$sqlret);
		foreach($lines as $line)
		{
			$columns = explode("\t",$line);
			$result[] = $columns;
		}
	
		return $result;
	}
	
}
