<?php

/**
 * 英雄模块
 * @author liuchangbin
 */
class Item_Exception extends GameException
{
	
	/**
	 * 不存在道具配置
	 * @var unknown
	 */
	const STATUS_ITEM_CONF_NOT_EXIST = 701;
	
	/**
	 * 此道具无法使用
	 * @var unknown
	 */
	const STATUS_ITEM_CANNOT_USE = 702;
	
	
	/**
	 * 道具不够
	 * @var unknown
	 */
	const STATUS_ITEM_NOT_ENOUGH = 703;
	
}

?>