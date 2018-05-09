<?php

namespace App\Helpers;

class AppTools
{     
    public static function instance(){
         return new AppTools();
    }

    public static function printArray($arr) {
        echo '<br><br><pre>';
        print_r($arr);
        echo '</pre>';
    }

    public static function cleanInput($buffer, $type = 'string', $decimal_places = 2) {
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', trim($buffer));
        if ($buffer != '' AND $type == 'numeric') {
            $buffer = str_replace(',', '', $buffer);
            $buffer = preg_replace("/[^0-9.]/", "", $buffer);
        } else if ($buffer != '' AND $type == 'decimal') {
            $buffer = preg_replace("/[^0-9.]/", "", $buffer);
            $buffer = number_format($buffer, $decimal_places, '.', '');
            $buffer = str_replace(',', '', $buffer);
        } else if ($buffer != '' AND $type == 'date') {
            if (self::validateDateFormat($buffer, 'Y-m-d') == false) {
                $buffer = '0000-00-00';
            }
        }
        return $buffer;
    }

    public static function cleanValue($buffer, $return = null) {
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', trim($buffer));
        if ($buffer == '' OR ! isset($buffer))
            return null;
        return $buffer;
    }

    /**
     * Minify your script
     */
    public static function setTrim($buffer) {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); // remove comments
        return str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer); // remove tabs, spaces, newlines, etc.    
    }

    /*
      public static function cleanInput($buffer) {
      return str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer);
      }
     */

    public static function isAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Identify the value if numeric or not
     */
    public static function isNumeric($n) {
        if (is_numeric($n))
            return true;
        else
            return false;
    }

    /*
     * Encode the string to url character
     */

    public static function setUrlEncode($str) {
        return urlencode($str);
    }

    /*
     * Decode the url value to string
     */

    public static function setUrlDecode($str) {
        return urldecode($str);
    }

    /*
     * Cut the string and return the specified string
     */

    public static function setTruncate($strText, $StrNum = 20) {
        return (strlen($strText) > $StrNum) ? substr($strText, 0, $StrNum) . '...' : $strText;
    }

    public static function setSession($handler, $value = '') {
        $_SESSION[$handler] = $value;
    }

    public static function getSession($handler) {
        return (isset($_SESSION[$handler])) ? $_SESSION[$handler] : null;
    }

    public static function setCookie($handler, $value = '', $expire = NULL, $domain = NULL) {
        if ($expire == NULL)
            $expire = time() + 3600; //1hour
        setcookie($handler, $value, $expire);
        return true;
    }

    public static function getCookie($handler) {
        if (isset($_COOKIE[$handler]))
            return $_COOKIE[$handler];
        else
            return false;
    }

    public static function isSubmit($submit) {
        return (isset($_POST[$submit]) OR isset($_GET[$submit]));
    }

    public static function setPregReplace($str) {
        //return preg_replace("/[^a-zA-Z0-9\s]/", "", $str);
        return preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $str);
    }

    public static function safeOutput($string, $html = false) {
        if (!$html)
            $string = strip_tags($string);
        return @Tools::htmlentitiesUTF8($string, ENT_QUOTES);
    }

    public static function htmlentitiesUTF8($string, $type = ENT_QUOTES) {
        if (is_array($string))
            return array_map(array('Tools', 'htmlentitiesUTF8'), $string);
        return htmlentities($string, $type, 'utf-8');
    }

    public static function htmlentitiesDecodeUTF8($string) {
        if (is_array($string))
            return array_map(array('Tools', 'htmlentitiesDecodeUTF8'), $string);
        return html_entity_decode($string, ENT_QUOTES, 'utf-8');
    }

    /**
     * Comparing String to String using Strlike
     */
    public static function SetStrLike($strText, $strRef) {
        $detect = 0;
        $lenText = strlen($strText);
        $lenRef = strlen($strRef);

        if ($lenText <= $lenRef) {
            for ($i = 0; $i < $lenText; $i++)
                if ($strText[$i] != $strRef[$i])
                    $detect = 1;
        } else
            $detect = 1;
        return $detect;
    }

    /*
     * str = the whole String
     * $prefix = locate the char/str from source
     * strlen(str) = count the whole string of scource
     */

    public static function SetCutString($str, $prefix) {
        return substr($str, strlen($prefix), strlen($str));
    }

    public static function passwordGenerate($length = 8) {
        $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0, $passwd = ''; $i < $length; $i++)
            $passwd .= self::substr($str, mt_rand(0, self::strlen($str) - 1), 1);
        return $passwd;
    }

    /*     * * INPUT VALIDATION *** */

    public static function SetClean($str) {
        $str = @trim($str);
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return mysql_real_escape_string($str);
    }

    public static function setPrevent($str, $N = 35) {
        $str = addslashes($str);
        $str = substr($str, 0, $N);
        return $str;
    }

    public static function setSanitize($buffer) {
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer);
        return htmlentities($buffer, ENT_QUOTES, 'utf-8');
        //return stripslashes($buffer);
        //return htmlentities(stripslashes($str), ENT_QUOTES);
    }

    public static function setUtf8_encode($string) {
        return utf8_encode($string);
    }

    public static function setUtf8_decode($string) {
        return utf8_decode($string);
    }

    public static function encodeToUtf8($string) {
        return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
    }

    public static function encodeToIso($string) {
        return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
    }

    public static function encodeToHtmlEntities($string) {
        $map = array(
            chr(0x8A) => chr(0xA9),
            chr(0x8C) => chr(0xA6),
            chr(0x8D) => chr(0xAB),
            chr(0x8E) => chr(0xAE),
            chr(0x8F) => chr(0xAC),
            chr(0x9C) => chr(0xB6),
            chr(0x9D) => chr(0xBB),
            chr(0xA1) => chr(0xB7),
            chr(0xA5) => chr(0xA1),
            chr(0xBC) => chr(0xA5),
            chr(0x9F) => chr(0xBC),
            chr(0xB9) => chr(0xB1),
            chr(0x9A) => chr(0xB9),
            chr(0xBE) => chr(0xB5),
            chr(0x9E) => chr(0xBE),
            chr(0x80) => '&euro;',
            chr(0x82) => '&sbquo;',
            chr(0x84) => '&bdquo;',
            chr(0x85) => '&hellip;',
            chr(0x86) => '&dagger;',
            chr(0x87) => '&Dagger;',
            chr(0x89) => '&permil;',
            chr(0x8B) => '&lsaquo;',
            chr(0x91) => '&lsquo;',
            chr(0x92) => '&rsquo;',
            chr(0x93) => '&ldquo;',
            chr(0x94) => '&rdquo;',
            chr(0x95) => '&bull;',
            chr(0x96) => '&ndash;',
            chr(0x97) => '&mdash;',
            chr(0x99) => '&trade;',
            chr(0x9B) => '&rsquo;',
            chr(0xA6) => '&brvbar;',
            chr(0xA9) => '&copy;',
            chr(0xAB) => '&laquo;',
            chr(0xAE) => '&reg;',
            chr(0xB1) => '&plusmn;',
            chr(0xB5) => '&micro;',
            chr(0xB6) => '&para;',
            chr(0xB7) => '&middot;',
            chr(0xBB) => '&raquo;',
        );
        return html_entity_decode(mb_convert_encoding(strtr($string, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
    }

    /** TIME VALIDATION  ***** */
    public static function getTime($opt = NULL) {
        //putenv("TZ=Asia/Manila");  // Set Time From Online Server
        //date_default_timezone_set('Asia/Manila');
        switch ($opt) {
            case 1: return date('Y-m-d h:i:s A', time());
                break;
            case 2: return date('Y-m-d', time());
                break;
            case 3: return date('h:i:s A', time());
                break;
            case 4: return date('Y-md-', time());
                break;
            case 5: return date('Y-m-d h:i:s A', time());
                break;
            case 6: return date('Y-m-', time());
                break;
            case 7: return date('Y', time());
                break;
            case 8: return date('m', time());
                break;
            case 9: return date('d', time());
                break;
            case 10: return date('y', time());
                break;
            default: return date('Y-m-d H:i:s', time());
                break;
        }
    }

    public static function getLocaltime($opt) {
        $timezone_offset = 4;
        switch ($opt) {
            case 1: return gmdate('Y-m-d h:i:s', time() + $timezone_offset * 60 * 60);
                break;
            case 2: return gmdate('Y-m-d', time() + $timezone_offset * 60 * 60);
                break;
            case 3: return gmdate('h:i:s A', time() + $timezone_offset * 60 * 60);
                break;
            case 4: return gmdate('Y-md-', time() + $timezone_offset * 60 * 60);
                break;
            case 5: return gmdate('Y-m-d h:i:s A', time() + $timezone_offset * 60 * 60);
                break;
            default: return gmdate('Y-m-d H:i:s', time() + $timezone_offset * 60 * 60);
                break;
        }
    }

    public function DateDiff_Days($start, $end) {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        return round($diff / 86400);
    }

    public static function DateDiff_Days2($first_time, $second_time) {
        $diff = date_diff(date_create($first_date), date_create($second_date));
        return $diff->format("%R%a");
    }

    public static function DateDiff_Date($first_datetime, $second_datetime) {
        $diff = abs(strtotime($first_datetime) - strtotime($second_datetime));
        $data['years'] = floor($diff / (365 * 60 * 60 * 24));
        $data['months'] = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $data['days'] = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        return $data;
    }

    public static function DateDiff_Interval($first_datetime, $second_datetime) {
        $difference = strtotime($firstTime) - strtotime($second_datetime);
        $data['years'] = abs(floor($difference / 31536000));
        $data['days'] = abs(floor(($difference - ($years * 31536000)) / 86400));
        $data['hours'] = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
        $data['minutes'] = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60));
        return $data;
    }

    /*     * * HEADER AND REQUEST ** */

    public static function ExcelDownload($filename) {
        header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Description: File Transfer");

        //session_cache_limiter("must-revalidate");
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    }

    public static function DestroySessionCache() {
        //session_destroy();
        header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always expired
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Pragma: nocache");  // HTTP/1.0
    }

    /** * NETWORK **** */

    public static function getReferer() {
        return $_SERVER['HTTP_REFERER'];
    }

    public static function getServerName() {
        if (isset($_SERVER['HTTP_X_FORWARDED_SERVER']) AND $_SERVER['HTTP_X_FORWARDED_SERVER'])
            return $_SERVER['HTTP_X_FORWARDED_SERVER'];
        return $_SERVER['SERVER_NAME'];
    }

    public static function getRemoteAddr() {
        // This condition is necessary when using CDN, don't remove it.
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] AND ( !isset($_SERVER['REMOTE_ADDR']) OR preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR'])) OR preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR'])) OR preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR'])) OR preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR'])))) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                return $ips[0];
            } else
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
    public static function getLocalIp() {
            //Just get the headers if we can or else use the SERVER global            
            $localip = '127.0.0.1';
            $the_ip = '';
            if($_SERVER['REMOTE_ADDR']!='::1'){
                if ( function_exists( 'apache_request_headers' ) ) {
                    $headers = apache_request_headers();
                } else {
                    $headers = $_SERVER;
                }
                $check = 0;
                //Get the forwarded IP if it exists     
                if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {                    
                    $the_ip = $headers['X-Forwarded-For'];
                } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
                ) {                    
                    $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
                } else {                                                       
                    $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
                }                
            }    
            if($the_ip!='' OR strlen($the_ip)==0){                
                $the_ip = $localip;
            }
            return $the_ip; 
    }
    public static function getLocalIpAddress() {
        $this_ip = '';
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    // trim for safety measures
                    $ip = trim($ip);
                    // attempt to validate IP
                    if (self::validate_ip($ip)) {
                        $this_ip = $ip;
                    }
                }
            }
        }
       $this_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
       return str_replace(':','_',$this_ip);
    }
    public static function validate_ip($ip){
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }
        return true;
    }    


    public static function setInternetConnection($Url) {
        $conn = @fsockopen($Url, 80, $errno, $errstr, 30);
        if ($conn) {
            $status = "Connection is OK";
            fclose($conn);
        } else {
            $status = "NO Connection<br/>\n";
            $status .= "$errstr ($errno)";
        }
        return $status;
    }

    /*     * * BROWSER VALIDATION **** */

    public static function DetectBrowser() {
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'chrome'))
            $message = 'chrome';
        elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'firefox'))
            $message = 'firefox';
        elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'safari'))
            $message = 'safari';
        elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'msie'))
            $message = 'msie';
        else
            $message = 'other';
        return $message;
    }

    public static function BrowserDetails() {
        $browser = array(
            'version' => '0.0.0',
            'majorver' => 0,
            'minorver' => 0,
            'build' => 0,
            'name' => 'unknown',
            'useragent' => ''
        );

        $browsers = array(
            'firefox', 'msie', 'opera', 'chrome', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape',
            'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
        );

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $user_agent = strtolower($browser['useragent']);
            foreach ($browsers as $_browser) {
                if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match)) {
                    $browser['name'] = $match[1];
                    $browser['version'] = $match[2];
                    @list($browser['majorver'], $browser['minorver'], $browser['build']) = explode('.', $browser['version']);
                    break;
                }
            }
        }
    }

    public static function getAllFiles($dir, $fileExtension) {
        // create array to hold matched files
        $filesArray = array();
        // finding files in dir we want to
        $allFiles = glob($dir . "*." . $fileExtension);
        // removing directory path from files we found
        foreach ($allFiles as $file) {
            //echo $image ."<br />";
            $file = explode("/", $file);
            // adding file name (without path, just file name) to files array
            $filesArray[] = $file[count($file) - 1];
        }
        // if any files found ... return that array
        if (count($filesArray) > 0) {
            return $filesArray;
        }
        // if no files found ... return false
        return false;
    }

    public static function deleteDirectory($dirname, $delete_self = true) {
        $dirname = rtrim($dirname, '/') . '/';
        $files = scandir($dirname);
        foreach ($files as $file)
            if ($file != '.' AND $file != '..') {
                if (is_dir($dirname . $file))
                    self::deleteDirectory($dirname . $file, true);
                elseif (file_exists($dirname . $file))
                    unlink($dirname . $file);
            }
        if ($delete_self)
            rmdir($dirname);
    }
  public static function deleteFolder($path){
    if (is_dir($path) === true){
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file){
                self::deleteFolder(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        }
        else if (is_file($path) === true){
            return unlink($path);
        }
  }

    public static function setAddSlashes($str) {
        return addslashes($str);
    }

    public static function setEntities($str) {
        return htmlentities($str, ENT_QUOTES, "UTF-8");
    }

    public static function setHtmlSpecialChars($str) {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    public static function setStripTags($str, $strip = NULL) {
        return strip_tags($str, $strip);
    }

    public static function setReplace($str, $rmv) {
        return str_replace($rmv, '', $str);
    }

    public static function jsonElement($str, $dataType = 'CHAR') {
        if ($dataType == 'INT') {
            if ($str != 0)
                return $str;
            else
                return '';
        }
        else {
            if ($str)
                return htmlentities($str, ENT_QUOTES, 'utf-8');
            else
                return '';
        }
    }
    public static function DateChangeFormat($date,$format = 'F d Y'){
        return date($format, strtotime($date));        
    }
    public static function folderSize($path) {
        $total_size = 0;
        $files = scandir($path);
        $cleanPath = rtrim($path, '/'). '/';

        foreach($files as $t) {
            if ($t<>"." && $t<>"..") {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = foldersize($currentFile);
                    $total_size += $size;
                }
                else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }   
        }
        return $total_size;
    }
    function folderSize1 ($dir){
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : folderSize($each);
        }
        return $size;
    }
  public static function extendMemoryLimit(){
    set_time_limit ( 90000 );
        ini_set ( "memory_limit", "2048M" );
        ini_set ( "post_max_size", "2048M" );
        ini_set ( "upload_max_filesize", "2048M" );
  }

  public static function setStriplahes($string){    
    $string = stripslashes($string);
    return $string;
  }
  public static function arrayColumn(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}