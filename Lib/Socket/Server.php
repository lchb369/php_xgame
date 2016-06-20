<?php
class Socket_Server
{
	/**
	 * socket连接句柄
	 * @var unknown
	 */
	public static $servHandle;	
	
	/**
	 * 回调类
	 * @var unknown
	 */
	private static $callbackClass = 'CallBackEvent';
	
	
	/**
	 * 启动参数设置
	 * @var unknown
	 */
	private static $config = array(
		'timeout' => 2.5,  //select and epoll_wait timeout.
		'poll_thread_num' => 2, //reactor thread num
		'writer_num' => 2,     //writer thread num
		'worker_num' => 4,    //worker process num
		'backlog' => 128,   //listen backlog
		'max_request' => 1000,
	);
	
	
	/**
	 * 设置回调类
	 * @param unknown $clas
	 */
	public static function setCallbakClass( $className )
	{
		self::$callbackClass = $className;
	}
	
	
	/**
	 * 运行
	 * @param unknown $config
	 */
	public static function run( $config = array() )
	{
		if( $config )
		{
			self::$config = $config;
		}
		
		self::$servHandle = swoole_server_create( "0.0.0.0", 4477, SWOOLE_PROCESS, SWOOLE_SOCK_TCP );
		swoole_server_set( self::$servHandle , self::$config );
		
		swoole_server_handler( self::$servHandle , 'onStart', array( self::$callbackClass , 'onStartEvent' ));
		swoole_server_handler( self::$servHandle, 'onConnect', array(  self::$callbackClass  , 'onConnectEvent' ) );
		swoole_server_handler( self::$servHandle, 'onReceive',  array( self::$callbackClass , 'onReceiveEvent' ));
		swoole_server_handler( self::$servHandle, 'onClose',  array( self::$callbackClass , 'onCloseEvent' ) );
		swoole_server_handler( self::$servHandle, 'onShutdown', array( self::$callbackClass , 'onShutdownEvent' ));
		swoole_server_handler( self::$servHandle, 'onTimer', array( self::$callbackClass , 'onTimerEvent' ) );
		swoole_server_handler( self::$servHandle, 'onWorkerStart', array( self::$callbackClass , 'onWorkerStartEvent' ));
		swoole_server_handler( self::$servHandle, 'onWorkerStop',  array( self::$callbackClass , 'onWorkerStopEvent' ) );
		swoole_server_handler( self::$servHandle, 'onMasterConnect',  array( self::$callbackClass , 'onMasterConnectEvent' ));
		swoole_server_handler( self::$servHandle, 'onMasterClose', array( self::$callbackClass , 'onMasterCloseEvent' ));
		
		#swoole_server_addtimer($serv, 2);
		#swoole_server_addtimer($serv, 10);
		swoole_server_start( self::$servHandle );
		
	} 
	
	
	
	public static function shutDown()
	{	
		
		
	}
	
	
	
	
}


?>