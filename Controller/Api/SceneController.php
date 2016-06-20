<?php

/**
 * 场景初始化控制器
 * @author	Liuchangbing
 */
class SceneController extends BaseController
{
	/**
	 * 场景初始化
	 * @author	liuchangbing
	 * @return	array(
	 * 				userInfo
	 * 			)
	 */
	public function init()
	{
		//初始化初始数据
		//添加一个英雄
		$userInfo = User_Info::getInstance( $this->userId );
		$userData = $userInfo->getData();
		if( empty( $userData ))
		{
			return array(
				'userInfo' => array(),
			);
		}
	
		$userProfile = User_Profile::getInstance( $this->userId );
		$userProfile->onLogin();
		$heroModel = Hero_Model::getInstance( $this->userId );
		
		$returnData = array(
			'userInfo' => Pack_User::userInfo( $this->userId ),
			//英雄列表
			'heroList' => Pack_User::heroList( $this->userId ),
			'equipList' => Pack_User::equipList( $this->userId ),
			'pSkills' => Pack_User::passiveSKillList( $this->userId ),
			'itemList' => Pack_User::itemList( $this->userId ),
		);
		return $returnData;
	}
	
	
}

?>
