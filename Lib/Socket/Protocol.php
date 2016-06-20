<?php

class Socket_Protocol
{
	
    //1-8 表示频道  1004  2032 3128
    private $_cmd = 0;
    
    private $_uid = 0;
 
    private $_sid = 0;
       
    private $_params = array();
    
    private $_buffer = array();
    
    private $fd;
    
    
    private static $instance = null; 
    
    
    public static function getInstance()
    {
    	if( self::$instance == null )
    	{
    		self::$instance = new self();
    	}
    	return self::$instance;
    } 
    
    
   	protected function __construct()
   	{
   		
   	}
    

    /**
	* client包格式： 包总长+action+method+params(json_encode)
	* 包总长+cmd+uid+params(json_encode)
	* server包格式：包总长+数据(json_encode)
	* @param $_data
	* @return bool
	*/
    public function parse( $data )
    {
        if( !empty( $this->_buffer[$this->fd] ))
        {
            $data = $this->_buffer[$this->fd] . $data;
        }
    
		echo "fd".$this->fd."\n";    
        
        $packerObj = new Socket_MessagePacker( $data );
        
     	//从包头获取包长
        $packLen = $packerObj->readInt();
        
        //数据长度
        $dataLen = strlen( $data );
        
        //如果收到的是半包，则将数据先放入buff,不处理
        if ($packLen > $dataLen)
        {
            $this->_buffer[$this->fd] = $data;
            return false;
        }
        //如果收到的包比包体长，则是粘包,截取包体
        elseif( $packLen < $dataLen )
        {
            $this->_buffer[$this->fd] = substr( $data , $packLen, $dataLen - $packLen);
        }
        else 
        {
        	
            if (!empty($this->_buffer[$this->fd])) {
                unset($this->_buffer[$this->fd]);
            }
        }
        
        $this->_cmd = $packerObj->readInt();
        $this->_sid = $packerObj->readInt();
        $this->_uid = $packerObj->readInt();

        $params = $packerObj->readString(); 
		$params = trim( $params );

		$this->_params = json_decode( $params , true );
		return true;
    }

    public function setFd($fd)
    {
        $this->fd = $fd;
    }

    public function getCmd()
    {
        return $this->_cmd;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function getSid()
    {
	return $this->_sid;
    }

    public function getParams()
    {
        return $this->_params;
    }

    
    public function encode( $model )
    {
        $data = json_encode($model);
        $dataLen = strlen($data);
        $pack = new Socket_MessagePacker();
        $pack->writeInt($dataLen);
        $pack->writeString($data);
        return $pack->getData();
    }
}
