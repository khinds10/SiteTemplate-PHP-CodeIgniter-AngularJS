<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Logger
 * 	log system events by log level "error"|"debug"
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
class logger {

    /**
     * log array of mixed type variables to string as a error message
     *
     * @param array $arrayVars
     */
    public static function logVarsError($arrayVars = array()) {
        logger::logError(logger::arrayVarsToString($arrayVars));
    }

    /**
     * log array of mixed type variables to string as a debug message
     *
     * @param array $arrayVars
     */
    public static function logVarsDebug($arrayVars = array()) {
        logger::logDebug(logger::arrayVarsToString($arrayVars));
    }

    /**
     * log error message
     *
     * @param string $message
     */
    public static function logError($message = "") {
        log_message("error", $message);
    }

    /**
     * log debug message but only if system is set to "debug"
     *
     * @param string $message
     */
    public static function logDebug($message = "") {
        log_message("debug", $message);
    }

    /**
     * build list of variable names and values
     *
     * @param array $arrayVars
     * @return string
     */
    public static function arrayVarsToString($arrayVars = array()) {
        $output = "";
        foreach ($arrayVars as $key => $var) {
            $output .= $key . " => " . logger::toString($var) . "\n";
        }
        return $output;
    }

    /**
     * convert any type of input to string
     *
     * @param mixed $mixed
     */
    public static function toString($mixed) {
        return print_r($mixed, true);
    }
}