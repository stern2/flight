<?php
if ( !isset($FLIGHT_INLAND_PHP) )
{
    $FLIGHT_INLAND_PHP = 1;
    
    include_once(INCLUDE_DIR . '/Curlext.php');
    include_once(INCLUDE_DIR . '/simple_html_dom.php');
    include_once(INCLUDE_DIR . '/flight/abstract/AbstractFlight.php');

    class FlightInland extends AbstractFlight
    {
        const SINGLE_URL = 'http://flights.ctrip.com/booking/sha-nkg-day-1.html?ddate1=2017-06-09';
        //const SINGLE_URL = 'http://flights.ctrip.com/booking/{departure}-{destination}-day-1.html?ddate1={ddate1}';
        const RETURN_URL = 'http://flights.ctrip.com/booking/{departure}-{destination}---d-adu-1/?ddate1={ddate1}&ddate2={ddate2}';
        var $flightsSingleUrlParameters = array (
                                    'ddate1' => '{ddate1}',
                                 );
        
        function FlightInland()
        {
            parent::AbstractFlight();
            $this->_curl = new Curlext(array (), ROOT_DIR . 'cookies/' . __CLASS__);
        }
        
        
        
        /** 
         * 
         * @param type $departure
         * @param type $destination
         * @param type $ddate1
         * @return array 
         * 
         */
        function getAllFlights($departure, $destination, $ddate1)
        {
            $data = array ();
            
            //get html
            $flightsSingleUrl = str_replace(array ('{departure}', '{destination}', '{ddate1}'), array ($departure, $destination, $ddate1), self::SINGLE_URL);
            $this->_curl->setAction('flightsSingle', $flightsSingleUrl);
            $singleUrlParameters = str_replace(array ('{ddate1}'), array ($ddate1), $this->flightsSingleUrlParameters);
            //$html = $this->_curl->open()->post('flightsSingle', $singleUrlParameters)->body();
            $html = file_get_contents(self::SINGLE_URL);
            echo $html;die;
            if(!empty($html))
            {
                               
                //parse html
                $dom = new simple_html_dom($html);
                
                $calendarTable = $dom->find('form#Forms table', 1);
                
                foreach ($calendarTable->find('tr') as $k => $tr)
                {
                    if ($k > 1)
                    {
                        foreach ($tr->find('td[valign=top]') as $td) 
                        {
                            if (!empty($td->plaintext))
                            {
                                $plaintext = str_replace(' ', '', $td->plaintext);
                                $plaintext = explode("\n", $plaintext);
                                list($month, $date) = explode('/', $plaintext[0]);
                                if (!empty($date))
                                {
                                    $date = trim($date);
                                    $date = intval($date);
                                    $data[$date] = array ();
                                    $data[$date]['total_rooms'] = $td->find('input[type=text]', 0)->value;
                                    $data[$date]['sold_rooms'] = intval($td->find('label', 0)->innertext);
                                    $data[$date]['reserve_rooms'] = array ();
                                    $data[$date]['remain_rooms'] = $data[$date]['total_rooms'] - $data[$date]['sold_rooms'];
                                    $data[$date]['market_price'] = array ();
                                    $data[$date]['hotel_price'] = isset($prices[$date]['price']) ? $prices[$date]['price'] : '';
                                }
                                
                            }
                        }
                    }
                }
            }
            
            return $data;
        }
        
        
    }
}
?>