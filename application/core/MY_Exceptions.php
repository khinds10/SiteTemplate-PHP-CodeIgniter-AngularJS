<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Exceptions Controller
 * extend exceptions for advanced error handling
 *
 * @copyright Kevin Hinds @ KevinHinds.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *	http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class MY_Exceptions extends CI_Exceptions {

    /**
     * construct custom exceptions handler
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * (non-PHPdoc)
     *
     * @see CI_Exceptions::show_error()
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        if ($status_code == 500) {
            $this->reportErrorCompleteInfo($message);
        }
        
        /**
         * professionally produce the error output as an actual website page
         */
        include APPPATH . '/helpers/ui_helper.php';
        ob_start();
        
        /**
         * turn off the AngularJS ngCloaking
         */
        load_view('global/header', array(
            'htmlAttributes' => ''
        ));
        load_view('global/navigation');
        print parent::show_error($heading, $message, $template = 'error_general', $status_code = 500);
        load_view('global/footer');
        $errorOutput = ob_get_contents();
        @ob_end_clean();
        return $errorOutput;
    }

    /**
     * (non-PHPdoc)
     *
     * @see CI_Exceptions::log_exception()
     */
    public function log_exception($severity, $message, $filepath, $line) {
        parent::log_exception($severity, $message, $filepath, $line);
        
        /**
         * if the error is fatal then we must log with as much info as possible
         */
        if ($severity != E_WARNING && $severity != E_NOTICE && $severity != E_STRICT) {
            $this->reportErrorCompleteInfo($message);
        }
    }

    /**
     * get a formatted backtrace of the error
     *
     * @return string
     */
    protected function get_debug_backtrace() {
        ob_start();
        debug_print_backtrace();
        $msg = ob_get_contents();
        @ob_end_clean();
        return $msg;
    }

    /**
     * report critical error to the log with as much information as possible
     *
     * @param string $messageTitle            
     */
    protected function reportErrorCompleteInfo($messageTitle) {
        $messageBody = "Code Igniter Critical Error: \"" . $messageTitle . "\"\n";
        $messageBody .= "\nREQUEST \n";
        $messageBody .= "----------------\n\n";
        foreach ($_REQUEST as $k => $v) {
            $messageBody .= $k . " => " . $v . "\n";
        }
        $messageBody .= "\n\nSERVER \n";
        $messageBody .= "----------------\n\n";
        foreach ($_SERVER as $k => $v) {
            $messageBody .= $k . " => " . $v . "\n";
        }
        $messageBody .= "\n\nSTACKTRACE \n";
        $messageBody .= "----------------\n\n";
        $messageBody .= $this->get_debug_backtrace();
        
        log_message("error", $messageBody);
    }
} 