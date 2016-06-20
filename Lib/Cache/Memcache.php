<?php

class Cache_Memcache
{
	
	private $cache; 
	/**
	 * 初始化Redis
	 * $config = array(
	 *  'server' => '127.0.0.1' 服务器
	 *  'port'   => '6379' 端口号
	 * )
	 * @param array $config
	 */
	public function __construct( $config = array() )
	{
		if ($config['host'] == '')  $config['host'] = '127.0.0.1';
		if ($config['port'] == '')  $config['port'] = 11211;
		
		$this->cache = new Memcache();
		$this->cache->addserver( '127.0.0.1' , 11211 );
		return $this->cache;
	}
	
	/**
	 * 设置值
	 * @param string $key KEY名称
	 * @param string|array $value 获取得到的数据
	 * @param int $timeOut 时间
	 */
	
	public function set($key, $value, $timeOut = 0 ) 
	{
		return $this->cache->set( $key , $value , MEMCACHE_COMPRESSED , $timeOut );
	}
	
	/**
	 * 通过KEY获取数据
	 * @param string $key KEY名称
	 */
	
	public function get($key)
	{
		return $this->cache->get($key);
	}
	
	/**
	 * 删除一条数据
	 * @param string $key KEY名称
	 */
	public function delete($key)
	{
		return $this->cache->delete($key);
	}
	
	/**
	 * 清空数据
	 */
	public function flushAll()
	{
		return $this->cache->flushAll();
	}
	
	
	/**
	 * 数据自增
	 * @param string $key KEY名称
	 */
	public function increment($key)
	{
		return $this->cache->incr($key);
	}
	
	/**
	 * 数据自减
	 * @param string $key KEY名称
	 */
	public function decrement($key)
	{
		return $this->cache->decr($key);
	}

}