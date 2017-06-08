<?php
if ( !isset($FLIGHT_PHP) )
{
    $FLIGHT_PHP = 1;


    class Flight
    {
        var $_error = null;
        
        function Flight()
        {
            
        }
        
        static function factory($flight)
        {
            $file = INCLUDE_DIR . '/flight/Flight' .  ucfirst($flight) . '.php';
            
            if (file_exists($file))
            {
                ob_start();
                include_once($file);
                ob_clean();
                $class = 'Flight' . ucfirst($flight);
                return new $class();
            }
            else
            {
                exit ("Driver not exist: {$flight} $file");
            }
        }

        
        function getError()
        {
            return $this->_error;
        }
        
        
    }
}
?>