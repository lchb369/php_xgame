<?php

error_reporting( E_ALL ^ E_NOTICE );
ini_set( 'display_errors' , 'On' );


session_start();
$time = microtime( true );
define( "IN_INU" , true );


define( "ROOT_DIR" , dirname( dirname(  __FILE__ ) ) );
define( "MOD_DIR" , ROOT_DIR ."/Model" );
define( "LIB_DIR" , ROOT_DIR ."/Lib" );
define( "CON_DIR" , ROOT_DIR ."/Controller/Admin" );
define( "CONFIG_DIR" , ROOT_DIR . "/Config" );
define( "TPL_DIR" , ROOT_DIR . "/Tpl" );


//平台ID
$gPlatform = $_REQUEST['pf'];
//服务器ID
$gServerId = $_REQUEST['sid'];


$gPlatform = "test";
$gServerId = 1;


include LIB_DIR .'/Common.php';
$con = empty( $_GET['mod'] ) ? 'IndexController' :  ucfirst( strtolower( $_GET['mod'] ) ) . 'Controller';
$act = empty( $_GET['act'] ) ? 'run' : $_GET['act'];
$conFile = CON_DIR . "/{$con}.php";

if( file_exists( $conFile ) )
{
        include $conFile;
        $object = new $con;
        if( method_exists( $object , $act ) )
        {
                $result = $object->$act();
                return ;
        }
}

?>
