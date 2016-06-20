<?php

/**
 * 客户端连接管理器
 * @author liuchangbing
 *
 */
class ConnManager
{
	/**
	 * cache对象
	 * @var unknown
	 */
	private static $cache;
		
	/**
	 * 连接列表
	 * @var unknown
	 */
	private static $connList;

	/**
	 * cache key
	 * @var unknown
	 */
	private static $cacheKey = 'chart_connList';
	
	/**
	 * 读写锁
	 * @var unknown
	 */
	private static $cacheLock = 'chart_connList_lock';
	
	
	/**
	 * 初始化管理器
	 */
	public static function init()
	{
		self::$cache = new Memcache();
		self::$cache->addserver( '127.0.0.1' , 11211 );
		self::clear();
	}
	
	/**
	 * 添加一个新链接
	 */
	public static function add(  $fd , $fromId  )
	{
		//加锁,如果加锁失败，1秒后自动施放
		if( self::$cache->add( self::$cacheLock , 1 , null , 1 ) == false )
		{
			return false;	
		}
		
		self::$connList = self::$cache->get( self::$cacheKey );
		self::$connList[$fd] = array(
			'fromId' => $fromId,
			'pid' => posix_getpid(),
		);
		self::$cache->set( self::$cacheKey , self::$connList );
		
		//解锁
		self::$cache->delete( self::$cacheLock );
		return true;
		
	}
	
	/**
	 * 移除一个连接
	 */
	public static function remove( $fd )
	{
		self::$connList = self::$cache->get( self::$cacheKey );
		if( self::$connList[$fd] )
		{
			unset( self::$connList[$fd] );
			self::$cache->set( self::$cacheKey, self::$connList );
		}
	}
	
	
	/**
	 * 清空连接
	 */
	public static function clear()
	{
		self::$cache->delete( self::$cacheKey );
	}
	
	
	
	public static function get()
	{
		self::$connList = self::$cache->get( self::$cacheKey );
		foreach ( self::$connList as $fd => $info )
		{
			swoole_server_send( TcpServer::$servHandle , $fd, "xxx" );
		}
		
		print_r( self::$connList  );
	}
	
	
	
	
	
}