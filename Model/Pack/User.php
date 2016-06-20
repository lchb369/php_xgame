<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

class Pack_User
{
	/**
	 * 
	 * 生成客户端用户信息数据
	 * @param unknown_type $userId
	 */
	public static function userInfo( $userId )
	{
		$userProfile = User_Profile::getInstance( $userId )->getData();
		$userInfo = User_Info::getInstance( $userId )->getData();
		
		$heroTeam = array();
		if( $userInfo['heroTeam'] != 'null' )
		{
			$heroTeam = explode( ",", $userInfo['heroTeam'] );
		}
		
		$userData = array(
			'uid' => (int)$userId,
			'sex' => (int)$userInfo['sex'],
			'exp' => (int)$userInfo['exp'],
			'level' => (int)$userInfo['level'],
			'gold' => (int)$userInfo['gold'],
			'coin' => (int)$userInfo['coin'],
			'chips' => (int)$userInfo['chips'] ,
			'vigor' => (int)$userInfo['vigor'] ,
			'strength' => (int)$userInfo['strength'] ,
			'vip' => (int)$userInfo['vip'] ,
			'maxSecId' => (int)$userInfo['maxSecId'] ,
			'nickname' => (string)$userInfo['nickname'],
			'heroTeam' => $heroTeam,
			'loginTime' => (int)$userProfile['loginTime'],
			'registerTime' => (int)$userProfile['registerTime'],
			'keepLoginDays' => (int)$userProfile['keepLoginDays'],
			'totalLoginDays' => (int)$userProfile['totalLoginDays'],
			'todayLoginTimes' => (int)$userProfile['todayLoginTimes'],
		);
		return $userData;
	}

	
	/**
	 * 英雄列表
	 * @param unknown $userId
	 * @return multitype:
	 */
	public static function heroList( $userId , $heroUid = 0 )
	{
		$heroList = Data_Hero_Model::getInstance( $userId )->getData();
		$userInfo = User_Info::getInstance( $userId )->getData();
		$heroTeam = explode( ",", $userInfo['heroTeam'] );
		
		foreach ( $heroList as $key => $hero )
		{
			$heroList[$key]['id'] = (int)$hero['id'];
			$heroList[$key]['heroCid'] = (int)$hero['heroCid'];
			$heroList[$key]['exp'] = (int)$hero['exp'];
			$heroList[$key]['level'] = (int)$hero['level'];
			$heroList[$key]['skillExp'] = (int)$hero['skillExp'];
			$heroList[$key]['skillCid'] = (int)$hero['skillCid'];
			
			if( in_array( $hero['id'] , $heroTeam ))
			{
				$heroList[$key]['weaponId'] = (int)$hero['weaponId'];
				$heroList[$key]['armorId'] = (int)$hero['armorId'];
				$heroList[$key]['accId'] = (int)$hero['accId'];
				$heroList[$key]['pSkillId'] = (int)$hero['pSkillId'];
			}
			else
			{
				unset( $heroList[$key]['weaponId'] );
				unset( $heroList[$key]['armorId'] );
				unset( $heroList[$key]['accId'] );
				unset( $heroList[$key]['pSkillId'] );
			}
		}
		
		if( $heroUid > 0 )
		{
			return $heroList[$heroUid];
		}
		return $heroList ? $heroList : null;
	}

	
	/**
	 * 装备列表
	 * @param unknown $userId
	 * @return multitype:
	 */
	public static function equipList( $userId , $equipUid = 0 )
	{
		
		//默认装备都是没有装上的
		//根据小队列表
		$userInfo = User_Info::getInstance( $userId )->getData();
		$heroTeam = array();
		$equipedIds = array();
		
		$heroTeam = explode( ",", $userInfo['heroTeam'] );
		foreach ( $heroTeam as $heroId )
		{
			if( $heroId > 0 )
			{
				$heroInfo = self::heroList( $userId, $heroId );
				$equipedIds[$heroInfo['weaponId']] = $heroId;
				$equipedIds[$heroInfo['armorId']] = $heroId;
				$equipedIds[$heroInfo['accId']] = $heroId;
			}
		}

		$equipList = Data_Equip_Model::getInstance( $userId )->getData();
		foreach ( $equipList as $key => $info )
		{
			$equipList[$key]['id'] = (int)$info['id'];
			$equipList[$key]['level'] = (int)$info['level'];
			$equipList[$key]['equipCid'] = (int)$info['equipCid'];
			
			if( $equipedIds[$info['id']] > 0 )
			{
				$equipList[$key]['heroId'] = (int)$equipedIds[$info['id']];
			}
			else 
			{
				$equipList[$key]['heroId'] = 0;
			}
		}
		
		if( $equipUid == 0 )
		{
			return $equipList ? $equipList : null ;
		}
		else 
		{
			return $equipList[$equipUid] ? $equipList[$equipUid] : null ;
		}
	}
	
	
	/**
	 * 被动技能列表
	 * @param unknown $userId
	 * @return multitype:
	 */
	public static function passiveSKillList( $userId , $pskillUid = 0 )
	{
		//默认装备都是没有装上的
		//根据小队列表
		$userInfo = User_Info::getInstance( $userId )->getData();
		$heroTeam = array();
		$equipedIds = array();
		
		$heroTeam = explode( ",", $userInfo['heroTeam'] );
		foreach ( $heroTeam as $heroId )
		{
			if( $heroId > 0 )
			{
				$heroInfo = self::heroList( $userId, $heroId );
				$equipedIds[$heroInfo['pSkillId']] = $heroId;
			}
		}
		
		$pskillList = Data_Hero_PassiveSkill::getInstance( $userId )->getData();
		if( $pskillList )
		{
			foreach ( $pskillList as $key => $info )
			{
				$pskillList[$key]['id'] = (int)$info['id'];
				$pskillList[$key]['skillCid'] = (int)$info['skillCid'];
				if( $equipedIds[$info['id']] > 0 )
				{
					$pskillList[$key]['heroId'] = (int)$equipedIds[$info['id']];
				}
				else
				{
					$pskillList[$key]['heroId'] = 0;
				}
				unset( $pskillList[$key]['skillLevel'] );
			}
		}
		
		if( $pskillUid > 0 )
		{
			return $pskillList[$pskillUid];
		}
		return  $pskillList;
	}
	
	/**
	 * 道具列表
	 * @param unknown $userId
	 * @return Ambigous <number, multitype:>
	 */
	public static function itemList( $userId )
	{
		$itemList = Data_Item_Model::getInstance( $userId )->getData();
		if( $itemList )
		{
			foreach ( $itemList as $key => $item )
			{
				$itemList[$key]['id'] = (int)$item['id'];
				$itemList[$key]['num'] = (int)$item['num'];
			}
		}
		return $itemList;
	}
	
	
	/**
	 * 邮件列表
	 * @param unknown $userId
	 * @return Ambigous <number, multitype:>
	 */
	public static function mailList( $userId )
	{
		$mailList = Data_Mail_Model::getInstance( $userId )->getData();
	//	print_r( $mailList );
		if( $mailList )
		{
			foreach ( $mailList as $key => $item )
			{
				$itemList[$key]['id'] = (int)$item['id'];
				$itemList[$key]['sendTime'] = (int)$item['sendTime'];
			}
		}
		return $mailList;
	}
	
	
}
