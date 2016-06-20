<?php

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Item_Model extends Data_Abstract
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
			'item_list' => array(
				'columns' => array(
					'id' , 'num'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'item_list' , $lock  );
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
		if( $table == 'item_list' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['item_list']['columns'] ) )
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
				'num' => (int)$eachData[2],
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
	 *  改变道具数量
	 * @param unknown $itemCid
	 * @param unknown $num
	 */
	public function changeItem( $itemCid , $num )
	{
		if( $num == 0 || $this->data[$itemCid]['num'] + $num < 0 )
		{
			return false;
		}
		
		if( $this->data[$itemCid]['num'] + $num == 0 )
		{
			
			unset( $this->data[$itemCid] );
			$this->updateToDb( 'item_list', self::DATA_ACTION_DELETE , array( 'id' => $itemCid ));
			return true;
		}
		
		if( empty( $this->data[$itemCid] ) )
		{
			$this->data[$itemCid] = array(
				'id' => $itemCid,
				'num' => $num,
			);
			$this->updateToDb( 'item_list', self::DATA_ACTION_ADD , $this->data[$itemCid] );
		}
		else 
		{
			$this->data[$itemCid]['num'] += $num;
			$this->updateToDb( 'item_list', self::DATA_ACTION_UPDATE , $this->data[$itemCid] );
		}
		return true;
	}

	
	public function getData()
	{
		return $this->data;
	}
	
}
