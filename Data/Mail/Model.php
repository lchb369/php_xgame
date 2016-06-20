<?php

/**
 * 邮箱系统
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Mail_Model extends Data_Abstract
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
			'mail_list' => array(
				'columns' => array(
					'id' , 'items' , 'title' , 'content' , 'sendTime'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'mail_list' , $lock  );
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
		if( $table == 'mail_list' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['mail_list']['columns'] ) )
				{
					if( $key == 'items' )
					{
						$returnData[$key] = addslashes( $value );
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
			$id = (int)$eachData[1];
			$this->data[$id] = array(
				'id' => $id ,
				'items' => $eachData[2],
				'title' => $eachData[3],
				'content' => $eachData[4],
				'sendTime' => (int)$eachData[5],
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
	 * 发送邮件
	 * @param unknown $title 
	 * @param unknown $content
	 * @param unknown $items
	 */
	public function addMail( $mailInfo )
	{
		if( !$mailInfo['packName'] || !$mailInfo['packDesc'] )
		{
			return false;
		}
		
		$id = 1;
		if( $this->data )
		{
			$keys = array_keys( $this->data );
			$id = max( $keys )+1;
		}
		
		$this->data[$id] = array(
			'id' => $id,
			'title' => $mailInfo['packName'] ,
			'content' => $mailInfo['packDesc'],
			'items' => $mailInfo['packInfo'],
			'sendTime' => $_SERVER['REQUEST_TIME'],
		);
		$this->updateToDb( 'mail_list' , self::DATA_ACTION_ADD , $this->data[$id] );
	}

	
	public function getData()
	{
		return $this->data;
	}
	
}
