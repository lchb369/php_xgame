<?php
/**
 * 英雄模块
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class Hero_Model
{
	
	protected $userId;
	/**
	 * 单例对象
	 * @var	User_Info[]
	 */
	protected static $singletonObjects;

	//主动技能
	const SKILL_TYPE_ACTIVE = 1;
	
	//怒气技能
	const SKILL_TYPE_ANGER = 3;
	
	//被动技能
	const SKILL_TYPE_PASSIVE = 2;
	
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
	 * 增加一个英雄
	 * @param unknown $heroType
	 */
	public function addHero( $heroId )
	{
		return Data_Hero_Model::getInstance( $this->userId , true )->add( $heroId );
	}


	/**
	 * 英雄兑换
	 * @param unknown $heroId
	 */
	public function composite( $heroId )
	{
		
		//是否可以兑换
		$heroConfig = Common::getConfig( 'hero' );
		$heroConf = $heroConfig[$heroId];
		//碎片是否足够
		if( empty( $heroConf ) )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NO_HERO_CONF );
		}
		
		//是否可以兑换
		if( $heroConf['enable_exchange'] == 0 || $heroConf['exch_need_chips'] == 0 )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_CANNOT_COMPOSITE );
		}
		
		//扣除碎片
		if( User_Info::getInstance( $this->userId )->changeChips( -$heroConf['exch_need_chips'] ) == false )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NOT_ENOUGH_CHIPS );
		}
			
		//增加武将
		if( $this->addHero( $heroId ) == false )
		{
			//超过上限
			throw new Hero_Exception( Hero_Exception::STATUS_OVER_HERO_MAX );
		}
	}
	
	
	/**
	 * 分解英雄
	 */
	public function explode( $heroUid )
	{
		
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		if( empty( $heroList[$heroUid]) )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NOT_EXIST_HERO );
		}
		$heroData = $heroList[$heroUid];
		
		$heroConfig = Common::getConfig( 'hero' );
		$heroConf = $heroConfig[$heroData['heroCid']];
		

		if( empty( $heroConf ) )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NO_HERO_CONF );
		}
		
		//增加碎片
		User_Info::getInstance( $this->userId )->changeChips( $heroConf['explode_chips'] );	

		//删除英雄
		$this->delHero($heroUid );
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
	 * 增加经验
	 * @param unknown $heroUid
	 * @param unknown $exp
	 */
	public function addExp( $heroUid , $exp )
	{
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		$heroData = $heroList[$heroUid];
		if( empty( $heroData ) )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NOT_EXIST_HERO );
		}
		
		$heroLevel = Common::getConfig( 'heroLevel' );
		$etcConfig = Common::getConfig( 'etc' );
		
		$totalExp = $heroData['exp'] + $exp;
		$newLevel = $heroData['level'];
		for( $level = $heroData['level'] ; $level < $etcConfig['hero_level_limit'] ; $level++ )
		{
			if( $totalExp >= $heroLevel[$level] )
			{
				$newLevel++;
				break;
			}
		}
		Data_Hero_Model::getInstance( $this->userId , true )->setExp( $heroUid , $exp , $newLevel );
	}
	
	
	/**
	 * 装备被动技能
	 */
	public function equipSkill( $heroUid , $skillUid )
	{
		$this->checkInTeam( $heroUid );
		//计算装备部位
		$skillConfig = Common::getConfig( 'skill' );
		$skillData = Data_Hero_PassiveSkill::getInstance( $this->userId )->getInfo( $skillUid );
		$heroData = Data_Hero_Model::getInstance( $this->userId )->getData();
		
		if(  $skillData['heroId'] == $heroUid )
		{
			return true;
		}
		
		//获取配置表装备Id
		$skillCid = $skillData['skillCid'];
		$skillConf = $skillConfig[$skillCid];
		
		if( empty( $skillConf ))
		{
			throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		if( $heroUid == 0 )
		{
			Data_Hero_PassiveSkill::getInstance( $this->userId , true )->equipedHero( $skillUid , 0 );
			return true;
		}
		
		
		//如果这个技能穿在别人身上，则要卸下来
		if( $skillData['heroId'] > 0 )
		{
			Data_Hero_Model::getInstance( $this->userId ,true )->equipUpPSkill( $skillData['heroId'] , 0  );
		}
		
		$downSkillId = 0;
		//如果目标身上已经有被动技能了，则要更新那个技能，已经不在任何人身上
		//先得到要去掉标识的装备唯一ID
		$downSkillId = $heroData[$heroUid]['pSkillId'];
		if( $downSkillId > 0 )
		{
			Data_Hero_PassiveSkill::getInstance( $this->userId , true )->equipedHero( $downSkillId , 0 );
		}
		
		//更新英雄各部位装备ID
		Data_Hero_Model::getInstance( $this->userId ,true )->equipUpPSkill( $heroUid , $skillUid  );
		
		//更新装备信息，标识这个装备安在谁身上
		Data_Hero_PassiveSkill::getInstance( $this->userId , true )->equipedHero( $skillUid , $heroUid );
	}
	
	
	/**
	 * 删除英雄
	 * @param unknown $heroUid
	 */
	public function delHero( $heroUid )
	{
		//删除英雄时，要把英雄的所有装备脱下来，把被动技能也要脱下来
		$heroData = Data_Hero_Model::getInstance( $this->userId )->getData( $heroUid );
		if( empty( $heroData ))
		{
			return false;
		}
		
		//先把装备脱下来再去死
		if( $heroData['weaponId'] > 0 )
		{
			Equip_Model::getInstance( $this->userId )->equipDown( $heroUid , $heroData['weaponId']  );
		}
		
		if( $heroData['armorId'] > 0 )
		{
			Equip_Model::getInstance( $this->userId )->equipDown( $heroUid , $heroData['armorId']  );
		}
		
		if( $heroData['accId'] > 0 )
		{
			Equip_Model::getInstance( $this->userId )->equipDown( $heroUid , $heroData['accId']  );
		}
		
		//看着英雄要死了，被动技能背叛他，离他而去了
		if( $heroData['pSkillId'] > 0 )
		{
			Data_Hero_PassiveSkill::getInstance( $this->userId , true )->equipedHero( $heroData['pSkillId'] , 0 );
		}
		
		Data_Hero_Model::getInstance( $this->userId , true )->del( $heroUid );
		return true;
	}
	
	
	/**
	 * 增加主动技能经验,就是英雄自身带的技能，不能替换，只能升级
	 * @param unknown $heroUid
	 */
	public function addActiveSkillExp( $heroUid , $addSkillExp )
	{
		$heroData = Data_Hero_Model::getInstance( $this->userId )->getData( $heroUid );
		
		$heroConfig = Common::getConfig( 'hero' );
		$heroConf = $heroConfig[$heroData['heroCid']];
		if( empty( $heroConf ))
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NO_HERO_CONF );
		}		
		$newSkillExp = $heroData['skillExp'] + $addSkillExp;
		
		//判断是否升级了
		$skillConfig = Common::getConfig( 'skill' );
		$skillCid = $heroData['skillCid'] ? $heroData['skillCid'] : $heroConf['active_skill_id'];
		$skillConf = $skillConfig[$skillCid];
		
		if( empty( $skillConf ))
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NO_HERO_CONF );
		}
		
		
		if( $newSkillExp >= $skillConf['up_exp'] )
		{
			$newSkillExp = 0;
			$newSkillCid = $skillConf['next_id'];
			if( $newSkillCid == 0 )
			{
				throw new Hero_Exception( Hero_Exception::STATUS_SKILL_OVER_MAX_LEVEL );
			}
		}
		else 
		{
			$newSkillCid = $skillCid;
		}
		
		//替换老ID技能与经验
		Data_Hero_Model::getInstance( $this->userId ,true  )->setActiveSkill( $heroUid , $newSkillCid , $newSkillExp );
		return true;
	}
	
	
	/**
	 * 删除被动技能时，要把英雄的被动技能ID置零
	 */
	public function delPSkill( $skillUid )
	{
		$pSkillInfo = Data_Hero_PassiveSkill::getInstance( $this->userId )->getInfo( $skillUid );
		if( $pSkillInfo['heroId'] > 0 )
		{
			//设置英雄的被动技能为0
			Data_Hero_Model::getInstance( $this->userId ,true )->equipUpPSkill( $pSkillInfo['heroId'] , 0  );
		}
		Data_Hero_PassiveSkill::getInstance( $this->userId , true )->del($skillUid );
	}
	
	
}
