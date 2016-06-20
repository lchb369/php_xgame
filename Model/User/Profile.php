<?php
/**
 * 用户游戏信息
 * 
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class User_Profile
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
	 * 用户进入游戏时触发
	 */
	public function onLogin()
	{
		$userProfile = Data_User_Profile::getInstance( $this->userId , true );
		$userProfile->setLoginTime();
		
	}
	
	
	public function getData()
	{
		return Data_User_Profile::getInstance( $this->userId )->getData();
	}
	
}
