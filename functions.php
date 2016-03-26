<?php
/**
 * Created by: Filipe Mota de Sá - pihh.rocks@gmail.com
 * Date: 26/03/2016
 * Time: 22:07
 * Requirements:
 *  .env file
 */

/**
|---------------------------
| Environment setup function, opens .env file, reads it's contents and set's the environment
| @Requires: .env
|---------------------------
 */

function environment_setup(){
    if (0 === ENV_SETUP) {

        // Set env setup to 1 so this function runs once
        define('ENV_SETUP', 1);
        $env = ENV_PATH;

        // Loop through the file
        foreach($env as $line){
            // Remove comments
            $line = strpos($line, '#') ? substr($line, 0, strpos($line, '#')) : $line;

            // If not empty -> POW
            if(strlen(trim($line))>0){

               //  Set it on the environment
               putenv (trim($line));
               $newEnvVar = explode('=',$line);

               //  Set it on $_ENV
               if(isset($newEnvVar[1])) {
                  $key = trim($newEnvVar[0]);
                  $value = trim($newEnvVar[1]);
                  $_ENV[$key] = $value;
               }
            }   // enf if not empty
        }   // end foreach
    }   // end if env
}

/**
|---------------------------
| Gets browser language
|
|---------------------------
 */

function get_browser_language(){
    $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    return $lang;
}

/**
|---------------------------
| Generates a random string
| @Params: length : number, valid characters : string
| @Returns: random string : string
|---------------------------
 */

function generate_random_string($length = false, $valid_chars = false){

    ($length) ? $length = $length : $length = 64;
    ($valid_chars) ? $valid_chars = $valid_chars : $valid_chars = "asdfghjklqwertyuiopzxcvbnm1234567890" ;

    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}

/**
|---------------------------
| Get's current url
| @Return: string
|---------------------------
 */

function get_url(){

    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
    $url .= $_SERVER["REQUEST_URI"];
    return $url;

}

/**
|---------------------------
| Get's client ip address
| @Return: string
|---------------------------
 */

function get_ip(){

    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

/**
|---------------------------
| Returns an excerpt of a string
| @Params:
|   1. string: string (string that we want to trim)
|   2. size : number (number of characters in that string)
|   3. ending: string (example: read more: )
|---------------------------
 */

function excerpt($string, $size = 30 ,$excerpt_ending = '...'){
    return substr ( $string , 0, $size).$excerpt_ending;
}

/**
|---------------------------
| Checks if some substring exists in string
| @Return bool
|---------------------------
 */

function string_contains($substring, $string) {
    $pos = strpos($string, $substring);
    if($pos === false) {
        // string needle NOT found in haystack
        return false;
    }
    else {
        // string needle found in haystack
        return true;
    }
}

/**
|---------------------------
| Dumps a detailed error report
| @Return string
|---------------------------
 */

function detailed_error_report(){
    function mfr_super_errors(){
    // - Display Errors
        ini_set('display_errors', 'On');
        ini_set('html_errors', 0);

    // - Error Reporting
        error_reporting(-1);

    // - Shutdown Handler
        function ShutdownHandler()
        {
            if(@is_array($error = @error_get_last()))
            {
                return(@call_user_func_array('ErrorHandler', $error));
            };

            return(TRUE);
        };

        register_shutdown_function('ShutdownHandler');

    // - Error Handler
        function ErrorHandler($type, $message, $file, $line)
        {
            $_ERRORS = Array(
                0x0001 => 'E_ERROR',
                0x0002 => 'E_WARNING',
                0x0004 => 'E_PARSE',
                0x0008 => 'E_NOTICE',
                0x0010 => 'E_CORE_ERROR',
                0x0020 => 'E_CORE_WARNING',
                0x0040 => 'E_COMPILE_ERROR',
                0x0080 => 'E_COMPILE_WARNING',
                0x0100 => 'E_USER_ERROR',
                0x0200 => 'E_USER_WARNING',
                0x0400 => 'E_USER_NOTICE',
                0x0800 => 'E_STRICT',
                0x1000 => 'E_RECOVERABLE_ERROR',
                0x2000 => 'E_DEPRECATED',
                0x4000 => 'E_USER_DEPRECATED'
            );

            if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
            {
                $name = 'E_UNKNOWN';
            };

            return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
        };

        $old_error_handler = set_error_handler("ErrorHandler");

    }
}

/**
|---------------------------
| Prints a variable and stops the script
| @Return HTML
|---------------------------
 */

function print_and_die($var){
    echo '<pre><small>';
    var_dump($var);
    echo '</small></pre>';
}

/**
|---------------------------
| Check missing requirements for this framework
| @Return requirments that miss : array
|---------------------------
 */

function check_requirements(){

    $requirements = array(
        'php_version'   => 'check'
    );
    // Php version
    if(phpversion() < 5.6){
        $requirements['php_version']  = 'FAIL';
    }


    // Return
    return print_and_die($requirements);
}
