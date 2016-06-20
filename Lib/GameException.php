<?php
class GameException extends Exception 
{
	/**
	 * 参数错误
	 * @var unknown_type
	 */
	const PARAM_ERROR = 100;
	/**
	 * 
	 * @param unknown_type $code
	 */
	const DB_SQL_ERROR = 101;
	
	
	
	public function __construct( $code = 0 , $message = null )
	{
		parent::__construct( $message , $code );   
	}
}
?>