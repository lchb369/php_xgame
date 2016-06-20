<?php

/**
 * 关卡信息
 * @name Info.php
 * @author liuchangbing
 * @since 2013-11-08
 * 
 * http://user.ecngame.com/api.php?method=User.bind&orgName=8b42c140f8ae0766a5b2b8b8dd921cd66dc66188&orgPwd=ecngame&bindName=908080133&bindPwd=515651&appId=1001
 * 
 * 1,记录通过的最大关卡ID
 * 2,记录非三星关卡
 * 3,记录关卡当日通关次数
 */
class Data_Chapter_Model extends Data_Abstract
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
			'passed_chapter' => array(
				'columns' => array(
					'id' , 'star' , 'passedTimes' ,  'resetTimes' , 'lastTime'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'passed_chapter' , $lock  );
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
		if( $table == 'passed_chapter' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['passed_chapter']['columns'] ) )
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
				'star' => (int)$eachData[2] ,
				'passedTimes' => (int)$eachData[3],
				'resetTimes' => (int)$eachData[4],
				'lastTime' => (int)$eachData[5],
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
	 * 设置通关星级
	 * @param unknown $chapterId
	 * @param unknown $star
	 */
	public function setPassedStar( $secId , $star , $times = 1 )
	{
		$this->data[$secId] = array(
			'id' => (int)$secId,
			'star' => (int)$star,
			'passedTimes' => (int)$times,
			'resetTimes' => 0,
			'lastTime' => $_SERVER['REQUEST_TIME'],
		);
		$this->updateToDb( 'passed_chapter', self::DATA_ACTION_UPDATE , $this->data[$secId] );
	}
	
	
	/**
	 * 是否通关
	 * @param unknown $chapterId
	 */
	public function isPassed( $chapterId )
	{
		
	}
	
	/**
	 * 获取通关信息
	 * @param unknown $chapterId
	 */
	public function getPassedInfo( $secId )
	{
		if( $this->data[$secId] )
		{
			return $this->data[$secId];
		}
		return null;
	}
	
	
	public function getData()
	{
		return $this->data;
	}
	
}
