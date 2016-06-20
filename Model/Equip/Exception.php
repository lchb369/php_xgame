<?php

/**
 * 英雄模块
 * @author liuchangbin
 */
class Equip_Exception extends GameException
{
	
	/**
	 * 装备不存在
	 * @var	int
	 */
	const STATUS_EQUIP_NOT_EXSIT = 401;
	
	/**
	 * 此装备没有装备在该英雄身上,不用下啦
	 * @var unknown
	 */
	const STATUS_EQUIP_NOT_EQUIPED = 402;
	
	
	/**
	 * 不存在该装备配置
	 * @var unknown
	 */
	const STATUS_CONFIG_NOT_EXSIT = 403;
	
	
	/**
	 * 超过最大强化上限
	 * @var unknown
	 */
	const STATUS_OVER_MAX_LEVEL = 404;
	
	
	const STATUS_CANNOT_WASH = 405;
	
	
	
	
}

?>