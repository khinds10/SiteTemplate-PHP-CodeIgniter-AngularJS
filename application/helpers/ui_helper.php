<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * render relative URL path for static resource as a cloud based static URL
 *
 * @param string $url            
 * @return string
 */
if (! function_exists('getStaticResourceURL')) {

    function getStaticResourceURL($url) {
        $appendValue = (stripos($url, "?") === false) ? '?' . getStaticAppendValue() : '&' . getStaticAppendValue();
        $prependValue = preg_replace("#^(http|https)://#", "", STATIC_RESOURCES_PREPEND);
        $staticURL = str_replace("//", "/", $prependValue . $url . $appendValue);
        return (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true) ? 'https://' . $staticURL : 'http://' . $staticURL;
    }
}

/**
 * get the special append value key to insert into a static resource URL for automatic re-caching
 *
 * @return string
 */
if (! function_exists('getStaticAppendValue')) {

    function getStaticAppendValue() {
        return "version=" . STATIC_RESOURCES_APPEND;
    }
}

/**
 * this helper can be used to load a view directly from the file system to be included as output
 *
 * @param string $viewName
 *            name of the view file to be included
 * @param $data array
 *            of variables to be included as local view variables
 * @param string $viewRootFolder
 *            if the view exists outside of the traditional '/views/' directory you can specify here
 *            
 */
if (! function_exists("load_view")) {

    function load_view($viewName, $data = array(), $viewRootFolder = 'views/') {
        $viewFile = get_view_path($viewName, $viewRootFolder);
        ob_start();
        extract($data);
        include $viewFile;
        $content = ob_get_contents();
        @ob_end_clean();
        print $content;
    }
}

/**
 * get the path for a view file to be included
 * you can also chose a root folder to search outside of the traditional '/views/' directory
 *
 * @param string $viewName            
 * @param string $viewRootFolder            
 * @return string
 */

if (! function_exists("get_view_path")) {

    function get_view_path($viewName, $viewRootFolder = 'views/') {
        $target_file = APPPATH . $viewRootFolder . $viewName . '.php';
        if (file_exists($target_file))
            return $target_file;
    }
}

/**
 * create a complete dropdown of countries
 * w/ the option of preselecting one of the values
 *
 * @param string $preSelectedCountryCode            
 */
if (! function_exists('createCountryDropdown')) {

    function createCountryDropdown($preSelectedCountryCode = '') {
        global $websiteCountriesList;
        $output = "";
        foreach ($websiteCountriesList as $countryCode => $countryName) {
            ($preSelectedCountryCode == $countryCode) ? $selected = "selected=\"selected\"" : $selected = "";
            $output .= "<option value=\"{$countryCode}\" $selected>{$countryName}</option>\n";
        }
        return $output;
    }
}
