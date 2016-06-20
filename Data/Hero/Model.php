<?php

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Hero_Model extends Data_Abstract
{
	
	/**
	 * 单例对象
	 * @var	Data_User_Info[]
	 */
	protected static $singletonObjects;
	
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	
	 */
	public function __construct( $userId , $lock = false  )
	{
		$this->dbColumns = array(
			'hero_list' => array(
				'columns' => array(
					'id' , 'heroCid' , 'exp' , 'level' , 'weaponId' , 'armorId' , 'accId' , 'skillExp' , 'skillCid' , 'pSkillId'
				),
				'isNeedFindAll' => true ,
			),
		);
		parent::__construct( $userId , 'hero_list' , $lock  );
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Info
	 */
	public static function & getInstance( $userId , $lock = false  )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock  );
		}
		
		if( $lock )
		{
			ObjectStorage::register( self::$singletonObjects[$userId] );
		}
		return self::$singletonObjects[$userId];
	}

	
	/**
	 * 格式化保存到数据库的数据
	 * @param	array $table	表名
	 * @param	array $data		数据
	 * @return	array
	 */
	protected function formatToDBData( $table , $data )
	{
		if( $table == 'hero_list' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['hero_list']['columns'] ) )
				{
					$returnData[$key] = $value;	
				}
			}
		}
		return $returnData;
	}
	
	
	/**
	 * 将数据库数据格式转为cache格式
	 * @see Data_Abstract::formatFromDBData()
	 */
	protected function formatFromDBData( $table , $data )
	{
		//print_r( $data );exit;
		foreach ( $data as $eachData )
		{
			$id = (int)$eachData[1];
			$this->data[$id] = array(
				'id' => (int)$id,
				'heroCid' => (int)$eachData[2],
				'exp' => (int)$eachData[3],
				'level' => (int)$eachData[4],
				'weaponId' => (int)$eachData[5],
				'armorId'=> (int)$eachData[6], 
				'accId' => (int)$eachData[7],
				'skillExp' => (int)$eachData[8],
				'skillCid' => (int)$eachData[9],
				'pSkillId' => (int)$eachData[10],
			);
		}
		return $this->data;
	}
	
	
	/**
	 * 如果数据为空
	 * @see Data_Abstract::emptyDataWhenloadFromDB()
	 */
	protected function emptyDataWhenloadFromDB( $table )
	{
		//如果为空，不要自动创建
	}
	
	
	/**
	 * 添加一个英雄
	 * @param unknown $heroInfo
	 */
	public function add( $id , $skillCid = 0 )
	{
		if( empty( $this->data ))
		{
			$maxUnid = 1;
		}
		else
		{
			if( count( $this->data ) > 99 )
			{
				return false;
				//throw new Hero_Exception( Hero_Exception::STATUS_OVER_LIMIT );
			}
			$keys = array_keys( $this->data  );
			$maxUnid =  max( $keys )+1;
		}
	
		$heroConfig = Common::getConfig( 'hero' );
		
		//装备主动技能
		if( !$heroConfig[$id] && $heroConfig[$id]['active_skill_id'] )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_NO_HERO_CONF );
		}
		
		
		$this->data[$maxUnid] = array(
			'id' => (int)$maxUnid,
			'heroCid' => (int)$id,
			'exp' => 0,
			'level' => 0,
			'weaponId' => 0,
			'armorId'=> 0,
			'accId' => 0,
			'skillExp' => 0,
			'skillCid' => (int)$heroConfig[$id]['active_skill_id'],
			'pSkillId' => 0,
		);
	
		$this->updateToDb( 'hero_list', self::DATA_ACTION_ADD , $this->data[$maxUnid] );
		return $maxUnid;
	}
	
	/**
	 * 装备,被动技能转移
	 * @param unknown $oldHeroId
	 * @param unknown $newHeroId
	 * @return boolean
	 */
	public function replaceEquipAndSkill( $oldHeroId , $newHeroId )
	{
		if( !$this->data[$oldHeroId] )
		{
			return true;
		}
		
		if(  $newHeroId > 0 )
		{
			//互换英雄装备
			$this->data[$newHeroId]['weaponId'] = $this->data[$oldHeroId]['weaponId'];
			$this->data[$newHeroId]['armorId'] = $this->data[$oldHeroId]['armorId'];
			$this->data[$newHeroId]['accId'] = $this->data[$oldHeroId]['accId'];
			$this->data[$newHeroId]['pSkillId'] = $this->data[$oldHeroId]['pSkillId'];
			$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$newHeroId] );
		}
		
		//脱下老英雄的东西
		$this->data[$oldHeroId]['weaponId'] = 0;
		$this->data[$oldHeroId]['armorId'] = 0;
		$this->data[$oldHeroId]['accId'] = 0;
		$this->data[$oldHeroId]['pSkillId'] = 0;
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$oldHeroId] );
	}
	
	/**
	 * 装备上一件装备
	 * @param unknown $heroUid
	 * @param unknown $equipUid
	 * @param unknown $equipType
	 * @throws Equip_Exception
	 */
	public function equipUp( $heroUid , $equipUid , $equipType  )
	{
		if( $equipType ==  Equip_Model::EQUIP_TYPE_WEAPON )
		{
			$this->data[$heroUid]['weaponId'] = $equipUid; 
		}
		elseif ( $equipType == Equip_Model::EQUIP_TYPE_ARMOR )
		{
			$this->data[$heroUid]['armorId'] = $equipUid;
		}
		elseif( $equipType == Equip_Model::EQUIP_TYPE_ACC  )
		{
			$this->data[$heroUid]['accId']= $equipUid;
		}
		else 
		{
			throw new Equip_Exception( Equip_Exception::STATUS_EQUIP_NOT_EXSIT );
		}
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$heroUid] );
	}
	
	
	
	/**
	 * 卸下一件装备
	 * @param unknown $heroUid
	 * @param unknown $equipUid
	 * @param unknown $equipType
	 * @throws Equip_Exception
	 */
	public function equipDown( $heroUid , $equipType  )
	{
		if( $equipType ==  Equip_Model::EQUIP_TYPE_WEAPON )
		{
			$this->data[$heroUid]['weaponId'] = 0;
		}
		elseif ( $equipType == Equip_Model::EQUIP_TYPE_ARMOR )
		{
			$this->data[$heroUid]['armorId'] = 0;
		}
		elseif( $equipType == Equip_Model::EQUIP_TYPE_ACC  )
		{
			$this->data[$heroUid]['accId']= 0;
		}
		else
		{
			throw new Equip_Exception( Equip_Exception::STATUS_EQUIP_NOT_EXSIT );
		}
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$heroUid] );
	}
	
	
	/**
	 * 装备或卸下一个被动技能
	 * @param unknown $heroUid
	 * @param unknown $equipUid
	 * @param unknown $equipType
	 * @throws Equip_Exception
	 */
	public function equipUpPSkill( $heroUid , $skillId = 0 )
	{
		$this->data[$heroUid]['pSkillId']= $skillId;
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$heroUid] );
	}
	
	
	/**
	 * 删除一个英雄 
	 * @param unknown $heroInfo
	 */
	public function del( $index )
	{
		if( empty( $this->data ))
		{
			return false;
		}
		else
		{
			unset( $this->data[$index] );
			$this->updateToDb( 'hero_list', self::DATA_ACTION_DELETE , array( 'id' => $index ));
		}
		return true;
	}
	
	/**
	 * 增加经验 
	 * @param unknown $heroUid
	 * @param unknown $exp
	 */
	public function setExp( $heroUid , $exp , $level = 0 )
	{
		$this->data[$heroUid]['exp'] = $exp;
		$this->data[$heroUid]['level'] = $level;
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$heroUid] );
		return $this->data[$heroUid]['exp'];
	}
	
	/**
	 * 设置主动技能
	 * @param unknown $heroUid
	 * @param unknown $skillCid
	 * @param unknown $skillExp
	 */
	public function setActiveSkill( $heroUid ,  $skillCid , $skillExp )
	{
		$this->data[$heroUid]['skillCid'] = $skillCid;
		$this->data[$heroUid]['skillExp'] = $skillExp;
		$this->updateToDb( 'hero_list', self::DATA_ACTION_UPDATE , $this->data[$heroUid] );
	}
	
	
	public function getData( $heroUid = 0 )
	{
		if( $heroUid > 0 )
		{
			return $this->data[$heroUid];
		}
		return $this->data;
	}
	
	
}
