<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

class IndexController extends AdminBaseController
{
	private $db;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function main()
	{
		$act = empty( $_GET[ 'act' ] ) ? 'user' : $_GET[ 'act' ];
		if( $_GET['uid'] > 0 )
		{
			$_SESSION['uid'] = $_GET['uid'];
		}
		$this->userId = $_SESSION['uid'];
		
		if( !$this->userId )
		{
			$this->display( "user.php" );
			exit;
		}
		
		$this->assign( 'uid' , $this->userId );
		$this->assign( 'act' , $act );
		if( method_exists( $this , $act ) )
		{
			$this->$act();
		}
		exit();
	}
	
	
	
	public function user()
	{
		
		$userInfo = Data_User_Info::getInstance( $this->userId )->getData();
		$userProfile = Data_User_Profile::getInstance( $this->userId )->getData();
		
		if( $_POST['exp'] > 0 )
		{
			//加经验
			$addExp = $_POST['exp'] - $userInfo['exp'];
			User_Info::getInstance( $this->userId )->changeExp( $addExp );
		}	
		
		if(  $_POST['coin'] > 0 && $_POST['coin'] != $userInfo['coin'])
		{
			//加经验
			User_Info::getInstance( $this->userId )->changeCoin( $_POST['coin']-$userInfo['coin'] );
		}
		
		if(  $_POST['gold'] > 0 && $_POST['gold'] != $userInfo['gold'])
		{
			//加经验
			User_Info::getInstance( $this->userId )->changeGold( $_POST['gold']-$userInfo['gold'] );
		}
		
		if(  $_POST['vigor'] > 0 && $_POST['vigor'] != $userInfo['vigor'])
		{
			//加经验
			Data_User_Info::getInstance( $this->userId , true )->setUserInfo( 'vigor' , $_POST['vigor'] );
		}
		
		
		if(  $_POST['strength'] > 0 && $_POST['strength'] != $userInfo['strength'])
		{
			//加经验
			Data_User_Info::getInstance( $this->userId , true )->setUserInfo( 'strength' , $_POST['strength'] );
		}
		
		
		if(  $_POST['chips'] > 0 && $_POST['chips'] != $userInfo['chips'])
		{
			//设置英雄碎片
			Data_User_Info::getInstance( $this->userId , true )->setUserInfo( 'chips' , $_POST['chips'] );
		}
		
		
		if(  $_POST['vip'] > 0 && $_POST['vip'] != $userInfo['vip'])
		{
			//设置英雄碎片
			Data_User_Info::getInstance( $this->userId , true )->setUserInfo( 'vip' , $_POST['vip'] );
		}
		
		
		
		if( !empty( $_POST['heroTeam'] ) && $_POST['heroTeam'] != $userInfo['heroTeam'])
		{
			//设置战斗小队
			User_Info::getInstance( $this->userId )->formatTeam(  trim( $_POST['heroTeam'] ));
		}
			
		ObjectStorage::save();
		$userInfo = Data_User_Info::getInstance( $this->userId )->getData();
		$userProfile = Data_User_Profile::getInstance( $this->userId )->getData();
		$this->assign( 'userInfo', $userInfo );
		$this->assign( 'userProfile', $userProfile );
		$this->display( "user.php" );	
	}
	
	/**
	 * 英雄模块
	 */
	public function heros()
	{
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		
		if( $_POST['heroId'] > 0 )
		{
			Hero_Model::getInstance( $this->userId )->addHero( $_POST['heroId'] );
			ObjectStorage::save();
		}
		
		
		if( $_GET['tag'] )
		{
			$tagArr = explode( "_", $_GET['tag'] );
			$heroUid = $tagArr[1];
			
		
			
			if( $tagArr[0] == 'explode' )
			{
				if( $heroList[$heroUid])
				{
					Hero_Model::getInstance( $this->userId )->explode( $heroUid );
					ObjectStorage::save();
				}
			}
			elseif( $tagArr[0] == 'delhero' )
			{
				if( $heroList[$heroUid])
				{
					Hero_Model::getInstance( $this->userId )->delHero( $heroUid );
					//Data_Hero_Model::getInstance( $this->userId , true )->del( $heroUid );
					ObjectStorage::save();
				}
			}
			
			
		}
		
		
		if( $_GET['heroUid'] && $_GET['oper'] == 'equipup' &&  $_GET['type'] )
		{
			if( $_GET['equipUid'] > 0 )
			{
				Equip_Model::getInstance( $this->userId )->equipUp( $_GET['heroUid'] , $_GET['equipUid'] );
			}
			else 
			{
				$type = $_GET['type'];
				$equipUid = $heroList[$_GET['heroUid']][$type];
				if( $equipUid > 0 )
				{
					Equip_Model::getInstance( $this->userId )->equipDown( $_GET['heroUid'] , $equipUid );
				}
			}
			ObjectStorage::save();
		}
		
		if( $_GET['oper'] == 'changeExp' &&  $_GET['heroUid'] && $_GET['exp'] )
		{
			Hero_Model::getInstance( $this->userId )->addExp( $_GET['heroUid'] , $_GET['exp'] );
			ObjectStorage::save();
		}
		
		$equipList = Data_Equip_Model::getInstance( $this->userId )->getData();
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		$this->assign( 'heroList', $heroList );
		$this->assign( 'equipList', $equipList );
		$this->display( "heros.php" );
		
	}
	
	
	public function equip()
	{
		
		try {
			if( $_POST['equipId'] )
			{
				Data_Equip_Model::getInstance( $this->userId , true )->add( $_POST['equipId'] );
				ObjectStorage::save();
			}
			
			if( $_GET['oper'] == 'changeLevel' && $_GET['equipUid'] &&  $_GET['level'] )
			{
				Data_Equip_Model::getInstance( $this->userId , true )->setEquipLevel( $_GET['equipUid'] , $_GET['level'] );
				ObjectStorage::save();
			}
			
			
			if( $_GET['tag'] )
			{
				$tagArr = explode( "_", $_GET['tag'] );
				$equipUid = (int)$tagArr[1];
				
				if( $_GET['oper'] == 'wash')
				{
					Equip_Model::getInstance( $this->userId )->wash( $equipUid , 1 );
				}
				elseif(  $_GET['oper'] == 'cwash' )
				{
					Equip_Model::getInstance( $this->userId )->confirmWash( $equipUid );
				}
				elseif(  $_GET['oper'] == 'dwash' )
				{
					Equip_Model::getInstance( $this->userId )->cancelWash( $equipUid );
				}
				elseif(  $_GET['oper'] == 'delEquip' )
				{
					Equip_Model::getInstance( $this->userId )->delEquip( $equipUid );
				}
				ObjectStorage::save();
			}
		}
		catch( Exception $e )
		{
			$code = $e->getCode();
		}
		
		$equipList = Data_Equip_Model::getInstance( $this->userId )->getData();
		$this->assign( 'equipList', $equipList );
		$this->display( "equip.php" );
	}
	
	/**
	 * 道具模块
	 */
	public function item()
	{
		$itemList = Item_Model::getInstance( $this->userId )->getData();
		try {
			
			if( $_GET['oper'] == 'changeItem' && $_GET['itemId'] &&  $_GET['num'] )
			{
				$itemId = $_GET['itemId'];
				$addNum = $_GET['num']- $itemList[$itemId]['num'];
			
				Item_Model::getInstance( $this->userId , true )->changeItem( $_GET['itemId'] , $addNum );
				ObjectStorage::save();
			}
			else 
			{
				if( $_POST['itemId'] )
				{
					Item_Model::getInstance( $this->userId )->changeItem( $_POST['itemId'] , 1 );
					ObjectStorage::save();
				}
			}	
				
	
			if( $_GET['tag'] )
			{
				if( $_GET['oper'] == 'delItem')
				{
					$itemId = (int) $_GET['tag'];	
					Item_Model::getInstance( $this->userId )->changeItem( $itemId , -$itemList[$itemId]['num'] );
					ObjectStorage::save();
				}
				
			}
		}
		catch( Exception $e )
		{
			$code = $e->getCode();
		}
	
		$itemList = Item_Model::getInstance( $this->userId )->getData();
		$this->assign( 'itemList', $itemList );
		$this->display( "item.php" );
	}
	
	
	public function skill()
	{
		if( $_POST['skillId'] )
		{
			Data_Hero_PassiveSkill::getInstance( $this->userId , true )->add( $_POST['skillId'] );
			ObjectStorage::save();
		}
		
		if( $_GET['tag'] )
		{
			$tagArr = explode( "_", $_GET['tag'] );
			$skillUid = (int)$tagArr[1];
			
			if( $tagArr[0] == 'delskill')
			{
				//Data_Hero_PassiveSkill::getInstance( $this->userId , true )->del($skillUid );
				Hero_Model::getInstance( $this->userId )->delPSkill( $skillUid );
				ObjectStorage::save();
			}
		}
		
		
		if( $_GET['oper'] == 'equipup' && isset( $_GET['heroUid'] ) &&  $_GET['skillUid'] > 0 )
		{
			Hero_Model::getInstance( $this->userId )->equipSkill( $_GET['heroUid'] , $_GET['skillUid'] );
			ObjectStorage::save();
		}
		
		
		$heroList = Data_Hero_Model::getInstance( $this->userId )->getData();
		$skillList = Data_Hero_PassiveSkill::getInstance( $this->userId )->getData();
		
		//print_r( $skillList );exit;
		
		$this->assign( 'heroList', $heroList );
		$this->assign( 'skillList', $skillList );
		$this->display( "skill.php" );
	}
	
	
	public function chapter()
	{
		
		if( $_POST['pass'] == 1 )
		{
			$secId = $_POST['secId'];
			Chapter_Model::getInstance( $this->userId )->passChapterSec( $secId , 1 );
			ObjectStorage::save();
		}
		
		$chapterList = Data_Chapter_Model::getInstance( $this->userId )->getData();
		$this->assign( 'chapterList', $chapterList );
		$this->display( "chapter.php" );
	}
	
	
	/**
	 * 邮件礼包
	 */
	public function mailPackage()
	{
		
		if( $_POST['mod'] == 'addPack')
		{
			//将提交数据打包
			$packArr = array();
			if( $_POST['itemId'] > 0 && $_POST['itemNum'] > 0 )
			{
				$packArr[] = array(
					'item' => array(
						'id' => (int)$_POST['itemId'],
						'num' => (int)$_POST['itemNum'],
					),
				);
			}
			
			if( $_POST['equipId'] > 0  )
			{
				$packArr[] = array(
						'equip' => array(
								'id' => (int)$_POST['equipId'],
								'num' => 1,
						),
				);
			}
			
			
			if( $_POST['heroId'] > 0  )
			{
				$packArr[] = array(
						'heroId' => (int)$_POST['heroId'],
				);
			}
			
			if( $_POST['gold'] > 0  )
			{
				$packArr[] = array(
						'gold' => (int)$_POST['gold'],
				);
			}
			
			if( $_POST['coin'] > 0  )
			{
				$packArr[] = array(
						'coin' => (int)$_POST['coin'],
				);
			}
			
			$packInfo = json_encode( $packArr );
			$packName = $_POST['packName'];
			$packDesc = $_POST['packDesc'];
			
			//添加礼包
			//Data_
			if(  $packName && $packDesc )
			{
				Data_Mail_Package::getInstance( 1 , true )->addPackage( $packInfo , $packName , $packDesc );
				ObjectStorage::save();
			}
			
			
			
		}
		
		if( $_GET['oper'] == 'del' )
		{
			$id = $_GET['id'];
			if( $id )
			{
				Data_Mail_Package::getInstance( 1 , true )->delPack( $id );
				ObjectStorage::save();
			}
			
		}
		
		
		
		$packList = Data_Mail_Package::getInstance( 1 )->getData();
		$this->assign( 'packList', $packList );
		$this->display( "mailPackage.php" );
	}
	
	/**
	 * 邮件列表
	 */
	public function mail()
	{
		$packList = Data_Mail_Package::getInstance( 1 )->getData();
		if( $_POST['mod'] == 'selectPack' )
		{
			$packId = $_POST['packId'];
			if( $packId > 0 && $packList[$packId] )
			{
				Data_Mail_Model::getInstance( $this->userId  , true )->addMail( $packList[$packId]  );
				ObjectStorage::save();
			}
		}
		
		$mailList = Data_Mail_Model::getInstance( $this->userId )->getData();
		$this->assign( 'packList', $packList );
		$this->assign( 'mailList', $mailList );
		$this->display( "mail.php" );
	}
	
}

?>
