<?php

    include_once("config.php");

    ob_start();
    include_once(INCLUDE_DIR. "/simple_html_dom.php");
    include_once(INCLUDE_DIR. "/Template.php");
    include_once(INCLUDE_DIR. "/Curlext.php");
    include_once(INCLUDE_DIR. "/Flight.php");
    ob_clean();

    // request
    $departure = isset($_REQUEST["departure"]) ? $_REQUEST["departure"] : "";
    $destination = isset($_REQUEST["destination"]) ? $_REQUEST["destination"] : "";
    $ddate1 = isset($_REQUEST["ddate1"]) ? $_REQUEST["ddate1"] : "";
    $ddate2 = isset($_REQUEST["ddate2"]) ? $_REQUEST["ddate2"] : "";
    $flight = 'inland';

    //所有航班
    $flightInstance = Flight::factory($flight == '' ? key($FLIGHTS) : $flight);

    $flights = $flightInstance->getAllFlights($departure, $destination, $ddate1);
    if (empty($flights))
    {
        JavaScript::alertAndRedirect("无法获取航班列表， 请确认参数是否正确！", 'flight.php');
        exit ("發生異常，無法獲取航班!");
    }
    
    //header("Location:flight.php?departure={$departure}&destination={$destination}&ddate1={$ddate1}&room=" . end($flights));


    // template
    $myTemplate = new Template(TEMPLATE_DIR ."/flight.html");

    include_once("common.inc.php");

    //search
    $searchArray = array ();
    $searchArray['{departure}'] = $departure;
    $searchArray['{destination}'] = $destination;
    $searchArray['{ddate1}'] = $ddate1;
    $searchArray['{ddate2}'] = $ddate2;

    $myTemplate->setReplace('search', $searchArray);
        
    //list
    for($i = 0; isset($flights[$i]); $i++)
    {
        $dataArray = array();
        $dataArray["{flight}"] = $flights[$i]['flight'];
        $dataArray["{takeoff_time}"] = $flights[$i]['takeoff_time'];
        $dataArray["{arrival_time}"] = $flights[$i]['arrival_time'];
        $dataArray["{ontimerate}"] = $flights[$i]['ontimerate'];
        $dataArray["{price}"] = $flights[$i]['price'];

        $myTemplate->setReplace("list", $dataArray);
    }


    $myTemplate->process();
    $myTemplate->output();


?>