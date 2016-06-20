<?php
/**
 * 英雄模块
 * @author liuchangbin
 */
class Hero_Exception extends GameException
{
	
	/**
	 * 用户不存在
	 * @var	int
	 */
	const STATUS_OVER_LIMIT = 301;
	
	/**
	 * 武将配置不存在
	 * @var unknown
	 */
	const STATUS_NO_HERO_CONF = 302;
	
	/**
	 * 此武将不能兑换
	 * @var unknown
	 */
	const STATUS_CANNOT_COMPOSITE = 303;
	
	/**
	 * 碎片不足
	 * @var unknown
	 */
	const STATUS_NOT_ENOUGH_CHIPS = 304;
	
	/**
	 * 超过英雄上限
	 * @var unknown
	 */
	const STATUS_OVER_HERO_MAX = 305;
	
	/**
	 * 不存在这个英雄
	 * @var unknown
	 */
	const STATUS_NOT_EXIST_HERO = 306;
	
	/**
	 * 技能等级达到上限
	 * @var unknown
	 */
	const STATUS_SKILL_OVER_MAX_LEVEL = 307;
	
	
	const STATUS_HERO_TEAM_NUM_ERROR = 309;
	
	
	const STATUS_HERO_NOT_IN_TEAM = 310;
}

?>