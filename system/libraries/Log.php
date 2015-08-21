<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package CodeIgniter
 * @author Rick Ellis
 * @copyright Copyright (c) 2006, EllisLab, Inc.
 * @license http://www.codeignitor.com/user_guide/license.html
 * @link http://www.codeigniter.com
 * @since Version 1.0
 * @filesource
 *
 */
    
// ------------------------------------------------------------------------

/**
 * Logging Class REPLACEMENT to use Syslog because we want to
 *
 * @package CodeIgniter
 * @subpackage Libraries
 * @category Logging
 * @author Rick Ellis
 * @link http://www.ellislab.com/codeigniter/user-guide/general/errors.html
 */
class CI_Log {

    var $_threshold = 1;

    var $_enabled = TRUE;

    var $_levels = array(
        'ERROR' => '1',
        'DEBUG' => '2',
        'INFO' => '3',
        'ALL' => '4'
    );

    /**
     * Constructor
     *
     * @access public
     * @param
     *            string the log file path
     * @param
     *            string the error threshold
     * @param
     *            string the date formatting codes
     */
    function CI_Log() {
        $config = & get_config();
        
        if (is_numeric($config['log_threshold'])) {
            $this->_threshold = $config['log_threshold'];
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Write Log File
     *
     * Generally this function will be called using the global log_message() function
     *
     * @access public
     * @param
     *            string the error level
     * @param
     *            string the error message
     * @param
     *            bool whether the error is a native PHP error
     * @return bool
     */
    function write_log($level = 'error', $msg, $php_error = FALSE) {
        
        if ($this->_enabled === FALSE) {
            return FALSE;
        }
        
        $level = strtoupper($level);
        
        if (! isset($this->_levels[$level]) or ($this->_levels[$level] > $this->_threshold)) {
            return FALSE;
        }
        
        $message = '';
        $message .= $level . ' - ' . $msg . "\n";
        
        /** use syslog if the configuration says, else standard error_log */
        if (strtolower(USE_SYSLOG) == 'true') {
            openlog("PHP", LOG_PID, LOG_USER);
            syslog(LOG_ERR, $message);
            closelog();
        } else {
            error_log($message);
        }
        
        return true;
    }
}
// END Log Class