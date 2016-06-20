<?php
/**
 * 配置文件
 * @author liuchangbin
 */
class ConfigController extends BaseController
{
	
	/**
	 * 获取配置文件
	 */
 	public function get()
    {
    	$confName = $this->params['confName'];
    	if( $confName )
        {
          $config = Common::getConfig( $confName );
          return array(
              $confName => $config,
           );
        }
        else
        {
           $heroConfig = Common::getConfig( 'hero' );
           $equipConfig = Common::getConfig( 'equip' );
           $skillConfig = Common::getConfig( 'skill' );
           return  array(
                'hero' => $heroConfig,
           		'equip' => $equipConfig,
           		'skill' => $skillConfig,
           );
        }
    }
	
	
	/**
	 * 获取服务器列表
	 */
	public function servers()
	{
		global $gPlatform;
		$serverList = array();
		if( $gPlatform || 1 )
		{
			$config = Common::getConfig( 'servers' );
			foreach ( $config as $conf )
			{
				if( $conf['pf'] == $gPlatform )
				{
					$serverList[$conf['sid']] = $conf;
				}
			}
		}
		echo json_encode( $serverList );
		exit;
	}

}


