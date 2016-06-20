<?php

/**
 * socket事件回调类
 *  //swoole_server_send($serv, $fd, 'Server: '.$data);
	//swoole_server_send($serv, $other_fd, "Server: $data", $other_from_id);
	//swoole_server_close($serv, $fd, $from_id);
	//swoole_server_close($serv, $ohter_fd, $other_from_id);
 * 
 * 
 * @author liuchangbing
 *
 */
class Socket_CallBackEvent
{
	
	/**
	 * Server start
	 * @param unknown $serv
	 */
	public static  function onStartEvent( $serv )
	{
	    echo "Server start\n";
	}
	
	
	/**
	 * Server onShutdown
	 * @param unknown $serv
	 */
	public static function onShutdownEvent( $serv )
	{
		Socket_ConnManager::clear();
	    echo "Server onShutdown\n";
	}
	
	
	/**
	 * 定时器事件
	 * @param unknown $serv
	 * @param unknown $interval
	 */
	public static function onTimerEvent( $serv, $interval )
	{
	    echo "Server Timer Call.Interval=$interval \n";
	}
	
	
	
	/**
	 * 客户端连接断开事件
	 * @param unknown $serv
	 * @param unknown $fd
	 * @param unknown $from_id
	 */
	public static function onCloseEvent( $serv, $fd, $from_id )
	{
		Socket_ConnManager::remove( $fd );
		Socket_ConnManager::get();
		//echo "Client Close.\n";
	}
	
	
	/**
	 * 客户端连接请求
	 * @param unknown $serv
	 * @param unknown $fd
	 * @param unknown $from_id
	 */
	public static function onConnectEvent( $serv , $fd , $from_id )
	{
		$status = Socket_ConnManager::add( $fd , $from_id );
		Socket_ConnManager::get();
		
		//回复客户端，是否连接成功
		if( $status != false )
		{
			swoole_server_send( $serv, $fd, "连接成功" );
		}
		else 
		{
			swoole_server_send( $serv, $fd, "连接失败,请重连" );
		}
		
	}
	
	/**
	 * worker进程启动事件
	 * @param unknown $serv
	 * @param unknown $worker_id
	 */
	public static function onWorkerStartEvent($serv, $worker_id)
	{
		
		echo "WorkerStart[$worker_id]|pid=".posix_getpid().".\n";
	}
	
	
	/**
	 * worker进程停止事件
	 * @param unknown $serv
	 * @param unknown $worker_id
	 */
	public static function onWorkerStopEvent($serv, $worker_id)
	{
		echo "WorkerStop[$worker_id]|pid=".posix_getpid().".\n";
	}
	
	
	/**
	 * 接收消息事件
	 * @param unknown $serv
	 * @param unknown $fd
	 * @param unknown $from_id
	 * @param unknown $data
	 */
	public static function onReceiveEvent($serv, $fd, $from_id, $data)
	{
	   // swoole_server_send($serv, $fd, $data);
		if( empty( $data ) ) 
		{
			return;
		}
		
		
		//解包
		$protoObj = Protocol::getInstance();
		$protoObj->setFd( $fd );
		$status = $protoObj->parse( $data );
		if( $status == true )
		{
			echo "cmd:".$protoObj->getCmd();
			echo "\n";
			echo "uid:".$protoObj->getUid();
			echo "\n";
			echo "sid:".$protoObj->getSid();
			echo "\n";
			print_r( $protoObj->getParams() );
			echo "\n";
			
			//$protoObj->setFd($fd);
			//$protoObj = $this->route( $protoObj );
			//swoole_server_send( $serv, $fd, $protoObj->getData());
		}
		
	}
	
	
	
	
	public static function onMasterCloseEvent($serv,$fd,$from_id)
	{
		
	    //echo "Client Close.PID=".posix_getpid().PHP_EOL;
	}
	
	
	
	
	public static function onMasterConnectEvent($serv,$fd,$from_id)
	{
	    echo "Client Connect.PID=".posix_getpid().PHP_EOL;
	}


	private function route( $reqProto )
        {
        	try
		{
            		//Core_Route::route( $reqProto );
        	}
		catch ( Exception $e )
		{
            		//$server->display( $e->getMessage() );
        	}
        	return $reqProto;
        }
}
?>
