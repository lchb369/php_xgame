<?php
/**
 * 用户游戏信息
 * 
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class User_Info
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
	 * 创建角色 
	 * @param unknown $nickName
	 */
	public function create( $nickName , $sex )
	{
		$this->checkNickName( $nickName );
		Data_User_Info::getInstance( $this->userId , true )->create( $nickName , $sex );
		
		//初始化user_profile信息
		Data_User_Profile::getInstance( $this->userId , true );
		
	}
	
	/**
	 * 检查有效性
	 * @param unknown $nickName
	 */
	protected function checkNickName( $nickName )
	{
		if( !$nickName   )
		{
			throw new User_Exception( User_Exception::STATUS_NICKNAME_EMPTY );
		}
	
		if( strlen( $nickName ) < 4 || strlen( $nickName ) > 20 )
		{
			throw new User_Exception( User_Exception::STATUS_NICKNAME_INVALID );
		}
		
		$dbEngine = Common::getDB();
		$sql = "select * from user_info where nickname='{$nickName}'";
		$record = $dbEngine->findQuery( $sql );
		
		if( count( $record ) > 0 )
		{
			throw new User_Exception( User_Exception::STATUS_NICKNAME_REPEAT );
		}
	}
	
	
	/**
	 * 英雄小队，用逗号隔开
	 * @param unknown $heros
	 */
	public function formatTeam( $heros )
	{
		$heroArr = explode( ",", $heros );
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		
		
		if( count( $heroArr ) > 4 )
		{
			throw new Hero_Exception( Hero_Exception::STATUS_HERO_TEAM_NUM_ERROR );
		}
		
		foreach ( $heroArr as $heroUid )
		{
			if( $heroUid > 0 && empty( $heroList[$heroUid]))
			{
				throw new Hero_Exception( Hero_Exception::STATUS_NOT_EXIST_HERO );
			}
		}
		
		$userInfo = Data_User_Info::getInstance( $this->userId )->getData();
		$oldHeroTeamArr =  explode( ",",  $userInfo['heroTeam'] );

		$this->_replaceEquipAndSkill( $heroArr , $oldHeroTeamArr  );
		
		Data_User_Info::getInstance( $this->userId , true )->formatTeam( $heros );		
	}
	
	
	/**
	 * 换装
	 * @param unknown $newHeros
	 * @param unknown $oldHeros
	 */
	private function _replaceEquipAndSkill( $newHeros , $oldHeros )
	{
		for( $i = 0 ; $i < 4 ; $i++ )
		{
			if( !$oldHeros[$i] || $oldHeros[$i] == $newHeros[$i] )
			{
				continue;
			}
			Data_Hero_Model::getInstance( $this->userId , true )->replaceEquipAndSkill( $oldHeros[$i] , $newHeros[$i] );
		}
	}
	
	
	
	/**
	 * 改成金币
	 * @param unknown $gold
	 */
	public function changeGold( $gold )
	{
		return Data_User_Info::getInstance( $this->userId , true )->changeGold( $gold );
	}
	
	/**
	 * 改成充值币
	 * @param unknown $coin
	 */
	public function changeCoin( $coin )
	{
		return Data_User_Info::getInstance( $this->userId , true )->changeCoin( $coin );
	}
	
	/**
	 * 改变碎片
	 * @param unknown $chips
	 * @return boolean
	 */
	public function changeChips( $chips )
	{
		return Data_User_Info::getInstance( $this->userId , true )->changeChips( $chips );
	}
	
	/**
	 * 改变经验
	 * @param unknown $exp
	 */
	public function changeExp( $exp )
	{
		Data_User_Info::getInstance( $this->userId , true )->changeExp( $exp );
	}
	
	//增加体力
	public function addStrength( $num )
	{
		Data_User_Info::getInstance( $this->userId , true )->addStrength( $num );
	}
	
	//增加精力
	public function addVigor( $num )
	{
		Data_User_Info::getInstance( $this->userId , true )->addVigor( $num );
	}
	
	//解锁最大关卡
	public function unlockChapterSec( $secId )
	{
		Data_User_Info::getInstance( $this->userId , true )->unlockChapterSec( $secId );
	}

	public function getData()
	{
		return Data_User_Info::getInstance( $this->userId )->getData();
	}
	
}
