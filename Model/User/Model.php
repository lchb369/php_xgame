<?php
/**
 * 武将模块
 * @author liuchangbin
 */
class User_Model
{
	public static function exist( $userId )
	{
		$cache = & Common::getCache();
		if( $cache == false )
		{
			return false;
		}
		$userInfoKey = $userId."_user_profile";
		$data = $cache->get( $userInfoKey );
		if( !$data )
		{
		  $db = Common::getDB( $userId );
		  $data = $db->find( 'user_profile' );
		}
		return $data ? true : false;
	}
	
	
	
	
}