<?php

/**
 * 英雄模块
 * @author liuchangbin
 */
class Chapter_Exception extends GameException
{
	

	/**
	 * 不存在该装备配置
	 * @var unknown
	 */
	const STATUS_CONFIG_NOT_EXSIT = 501;
	
	
	/**
	 * 不能解锁此关卡
	 * @var unknown
	 */
	const CANNOT_UNLOCK_THIS_SECTION = 502;
	
	
}

?>