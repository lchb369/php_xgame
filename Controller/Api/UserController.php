<?php

/**
 * 用户模块
 * @author liuchangbin
 *
 */
class UserController extends BaseController
{
	
	/**
	 * 用户是否存在
	 */
	public function exist()
	{
		$userInfo = User_Info::getInstance( $this->userId )->getData();
		if( empty( $userInfo ) )
		{
			$exist = 0;
		}
		else 
		{
			$exist = 1;
		}
		$returnData = array(
			'exist' => $exist,
		);
		return $returnData;
	}
	
	
	/**
	 * 创建角色
	 */
	public function create()
	{
		$nickName = strval( $this->params['nickname'] );
		$sex = (int)$this->params['sex'];
		
		if( !$nickName || !$sex )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		//创建用户
		$userInfo = User_Info::getInstance( $this->userId );
		$userInfo->create( $nickName , $sex );
		
		//增加一个武将
		$heroModel = Hero_Model::getInstance( $this->userId );
		$index = $heroModel->addHero( 1 );
		
		//设置武将小队
		$userInfo->formatTeam( $index );
		
		$returnData = array(
			'userInfo' => Pack_User::userInfo( $this->userId ),
			//英雄列表
			'heroList' => Pack_User::HeroList( $this->userId ),
		);
		return $returnData;
	}
	
	
	/**
	 * 使用道具
	 */
	public function useItem()
	{
		$itemId = (int)$this->params['itemId'];
		//$itemId = 1002;
		if( !$itemId )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		$info = Item_Model::getInstance( $this->userId )->useItem( $itemId );
		$returnData = array(
			'getInfo' => $info,
			'itemList' => Pack_User::itemList( $this->userId ),
		);
		return $returnData;
	}
	
	/**
	 * 出售道具
	 * @throws GameException
	 * @return multitype:unknown Ambigous <Ambigous, number, multitype:>
	 */
	public function sellItem()
	{
		$itemId = (int)$this->params['itemId'];
		$num = (int)$this->params['num'];
		$num = $num > 0 ? $num : 1;
		$itemId = 1002;
		
		if( !$itemId )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		$info = Item_Model::getInstance( $this->userId )->sell( $itemId , $num );
		$returnData = array(
				'getInfo' => $info,
				'itemList' => Pack_User::itemList( $this->userId ),
		);
		return $returnData;
		
	}
	
	/**
	 * 获取邮件列表
	 * @return multitype:unknown Ambigous <Ambigous, number, multitype:>
	 */
	public function getMailList()
	{
		$returnData = array(
			'mailList' => Pack_User::mailList( $this->userId ),
		);
		return $returnData;
	}
	
}
