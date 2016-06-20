<?php

/**
 * 装备模块
 * @author liuchangbin
 */
class EquipController extends BaseController
{
	/**
	 * 穿上
	 */
	public function up()
	{
		$heroUid = (int)$this->params['heroUid'];
		$equipUid = (int)$this->params['equipUid'];
		
		if( $heroUid <= 0 || $equipUid <= 0 )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		Equip_Model::getInstance( $this->userId )->equipUp( $heroUid , $equipUid );
		
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'heroList' => Pack_User::heroList( $this->userId ),
				'equipList' => Pack_User::equipList( $this->userId ),
		);
		return $returnData;
	}
	
	/**
	 * 卸下
	 */
	public function down()
	{
		$heroUid = (int)$this->params['heroUid'];
		
		$equipUid = (int)$this->params['equipUid'];
		
		//$equipUid = 1;
		//$heroUid = 2;
		
		if( $heroUid <= 0 || $equipUid <= 0 )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		Equip_Model::getInstance( $this->userId )->equipDown( $heroUid , $equipUid );
		
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'heroList' => Pack_User::heroList( $this->userId ),
				'equipList' => Pack_User::equipList( $this->userId ),
		);
		return $returnData;
		
	}
	
	
	/**
	 * 装备强化
	 * @throws GameException
	 * @return multitype
	 */
	public function reinforce()
	{
		$equipUid = (int)$this->params['equipUid'];
		//$equipUid = 1;
		
		if( $equipUid <= 0 )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		$upLevel = Equip_Model::getInstance( $this->userId )->reinforce( $equipUid );
		
		$returnData = array(
			'userInfo' => Pack_User::userInfo( $this->userId ),
			//英雄列表
			'equipInfo' => Pack_User::equipList( $this->userId , $equipUid ),
			//升级
			'upLevel' => $upLevel,
		);
		return $returnData;
	}
	
	
	/**
	 * 装备洗练
	 */
	public function wash()
	{	
		$equipUid = (int)$this->params['equipUid'];
		//$equipUid = 3;
		$washType = (int)$this->params['washType'];
		
		//$equipUid = 3;
		//$washType = 1;
		
		if( $equipUid <= 0 || $washType <= 0 )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}

		Equip_Model::getInstance( $this->userId )->wash( $equipUid , $washType );
		
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'equipInfo' => Pack_User::equipList( $this->userId , $equipUid ),
		);
		return $returnData;
		
	}
	
	/**
	 * 替换新属性
	 */
	public function confirmWash()
	{
		
		$equipUid = (int)$this->params['equipUid'];
		//$equipUid = 3;
		if( $equipUid <= 0  )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		Equip_Model::getInstance( $this->userId )->confirmWash( $equipUid );
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'equipInfo' => Pack_User::equipList( $this->userId , $equipUid ),
		);
		return $returnData;
		
	}
	
	/**
	 * 去消洗练
	 * @throws GameException
	 * @return multitype:multitype:number string multitype:  Ambigous <multitype:, NULL, unknown, multitype:>
	 */
	public function cancelWash()
	{
		$equipUid = (int)$this->params['equipUid'];
		//$equipUid = 3;
		if( $equipUid <= 0  )
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		Equip_Model::getInstance( $this->userId )->cancelWash( $equipUid );
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'equipInfo' => Pack_User::equipList( $this->userId , $equipUid ),
		);
		return $returnData;
	}
	
	/**
	 * 获取列表
	 */
	public function getList()
	{
		$returnData = array(
			'equipList' => Pack_User::equipList( $this->userId ),	
		);
		return $returnData;
	}
	
	
}
