<?php

/**
 * 被动技能
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Hero_PassiveSkill extends Data_Abstract
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
			'passive_skill' => array(
				'columns' => array(
					'id' , 'skillCid' , 'skillLevel' , 'heroId'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'passive_skill' , $lock  );
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
		if( $table == 'passive_skill' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['passive_skill']['columns'] ) )
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
		foreach ( $data as $eachData )
		{
			$id = (int)$eachData[1];
			$this->data[$id] = array(
				'id' => (int)$eachData[1],
				'skillCid' => (int)$eachData[2] ,
				'skillLevel' => (int)$eachData[3],
				'heroId' => (int)$eachData[4],
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
	 * 添加一个技能
	 * @param unknown $heroInfo
	 */
	public function add( $id , $level = 0 )
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
		
		$this->data[$maxUnid] = array(
			'id' => $maxUnid,
			'skillCid' => $id,
			'skillLevel' => $level,
			'heroId' => 0,
		);

		$this->updateToDb( 'passive_skill', self::DATA_ACTION_ADD , $this->data[$maxUnid] );
		return $maxUnid;
	}
	
	/**
	 * 设置此装备穿在了哪个武将身上
	 * @param unknown $id
	 */
	public function equipedHero(  $skillId , $heroUid = 0  )
	{
		$this->data[$skillId]['heroId'] = $heroUid;
		$this->updateToDb( 'passive_skill', self::DATA_ACTION_UPDATE , $this->data[$skillId] );
	}
	
	/**
	 * 获取单件装备信息
	 * @param unknown $id
	 * @return multitype:
	 */
	public function getInfo( $id )
	{
		return $this->data[$id] ? $this->data[$id] : array();
	}
	
	/**
	 * 删除一个技能
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
			$this->updateToDb( 'passive_skill', self::DATA_ACTION_DELETE , array( 'id' => $index ));
		}
	
		return true;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
}
