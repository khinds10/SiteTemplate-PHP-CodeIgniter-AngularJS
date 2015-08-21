<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * use PHP native sessions
 *  because codeIgniter saves sessions to limited 4Kb cookies
 *
 * @author khinds
 */
class appsession {

    /**
     * construct session
     */
    public function __construct() {
        session_start();
    }

    /**
     * set session value by key
     *
     * @param string $key            
     * @param mixed $value          
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * get session value by key
     *
     * @param string $key            
     * @return Ambigous <NULL, unknown>
     */
    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * regenerate a user session id
     * 
     * @param string $delOld            
     */
    public function regenerateId($delOld = false) {
        session_regenerate_id($delOld);
    }

    /**
     * remove session value by key
     * 
     * @param string $key            
     */
    public function delete($key) {
        unset($_SESSION[$key]);
    }
}