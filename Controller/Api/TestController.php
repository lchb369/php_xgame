<?php

/**
 * 场景初始化控制器
 * @author	Liuchangbing
 */
class TestController extends BaseController
{
	/**
	 * 场景初始化
	 * @author	liuchangbing
	 * @return	array(
	 * 				userInfo
	 * 			)
	 */
	//http://211.144.68.31/app/xgame/Web/Api.php?method=User.create&uid=3331446&debug&pf=test&sid=1&nickname=xxxx&sex=2
	public function createUser()
	{
		$con = new UserController();
		$con->create();
		
	}
}

?>
