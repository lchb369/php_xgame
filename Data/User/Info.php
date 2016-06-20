<?php

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_User_Info extends Data_Abstract
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
			'user_info' => array(
				'columns' => array(
					'nickname','sex','level','vip','gold','coin', 
					'heroTeam','chips','vigor','strength','maxSecId'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , 'user_info' , $lock  );
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
		if( $table == 'user_info' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['user_info']['columns'] ) )
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
			'nickname' => $data[1],
			'sex' => (int)$data[2],
			'exp' => (int)$data[3],	//冲值币
			'level'=> (int)$data[4],
			'vip' => (int)$data[5],
			'gold' => (int)$data[6],
			'coin' => (int)$data[7],
			'heroTeam' => $data[8],
			'chips' =>  (int)$data[9], 
			'vigor' => (int)$data[10], 
			'strength' => (int)$data[11],
			'maxSecId' => (int)$data[12],
		);
		return $formatedData;
	}
	
	
	public function unlockChapterSec( $secId )
	{
		if( $secId > $this->data['maxSecId'])
		{
			$this->data['maxSecId'] = $secId;
			$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	
	
	//增加体力
	public function addStrength( $num )
	{
		$this->data['strength'] += $num;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	//增加精力
	public function addVigor( $num )
	{
		$this->data['vigor'] += $num;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	/**
	 * 创建角色
	 * @param unknown $nickName 昵称
	 * @param unknown $sex      性别，1男，2女
	 */
	public function create( $nickName , $sex )
	{
		if( !empty( $this->data ) )
		{
			throw new User_Exception( User_Exception::STATUS_CREATE_REPEAT_ID );
		}
		
		$this->data = array(
			'nickname' => $nickName,
			'sex' => $sex,
			'exp' => 0,	//冲值币
			'level'=> 1,
			'vip'=> 0,
			'gold' => 2000,
			'coin' => 100,
			'heroTeam' => 'null',
			'chips' => 0 ,
			'vigor' => 0,
			'strength' => 0,
			'maxSecId' => 0,
		);
		$this->updateToDb( 'user_info', self::DATA_ACTION_ADD , $this->data );
	}
	
	
	//格试化队列
	public function formatTeam( $heros )
	{
		$this->data['heroTeam'] = $heros;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	/**
	 * 改变金币数量
	 * @param unknown $gold
	 */
	public function changeGold( $gold )
	{
		if( $gold < 0 )
		{
			if( $this->data['gold'] + $gold < 0 )
			{
				return false;
			}
		}
		$this->data['gold'] += $gold;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
		return true;
	}
	
	
	/**
	 * 改变充值币数量 
	 * @param unknown $coin
	 * @return boolean
	 */
	public function changeCoin( $coin )
	{
		if( $coin < 0 )
		{
			if( $this->data['coin'] + $coin < 0 )
			{
				return false;
			}
		}
		$this->data['coin'] += $coin;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
		return true;
	}
	
	
	/**
	 * 改变充值币数量
	 * @param unknown $coin
	 * @return boolean
	 */
	public function changeChips( $chips )
	{
		if( $chips < 0 )
		{
			if( $this->data['chips'] + $chips < 0 )
			{
				return false;
			}
		}
		$this->data['chips'] += $chips;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
		return true;
	}
	
	/**
	 * 设置最大通关数
	 * @param unknown $id
	 */
	public function setMaxSecId( $id )
	{
		$this->data['maxSecId'] = $id;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	/**
	 * 改变经验
	 * @param unknown $exp
	 */
	public function changeExp( $exp )
	{
		$newExp = $this->data['exp'] + $exp;
		$newExp = $newExp ? $newExp : 0;
		
		$newLevel = 1;
		$levelConfig = Common::getConfig( 'level' );
		foreach ( $levelConfig as $lv => $info )
		{
			if( $newExp >= $info['total_exp'] )
			{
				$newLevel = $lv;
			}
			else
			{
				break;
			}
		}
		
		$this->data['exp'] = $newExp;
		$this->data['level'] = $newLevel;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE, $this->data );
	}
	
	
	/**
	 * 设置用户信息
	 * @param unknown $item
	 * @param unknown $value
	 */
	public function setUserInfo( $item , $value )
	{
		if( !isset( $this->data[$item]) || $value < 0 )
		{
			//return;
		}
		$this->data[$item] = $value;
		$this->updateToDb( 'user_info', self::DATA_ACTION_UPDATE ,$this->data );
	}
	
	
	/**
	 * 如果数据为空
	 * @see Data_Abstract::emptyDataWhenloadFromDB()
	 */
	protected function emptyDataWhenloadFromDB( $table )
	{
		//echo 4;
		//如果为空，不要自动创建
	}
	
	
	public function getData()
	{
		return $this->data;
	}

}
