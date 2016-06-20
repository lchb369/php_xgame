<?php
return array(
		
		//游戏配置路径
		'gameConfigPath' => '/data/projmng/config/phproot/20131031/',
		
		//redis,memcache可选
		'cacheClassName' => 'Cache_Redis',
		
		'dbClassName' => 'DB_MysqlPool',
		
		//memcache配置
		'memcache' => array(
			'data' => array(
				array(	"host" => "127.0.0.1" , "port" => 11211 ) ,
				array(	"host" => "127.0.0.1" , "port" => 11212 ) ,
			),
		),
		
		//redis配置
		'redis' => array(
			'data' => array(
                'pconnect'=>true,
                'host'=>'127.0.0.1',
                'port'=> 6379,
                'timeout'=> 5
			),
		),
		 
		//mysql连接池配置
		'mysqlPool' => array(
			'data' => array(
				//库1
				1 => "http://127.0.0.1:32100/services/worker.fcgi",
			),
		),
		
		
		//Mysql数据库配置(在使用MYSQL数据库时配置)
		'mysqlDb' => array
		(
			'data' => array( 'host' => '127.0.0.1' , 'port' => '3306' , 'user' => 'root' , 'passwd' => 'mysqlpandora' , 'name' => 'xproject' ) ,
		),
		
		
		//mongodb配置
		'mongoDb' => array(
				'statsDB' => array( "host" => '192.168.1.3:27017', "dbname" => "myheroStatsDBTest" ),
		),
		
		'xhprof' => array(
		    'isOpen' => true ,     //是否使用执行效率检查；true => 使用；false => 不使用
			'logDir' => 'xgame' ,
		),
);