<?php

class Common
{

	/**
	 * 获取配置文件
	 * @param unknown $name
	 * @param string $configType
	 * @return Ambigous <>|Ambigous <multitype:, multitype:NULL >
	 */
	public static function & getConfig( $key = ''  )
	{
		static $config = array();
		if( !$config )
		{
			$sysConfig = self::getSysConfig();
			if( $sysConfig )
			{
				$config = self::getConfigFile( $sysConfig );
			}
			$config = array_merge_recursive( $config , self::getConfigFile( "Server" ) );
		}
	
		if( !isset( $config[$key] ) )
		{
			$config[$key] = self::getConfigFile( $key , $config['gameConfigPath'] );
		}

	
		if( $key )
			return $config[$key];
	
		return $config;
	}
	
	/**
	 * 获取系统配置文件
	 * @return string
	 */
	protected static function getSysConfig()
	{
		global $gPlatform;
		global $gServerId;
		
		if( !$gPlatform || !$gServerId )
		{
			return '';
		}
		$sysConfig = ucfirst( $gPlatform )."_".$gServerId."_Config";
		return $sysConfig;
	}
	
	
	/**
	 * 加载配置文件
	 * @param unknown $configKey
	 * @return multitype:
	 */
	protected static function & getConfigFile( $configKey , $path = '' )
	{
		$config = array();
		if( $path )
		{
			if( file_exists( $path . "/{$configKey}.php" ) )
			{
				$config = require( $path . "/{$configKey}.php" );
			}
			return $config;
		}
		
        if( file_exists( CONFIG_DIR . "/Platform/{$configKey}.php" ) )
		{
			$config = require( CONFIG_DIR . "/Platform/{$configKey}.php" );
		}
		else if( file_exists( CONFIG_DIR . "/Globals/{$configKey}.php" ) )
		{
			$config = include( CONFIG_DIR . "/Globals/{$configKey}.php" );
		}
		return $config;
	}
	
	/**
	 * 获取缓存对象
	 */
	public static function & getCache( $key = null )
	{
		static $cache = null;
		if( $cache == null )
		{
			$config = Common::getConfig( 'redis' );
			$conf = $config['data'];
			$cache = new Cache_Redis( $conf );
			
			//$cache = new Cache_Apc();
			//$cache = new Cache_Memcache();
		}
		return $cache;
	}
	
	/**
	 * @param int $unId 唯一ID/用户ID
	 * @param string $key 按业务分库，
	 * 不同的业务可能用到的是不用的存储库
	 * 比如游戏数据用mysql gamedb , 用户库用userdb
	 * 统计库用mongo
	 */
	public static function & getDB(  $unId = 0 , $key = null )
	{
		$dbClassName = Common::getConfig( 'dbClassName' );
	
		switch ( $dbClassName )
		{
			case 'DB_MysqlPool':
				return DB_MysqlPool::getInstance( $unId );
			break;
			//case 'MysqlProxy':
			//	return MysqlDber::getInstance();
		}
		throw new GameException( 101 ,__FILE__.' in line '.__LINE__ );		
	}
}


/**
 * 自动加载类文件
 * @param string $classname
 */
function __autoload( $classname ) 
{
	$classname = str_replace( '_' , '/' , $classname );
			

	//在模块文件夹搜索
	if( file_exists( LIB_DIR . "/{$classname}.php" ) )
	{
		include_once( LIB_DIR . "/{$classname}.php" );
		return ;
	}
	
	
	//在模块文件夹搜索
	if( file_exists( MOD_DIR . "/{$classname}.php" ) )
	{
		include_once( MOD_DIR . "/{$classname}.php" );
		return ;
	}
	
	
	//在控制器文件夹搜索
	if( file_exists( CON_DIR . "/{$classname}.php" ) )
	{
		include_once( CON_DIR . "/{$classname}.php" );
		return ;
	}
	
	
	//在模块文件夹搜索
	if( file_exists( ROOT_DIR . "/{$classname}.php" ) )
	{
		include_once( ROOT_DIR . "/{$classname}.php" );
		return ;
	}
}
	
	
	

?>