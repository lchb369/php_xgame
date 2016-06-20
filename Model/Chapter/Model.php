<?php

/**
 * 关卡模块
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class Chapter_Model
{
	
	protected $userId;
	/**
	 * 单例对象
	 * @var	User_Info[]
	 */
	protected static $singletonObjects;

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
	 * 解锁小节ID
	 * @param unknown $secId 关小节ID
	 * @param unknown $star  过关星级
	 */
	public function passChapterSec( $secId ,$star )
	{
		$sectionConfig = Common::getConfig( 'chapterSection');	
		//是否存在此小节
		$secConf = $sectionConfig[$secId];
		if( empty( $secConf ))
		{
			throw new Chapter_Exception( Chapter_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		
		//是否大于最大关卡，要解锁新关卡
		$userInfo = Data_User_Info::getInstance( $this->userId )->getData();
		if( $userInfo['maxSecId'] < $secId && $userInfo['maxSecId'] > 0 )
		{
			//判断当前关卡配置
			$currSecConf = $sectionConfig[$userInfo['maxSecId']];
			if( empty( $currSecConf ))
			{
				throw new Chapter_Exception( Chapter_Exception::STATUS_CONFIG_NOT_EXSIT );
			}
			
			//是可以解锁这个关卡
			if( $currSecConf['next_sec_id'] != $secId )
			{
				throw new Chapter_Exception( Chapter_Exception::CANNOT_UNLOCK_THIS_SECTION );
			}
			 
			//解锁新关卡TODO::
		}
		
		if( $secId > $userInfo['maxSecId'] )
		{
			//解锁此小关
			User_Info::getInstance( $this->userId )->unlockChapterSec( $secId );
		}
		
		//是否已经有通关记录
		$passedInfo = Data_Chapter_Model::getInstance( $this->userId )->getPassedInfo( $secId );
		
		//历史通关星级:$passedInfo['star'] , 今日已经挑战次数
		if( !$passedInfo )
		{
			Data_Chapter_Model::getInstance( $this->userId , true )->setPassedStar( $secId , $star );
		}
		else 
		{
			//是否星级高于原来星级，如果高于，更新星级
			$star = $passedInfo['star'] > $star ?  $passedInfo['star'] : $star;
			Data_Chapter_Model::getInstance( $this->userId , true )->setPassedStar( $secId , $star , $passedInfo['passedTimes']+1 );
		}
		//$passedInfo = Data_Chapter_Model::getInstance( $this->userId )->getPassedInfo( $secId );
	}
	
	
	
}
