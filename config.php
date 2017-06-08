<?php

    error_reporting(E_ERROR);
    ini_set('session.gc_maxlifetime', 21600);   // 6 hrs
    //set_time_limit(0); 

    session_start();

    mb_internal_encoding("UTF-8");

    if ( function_exists("date_default_timezone_set") )
    {
        date_default_timezone_set('Asia/Chongqing');
    }

    if ( $_SERVER["HTTP_HOST"] == "www.localhost.com" )
    {
        $DB_HOST = "localhost";
        $DB_USER = "root";
        $DB_PASS = "root";
        $DB_NAME = "test";

        define("DOMAIN", "http://". $_SERVER["HTTP_HOST"]);
        define("HOME_DIR", "C:/wamp64/www/flight");
        define("LOG_DIR", "C:/www/logs");    
        define("UPLOAD_URL", "http://www.localhost.com/flight/upload/");
    }
    else
    {
        echo "unknow domain!";
        exit;
    }

    include_once("session.inc.php");
    
    // definition    
    define("DEBUG_LEVEL", 0);
    define("INCLUDE_DIR", HOME_DIR ."/include");
    define("TEMPLATE_DIR", HOME_DIR ."/template"); 
    define("PIC_URL", $DOMAIN ."/pic/");
    define("ROOT_DIR", dirname(__FILE__) . '/'); 
    !file_exists(ROOT_DIR . 'cookies/') && mkdir(ROOT_DIR . 'cookies'); 

    ob_start();
    include_once(INCLUDE_DIR ."/Template.php");

    ob_clean();


?>