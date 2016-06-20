<?php

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_User_Profile extends Data_Abstract
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
			'user_profile' => array(
				'columns' => array(
					'registerTime' , 'loginTime' , 'keepLoginDays' , 'totalLoginDays' , 'todayLoginTimes'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
	
		parent::__construct( $userId , 'user_profile' , $lock  );
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
		if( $table == 'user_profile' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['user_profile']['columns'] ) )
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
		$formatedData = array(
			'registerTime' => $data[1],
			'loginTime' => $data[2],
			'keepLoginDays' => $data[3],	//冲值币
			'totalLoginDays'=> $data[4],
			'todayLoginTimes' => $data[5],
		);
		return $formatedData;
	}
	
	/**
	 * 如果数据为空
	 * @see Data_Abstract::emptyDataWhenloadFromDB()
	 */
	protected function emptyDataWhenloadFromDB( $table )
	{
		$this->data = array(
			'registerTime' => $_SERVER['REQUEST_TIME'],
			'loginTime' => $_SERVER['REQUEST_TIME'],
			'keepLoginDays' => 1,	//冲值币
			'totalLoginDays'=> 1,
			'todayLoginTimes' => 1,
		);
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_ADD , $this->data );
		return $this->data;
	}
	
	
	public function setLoginTime()
	{
		$this->data['loginTime'] = $_SERVER['REQUEST_TIME'];
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	
	
}
