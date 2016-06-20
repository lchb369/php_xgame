<?php

/**
 * 装备模块
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class Equip_Model
{
	
	protected $userId;
	/**
	 * 单例对象
	 * @var	User_Info[]
	 */
	protected static $singletonObjects;

	
	//装备类型武器
	const EQUIP_TYPE_WEAPON = 1;
	
	//装备类型防具
	const EQUIP_TYPE_ARMOR = 2;
	
	//装务类型饰品
	const EQUIP_TYPE_ACC = 3;
	
	//洗练类型:金币
	const WASH_TYPE_GOLD = 1;
	
	//洗练类型:元宝
	const WASH_TYPE_COIN = 2;
	
	
	/**
	 * 结构化对象
	 * @param	int $userId	用户ID
	 * @param	boolean $lock
	 */
	public function __construct( $userId )
	{
		$this->userId = $userId;
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	User_Info
	 */
	public static function & getInstance( $userId )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId );
		}
		return self::$singletonObjects[$userId];
	}
	

	/**
	 * 增加一件装备
	 * @param unknown $heroType
	 */
	public function addEquip( $equipId )
	{
		return Data_Equip_Model::getInstance( $this->userId , true )->add( $equipId );	
	}


	/**
	 * 穿上装备
	 * @param unknown $heroId 英雄唯一Id
	 * @param unknown $equipUid 用户装备唯一Id
	 */
	public function equipUp( $heroUid , $equipUid )
	{
		$this->checkInTeam( $heroUid );
		//计算装备部位
		$equipConfig = Common::getConfig( 'equip' );
		$equipData = Data_Equip_Model::getInstance( $this->userId )->getInfo( $equipUid );
		$heroData = Data_Hero_Model::getInstance( $this->userId )->getData();
		
		//获取配置表装备Id
		$equipId = $equipData['equipCid'];
		$equipConf = $equipConfig[$equipId];
		
		if( empty( $equipConf ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		//如果这件装备穿在别人身上，则要卸下来
		if( $equipData['heroId'] > 0 )
		{
			Data_Hero_Model::getInstance( $this->userId ,true )->equipDown( $equipData['heroId'] ,  $equipConf['type'] );
		}
		
		$downEquipId = 0;
		//如果目标身上已经有装备，则要更新那件装，已经不在任何人身上
		//先得到要去掉标识的装备ID
		if( $equipConf['type'] == self::EQUIP_TYPE_WEAPON )
		{
			$downEquipId = $heroData[$heroUid]['weaponId'];
		}
		elseif ( $equipConf['type'] == self::EQUIP_TYPE_ARMOR )
		{
			$downEquipId = $heroData[$heroUid]['armorId'];
		}
		elseif( $equipConf['type'] == self::EQUIP_TYPE_ACC )
		{
			$downEquipId = $heroData[$heroUid]['accId'];
		}
		
		if( $downEquipId > 0 )
		{
			Data_Equip_Model::getInstance( $this->userId , true )->setEquipedHero( $downEquipId , 0 );
		}
	
		//更新英雄各部位装备ID
		Data_Hero_Model::getInstance( $this->userId ,true )->equipUp( $heroUid , $equipUid , $equipConf['type'] );
		
		//更新装备信息，标识这个装备安在谁身上
		Data_Equip_Model::getInstance( $this->userId , true )->setEquipedHero( $equipUid , $heroUid );
	}
	
	
	
	/**
	 * 从某个人身上脱下某装备
	 * @param unknown $heroUid
	 * @param unknown $equipUid
	 */
	public function equipDown( $heroUid , $equipUid  )
	{
		$this->checkInTeam( $heroUid );
		//计算装备部位
		$equipConfig = Common::getConfig( 'equip' );
		$equipData = Data_Equip_Model::getInstance( $this->userId )->getInfo( $equipUid );
		$heroData = Data_Hero_Model::getInstance( $this->userId )->getData();
		
		//获取配置表装备Id
		$equipId = $equipData['equipCid'];
		$equipConf = $equipConfig[$equipId];
		
		if( empty( $equipConf ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		//对英雄来说，已经脱下了装备
		if( $equipData['heroId'] > 0 )
		{
			Data_Hero_Model::getInstance( $this->userId ,true )->equipDown( $equipData['heroId'] ,  $equipConf['type'] );
		}
		
		
		//对装备来说,已经不属性任何一个英雄了
		$downEquipId = 0;
		//如果目标身上已经有装备，则要更新那件装，已经不在任何人身上
		//先得到要去掉标识的装备ID
		if( $equipConf['type'] == self::EQUIP_TYPE_WEAPON )
		{
			$downEquipId = $heroData[$heroUid]['weaponId'];
		}
		elseif ( $equipConf['type'] == self::EQUIP_TYPE_ARMOR )
		{
			$downEquipId = $heroData[$heroUid]['armorId'];
		}
		elseif( $equipConf['type'] == self::EQUIP_TYPE_ACC )
		{
			$downEquipId = $heroData[$heroUid]['accId'];
		}
		
		if( $downEquipId != $equipUid )
		{
			throw new Equip_Exception( Equip_Exception::STATUS_EQUIP_ID_ERROR );
		}
		
		if( $downEquipId > 0 )
		{
			Data_Equip_Model::getInstance( $this->userId , true )->setEquipedHero( $downEquipId , 0 );
		}
	}
	
	
	/**
	 * 判断英雄是否在小队中
	 * @param unknown $heroUid
	 */
	public function checkInTeam( $heroUid )
	{
		$userInfo = Data_User_Info::getInstance( $this->userId )->getData();
		$heroTeamArr =  explode( ",",  $userInfo['heroTeam'] );
	
		if( !in_array( $heroUid , $heroTeamArr ))
		{
			throw new Hero_Exception( Hero_Exception::STATUS_HERO_NOT_IN_TEAM );
		}
	}
	
	
	/**
	 * 强化装备
	 * @param unknown $id
	 */
	public function reinforce( $equipUid )
	{
		$equipData = Data_Equip_Model::getInstance( $this->userId )->getInfo( $equipUid );
		if( empty( $equipData ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_EQUIP_NOT_EXSIT );
		}
		
		$equipId = $equipData['equipCid'];
		$equipConfig = Common::getConfig( 'equip' );
		$equipConf = $equipConfig[$equipId];
		
		if( empty( $equipConf ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		//是否能成功扣除金币
		if( User_Info::getInstance( $this->userId )->changeGold( -$equipConf['rein_gold'] ) == false )
		{
			throw new User_Exception( User_Exception::STATUS_NOT_ENOUGH_GOLD );
		}
		
		//最否达到最大上限,假设最大100级
		if( $equipData['level'] >= 100 )
		{
			throw new Equip_Exception( Equip_Exception::STATUS_OVER_MAX_LEVEL );
		}		
		
		$levelConfig = Common::getConfig( 'level' );
		//装备当前强化等级是否已经达到强化上限
		$userInfo = User_Info::getInstance( $this->userId )->getData();
		if( $equipData['level'] >= $levelConfig[$userInfo['level']]['rein_max_level']  )
		{
			throw new Equip_Exception( Equip_Exception::STATUS_OVER_MAX_LEVEL );
		}	
		
		//计算是否暴击,计算暴击等级
		$randNum = mt_rand( 1 , 100 );
		$critLevel = $randNum%6;
		if(  $equipData['level'] +  $critLevel > 100 )
		{
			$critLevel = 100 -  $equipData['level'];
		}
		$critLevel = $critLevel ? $critLevel : 1 ;
		$critLevel = 1;
		
		$newLevel = $equipData['level'] + $critLevel;
		//更新该装备属性
		Data_Equip_Model::getInstance( $this->userId , true )->setEquipLevel( $equipUid , $newLevel  );
		return $critLevel;
		
	}
	
	/**
	 * 出售装备
	 * @param unknown $equipUid
	 */
	public function sell( $equipUid )
	{
		$this->delEquip( $equipUid );
	}
	
	
	/**
	 * 装备洗练
	 * @param unknown $equipUid
	 */
	public function wash( $equipUid , $washType )
	{
		
		//是否可以洗练
		$equipData = Data_Equip_Model::getInstance( $this->userId )->getInfo( $equipUid );
		if( empty( $equipData ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_EQUIP_NOT_EXSIT );
		}
		
		$equipId = $equipData['equipCid'];
		$equipConfig = Common::getConfig( 'equip' );
		$equipConf = $equipConfig[$equipId];
		
	
		if( empty( $equipConf ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
	
		
		//判断装备是否可以洗炼
		if( $equipConf['wash_num'] <= 0 )
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CANNOT_WASH );
		}
		
		//TODO::
		$gold = $coin = 100;
		
		//区分用金钱洗练，还是元宝洗练
		//判断钱币是否足够
		if( $washType == self::WASH_TYPE_GOLD )
		{
			if( User_Info::getInstance( $this->userId )->changeGold( $gold ) == false )
			{
				throw new User_Exception( User_Exception::STATUS_NOT_ENOUGH_GOLD );
			}
		}
		else 
		{
			if( User_Info::getInstance( $this->userId )->changeCoin( $coin ) == false )
			{
				throw new User_Exception( User_Exception::STATUS_NOT_ENOUGH_COIN );
			}
		}
		
		$newWashAttr = $this->getWashAttr( $equipConf , $washType , $equipData['level'] );
		Data_Equip_Model::getInstance( $this->userId , true )->setWillWashedAttr( $equipUid , $newWashAttr );
	
	}
	
	
	/**
	 * 获取新的洗练属性
	 */
	protected function getWashAttr( $equipConf , $washType , $equipLevel )
	{
		$equipLevel = $equipLevel ? $equipLevel : 1;
		$etcConfig = Common::getConfig( 'etc' );
		$qulityRands = array();
		for( $i = 0 ; $i<=3 ;$i++ )
		{
			$key = $washType == 1 ? 'gold' : 'coin';
			$key .= "_wash_rate_{$i}";
			$qulityRands[$i] = $etcConfig[$key];
		}
		
		//计算品质
		$minNum = 0;
		$attrQulity = 0;
		$randNum = mt_rand( 1, 10000 );
		//$randNum = 3000;
		foreach ( $qulityRands as $key => $num )
		{
			if( $randNum > $minNum && $randNum <= $minNum+$num )
			{
				$attrQulity = $key;
				break;
			}
			$minNum = $minNum+$num;
		}
		
		
		if( $attrQulity > $equipConf['quality'] )
		{
			$attrQulity = 0;
		}
		//如果属性品质，大于装备品质，则直接为0
		if( $attrQulity == 0 )
		{
			return null;
		}
		
		$washConfig = Common::getConfig( 'wash' );
		$washConf = $washConfig[$attrQulity];
		
		if( empty( $washConf ) )
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		//根据装备等级过滤可洗属性
		foreach ( $washConf as $key => $info )
		{
			if( $info['level'] > $equipLevel )
			{
				unset( $washConf[$key] );
			}
		}
		$newAttrs = null;
		
		//抽取两个属性
		for( $i = 1; $i <= $equipConf['wash_num']; $i++ )
		{
			$randKey = array_rand( $washConf );
			$newAttrs[$i]['attr'] =  $washConf[$randKey]['attr_name'];
			$newAttrs[$i]['val'] =  $washConf[$randKey]['attr_val'];
		}
		return $newAttrs;
		
	}
	
	
	/**
	 * 确认洗练
	 * @param unknown $equipUid
	 */
	public function confirmWash( $equipUid )
	{
		Data_Equip_Model::getInstance( $this->userId , true )->confirmWash( $equipUid );
	}
	
	
	/**
	 * 去消洗练的新属性
	 * @param unknown $equipUid
	 */
	public function cancelWash( $equipUid )
	{
		Data_Equip_Model::getInstance( $this->userId , true )->cancelWash( $equipUid );
	}
	
	/**
	 * 删除装备
	 */
	public function delEquip( $equipUid )
	{
		//如果这件装备被某人穿着，那得先脱下来，才能删
		$equipInfo = Data_Equip_Model::getInstance( $this->userId )->getInfo( $equipUid );
		
		if( $equipInfo['heroId'] > 0 )
		{
			$this->equipDown(  $equipInfo['heroId'] , $equipUid );
		}
		Data_Equip_Model::getInstance( $this->userId , true )->del( $equipUid );
	
	}
	
	
}
