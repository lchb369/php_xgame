<?php

/**
 * 命令脚本入口
 */
error_reporting( E_ALL ^ E_NOTICE );
$time = microtime( true );
define( "_ES_" , true );
define( "ROOT_DIR" , dirname( dirname( dirname( __FILE__ ) ) ) );		#修改成游戏的根目录
define( "CONFIG_DIR" , ROOT_DIR . "/Config" );
define( "MOD_DIR" , ROOT_DIR ."/Model" );
define( "CON_DIR" , ROOT_DIR ."/Controller/" );
define( "TPL_DIR" , ROOT_DIR ."/Tpl/Admin" );
define( "LIB_DIR" , ROOT_DIR ."/Lib" );
include  LIB_DIR .'/Common.php';

function main()
{
	//初始化连接管理器
	Socket_ConnManager::init();
	
	//运行socket服务器
	Socket_Server::run();
}
main();