<?php


namespace app\base;


class Session
{
        public function __construct()
    {
        session_start();
    }
    
    /**
     * get
     *
     * @param  mixed $key
     * @return void
     */
    public function get($key)
    {
        return $_SESSION[$key];
    }
    
    /**
     * set
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * empty
     *
     * @param  mixed $key
     * @return void
     */
    public function empty($key)
    {
        return empty($_SESSION[$key]);
    }
    
    /**
     * exists
     *
     * @param  mixed $key
     * @return void
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }
}