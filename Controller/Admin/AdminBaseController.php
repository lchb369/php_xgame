<?php 

if( !defined( 'IN_INU' ) )
{
	return;
}
/**
 * 所有controller的基类
 * @author	Luckyboys
 * @since	2010.11.02
 */
abstract class AdminBaseController
{
	protected $post = array();
	protected $get = array();
	protected $userId = 0;
	protected $config = array();
	private $tplData = array();
	protected $adminObj = array();
	protected $adminType = 0;
	
	public function __construct()
	{
		if( get_magic_quotes_gpc() )
		{
			Common::prepareGPCData( $_GET );
			Common::prepareGPCData( $_POST );
		}

		$_POST = $_GET = $this->get = $this->post = array_merge( $_POST , $_GET );
		$this->config = Common::getConfig();
		$this->login();
	}
	
	public function login()
	{
		if( empty( $_SESSION[ 'admin' ] ) )
		{
			$_SESSION[ 'admin' ] = 11111;
			$this->display( 'login.php' );
			exit;
		}
		else
		{
			$this->main();
		}
	}
	
	public function logout()
	{
		session_unset();
		session_destroy();
		header("location:index.php");
	}
	
	/**
	 * 设置模板变量
	 */
	protected function assign( $key , $value )
	{
		$this->tplData[$key] = $value;
	}
	
	/**
	 * 展示模板
	 */
	protected function display( $tpl )
	{
		extract( $this->tplData );
		include TPL_DIR.'/'.$tpl;
	}
		
	/**
	 * 主程序
	 */
	abstract protected function main();
}
?>
