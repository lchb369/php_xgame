<?php
/**
 *	区/服配置
 */
return array
(
	'servers' => array( 
		/**
		 * 测试服配置
		 */
		'test_1' => array(
			'pf' => 'test',
			'sid' => 1,
			'serverName' => '一区 桃园结义',
			'statsName' => '内网测试',
			
			'notice' => 'http://sg.ecngame.com/notice91.html',
			'serverIp' => array(
				'211.144.68.31',
			),
			'clientVersion' => array(
				array(
					'platform' => 'uge',
					'refer' => 100,
					'name' => 'MyHero_V1.0.6',
					'url' => 'http://sg.ecngame.com/download/MyHero_V1.0.6_201308091048_uge.apk',
					'version' => 9,
				),
			),
		),	
	)
);