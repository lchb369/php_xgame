<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


class Cache_Apc
{
    public function enable()
    {
        return function_exists('apc_add');
    }

    public function selectDb($db)
    {
        return true;
    }

    public function add($key, $value, $timeOut = 0)
    {
        return apc_add($key, $value, $timeOut);
    }

    public function set($key, $value, $timeOut = 0)
    {
        return apc_store($key, $value, $timeOut);
    }

    public function get($key)
    {
        return apc_fetch($key);
    }

    public function delete($key)
    {
        return apc_delete($key);
    }

    public function increment($key, $step = 1)
    {
        return apc_inc($key, $step);
    }

    public function decrement($key, $step = 1)
    {
        return apc_dec($key, $step);
    }

    public function clear()
    {
        return apc_clear_cache();
    }
}

?>
