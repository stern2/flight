<?php
if ( !isset($CURLEXT_PHP) )
{
    $CURLEXT_PHP = 1;

    class Curlext 
    {

        var $_is_temp_cookie = false;
        var $_header = '';
        var $_body = '';
        var $_ch;
        var $_cookie;
        var $_options;
        var $_url = array();
        var $_referer = array();
        var $_error = '';
        var $_errorno = 0;

        function __construct($options = array(), $cookieFile = '') 
        {
            $this->Curlext($options, $cookieFile);
        }
        
        function Curlext($options = array(), $cookieFile = '')
        {
            $defaults = array();

            $defaults ['timeout'] = 30;
            $defaults ['temp_root'] = sys_get_temp_dir();
            $defaults ['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1 AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36';
            $this->_options = array_merge($defaults, $options);
            $this->_cookie = $cookieFile;
            
        }

        function open() 
        {
            $this->_ch = curl_init();

            curl_setopt($this->_ch, CURLOPT_HEADER, true);
            curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->_options ['user_agent']);
            curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_options ['timeout']);
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('Expect:')); // for lighttpd 417 Expectation Failed  
            
            curl_setopt($this->_ch, CURLOPT_COOKIEJAR, $this->_cookie);
            curl_setopt($this->_ch, CURLOPT_COOKIEFILE, $this->_cookie);
            
            $this->_header = '';
            $this->_body = '';

            return $this;
        }
        
        function followLocation($follow = false)
        {
            if ($follow)
            {
                curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true); 
            }
            return $this;
        }

        function close() 
        {
            if (is_resource($this->_ch)) {
                curl_close($this->_ch);
            }

            if (isset($this->_cookie) && $this->_is_temp_cookie && is_file($this->_cookie)) {
                @unlink($this->_cookie);
            }
        }
        


        function post($action, $query = array()) 
        {
            curl_setopt($this->_ch, CURLOPT_POST, true);
            curl_setopt($this->_ch, CURLOPT_URL, $this->_url [$action]);
            
            if ( is_array($query) )
            {
                curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($query));
            }
            else
            {
                curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $query);
            }

            if ( $this->_referer [$action] != "" )
            {
                curl_setopt($this->_ch, CURLOPT_REFERER, $this->_referer[$action]); 
            }

            $this->_requrest();

           // echo $this->_url [$action].'\r\n';
           // echo http_build_query($query).'\r\n';
           // echo $query.'\r\n';
           // echo $this->_header.'\r\n';
           //echo $this->_body;

            return $this;
        }

        function get($action, $query = array()) 
        {
            $url = $this->_url [$action];

            if (!empty($query)) 
            {
                $url .= strpos($url, '?') === false ? '?' : '&';
                $url .= is_array($query) ? http_build_query($query) : $query;
            }

            curl_setopt($this->_ch, CURLOPT_URL, $url);
            $this->_requrest();

            return $this;
        }



        function setAction($action, $url, $referer = '') 
        {
            $this->_url [$action] = $url;
            $this->_referer [$action] = $referer;

            return $this;
        }
        
        function getAction($action) 
        {
            return $this->_url[$action];
        }

        function header() 
        {
            return $this->_header;
        }

        function body() 
        {
            return $this->_body;
        }

        function effectiveUrl() 
        {
            return curl_getinfo($this->_ch, CURLINFO_EFFECTIVE_URL);
        }

        function httpCode() 
        {
            return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
        }

        function _requrest() 
        {
            $response = curl_exec($this->_ch);

            $errno = curl_errno($this->_ch);

            if ($errno > 0) 
            {
                $this->_errorno = $errno;
                $this->_error = curl_error($this->_ch);
            }

            $header_size = curl_getinfo($this->_ch, CURLINFO_HEADER_SIZE);

            $this->_header = substr($response, 0, $header_size);
            $this->_body = substr($response, $header_size);
        }
        
        function getError()
        {
            return $this->_error;
        }
        
        function getErrorNo()
        {
            return $this->_errorno;
        }
        
        function getInfo()
        {
            return curl_getinfo($this->_ch);
        }

        function __destruct() 
        {
            $this->close();;
        }

    }
}


?>