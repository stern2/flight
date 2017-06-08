<?php
if ( !isset($LIB_ABSTRACT_FLIGHT_PHP) )
{
    $LIB_ABSTRACT_FLIGHT_PHP = 1;
    class AbstractFlight
    {
        var $_error = '';
        var $_errorno = 0;
        var $_curl = null;
        
        
        function AbstractFlight()
        {
            $this->staticCookieSessionName = 'curl_' . get_class($this) . '_cookie';
        }
    }
}