<?php

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Equip_Model extends Data_Abstract
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
			'equip_list' => array(
				'columns' => array(
					'id' , 'level' , 'equipCid' ,  'washAttr' , 'heroId' , 'willWashAttr' //洗炼出来的属性
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'equip_list' , $lock  );
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
		if( $table == 'equip_list' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['equip_list']['columns'] ) )
				{
					if( $key == 'washAttr' || $key == 'willWashAttr' )
					{
						$returnData[$key] = addslashes( json_encode( $value ) );
					}
					else 
					{
						$returnData[$key] = $value;
					}
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
			$washAttr =  json_decode( $eachData[4] , true );
			$willWashAttr =  json_decode( $eachData[6] , true );
			$id = (int)$eachData[1];

			$this->data[$id] = array(
				'id' => (int)$eachData[1],
				'level' => (int)$eachData[2] ,
				'equipCid' => (int)$eachData[3],
				'washAttr' => $washAttr ? $washAttr : null,
				'heroId' => (int)$eachData[5],
				'willWashAttr' => $willWashAttr ? $willWashAttr : null,
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
	 * 添加一件装备
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
				'id' => (int)$maxUnid,
				'level' => $level ,
				'equipCid' => (int)$id,
				'washAttr' => null,
				'heroId' => 0,
				'willWashAttr' => null,
		);

		$this->updateToDb( 'equip_list', self::DATA_ACTION_ADD , $this->data[$maxUnid] );
		return $maxUnid;
	}
	
	
	/**
	 * 设置洗练出的属性
	 * @param unknown $newWashAttr
	 */
	public function setWillWashedAttr( $equipUid , $newAttr )
	{
		$this->data[$equipUid]['willWashAttr'] = $newAttr;
		$this->updateToDb( 'equip_list', self::DATA_ACTION_UPDATE , $this->data[$equipUid] );
	}
	
	/**
	 * 确认洗练
	 * @param unknown $equipUid
	 */
	public function confirmWash( $equipUid )
	{
		if( empty( $this->data[$equipUid]['willWashAttr'] ) )
		{
			return;
		}
		//可么写反了
		$this->data[$equipUid]['washAttr'] = $this->data[$equipUid]['willWashAttr'];
		$this->data[$equipUid]['willWashAttr'] = null;
		$this->updateToDb( 'equip_list', self::DATA_ACTION_UPDATE , $this->data[$equipUid] );
	}
	
	
	/**
	 * 去消洗练的属性
	 * @param unknown $equipUid
	 */
	public function cancelWash( $equipUid )
	{
		if( empty( $this->data[$equipUid]['willWashAttr'] ) )
		{
			return;
		}
		//可么写反了
		$this->data[$equipUid]['willWashAttr'] = null;
		$this->updateToDb( 'equip_list', self::DATA_ACTION_UPDATE , $this->data[$equipUid] );
	}
	
	/**
	 * 设置装备ID,装备强化时用
	 * @param unknown $equipUid
	 */
	public function setEquipLevel( $equipUid , $level )
	{
		$this->data[$equipUid]['level'] = $level;
		$this->updateToDb( 'equip_list', self::DATA_ACTION_UPDATE , $this->data[$equipUid] );
	}

	/**
	 * 设置此装备穿在了哪个武将身上
	 * @param unknown $id
	 */
	public function setEquipedHero( $equipUid , $heroUid = 0 )
	{
		$this->data[$equipUid]['heroId'] = $heroUid;
		$this->updateToDb( 'equip_list', self::DATA_ACTION_UPDATE , $this->data[$equipUid] );
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
	 * 删除一件装备
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
			$this->updateToDb( 'equip_list', self::DATA_ACTION_DELETE , array( 'id' => $index ));
		}
	
		return true;
	}
	
	
	public function getData()
	{
		return $this->data;
	}
	
}
