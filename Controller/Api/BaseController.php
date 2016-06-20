<?php 



/**
 * 所有controller的基类
 * @since	2010.11.02
 */
abstract class BaseController
{
	protected $post = array();
	protected $get = array();
	protected $userId = 0;
	protected $config = array();
	protected $params = array();
	
	public function __construct()
	{
		if( get_magic_quotes_gpc() )
		{
			Common::prepareGPCData( $_GET );
			Common::prepareGPCData( $_POST );
		}

		$this->params = json_decode( $_REQUEST['data'] , true );
		
		//$this->get = $this->post = array_merge( $_POST , $_GET );
		//$this->config = Common::getConfig();
	}
	
	public function setUser( $userId )
	{
		$this->userId = $userId;
		if( $userId > 0 )
		{
			if( User_Model::exist($userId) == false 
				&& $_REQUEST['method'] != 'User.create'
				&& $_REQUEST['method'] != 'Scene.init'
			)
			{
				throw new User_Exception( User_Exception::STATUS_USER_NOT_EXIST );
			}
		}
	}
}
?>