<?php

/**
 * 装备模块
 * @author liuchangbin
 */
class HeroController extends BaseController
{
	/**
	 * 英雄合成，兑换
	 */
	public function composite()
	{
		$heroId = (int)$this->params['heroId'];
		Hero_Model::getInstance( $this->userId )->composite( $heroId );
		
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'heroList' => Pack_User::heroList( $this->userId ),
		);
		return $returnData;
	}
	
	/**
	 * 分解
	 */
	public function explode()
	{
		$heroUid = (int)$this->params['heroUid'];
		Hero_Model::getInstance( $this->userId )->explode( $heroUid );
	
		$returnData = array(
				'userInfo' => Pack_User::userInfo( $this->userId ),
				//英雄列表
				'heroList' => Pack_User::heroList( $this->userId ),
		);
		return $returnData;
	}
	
	
	/**
	 * 设置小队
	 */
	public function formatTeam()
	{
		$heroUids = (string)$this->params['heroUids'];
		$heroUidArr = explode( ",", $heroUids );
		
		if( empty( $heroUidArr ))
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		
		User_Info::getInstance( $this->userId )->formatTeam( $heroUids );
		$returnData = array(
			'userInfo' => Pack_User::userInfo( $this->userId ),	
		);
		return $returnData;
		
	}
	
	/**
	 * 装备被动技能
	 * 如果英雄ID 大于0表示装备上，否则是卸下
	 */
	public function equipPSkill()
	{
		$heroUid = (int)$this->params['heroUid'];
		$pSkillUid = (int)$this->params['pSkillUid'] ;
		
		if( empty( $pSkillUid ))
		{
			throw new GameException( GameException::PARAM_ERROR );
		}
		$heroUid = $heroUid ? $heroUid : 0;
		Hero_Model::getInstance( $this->userId )->equipSkill( $heroUid , $pSkillUid );
		$returnData = array(
			//英雄列表
			'pSkillInfo' => Pack_User::passiveSKillList( $this->userId , $pSkillUid ),
		);
		
		if( $heroUid > 0 )
		{
			$returnData['heroInfo'] = Pack_User::heroList( $this->userId , $heroUid );
		}
		return $returnData;
	}
	
	
	/**
	 * 英雄列表
	 */
	public function getHeroList()
	{
		$returnData = array(
			'heroList' => Pack_User::heroList( $this->userId ),
		);
		return $returnData;
	}
	
	
	/**
	 * 获取被动技能列表
	 * @return multitype:Ambigous <multitype:, unknown, number, multitype:>
	 */
	public function getPSkillList()
	{
		$returnData = array(
			'pSkills' => Pack_User::passiveSKillList( $this->userId ),
		);
		return $returnData;
	}
	
	
}
