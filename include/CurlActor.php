<?php

if ( !isset($CURL_ACTOR_PHP) )
{
    $CURL_ACTOR_PHP = 1;  
    
    class CurlActor
    {
        var $debug;
        var $errorNo;
        var $errorMessage;
        var $referer;
        var $content;
        
        function CurlActor()
        {
            $this->debug = false;
            $this->referer = "";
            $this->content = "";
        }
        
        function setDebug($flag)
        {
            $this->debug = $flag;
        }
        
        function setReferer($url)
        {
            $this->referer = $url;
        }

        function getContent()
        {
            return $this->content;
        }

        function getHeaders($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)');

            if ( $this->referer != "" )
            {
                curl_setopt($ch, CURLOPT_REFERER, $this->referer);
            }
            
            $headers = curl_exec ($ch);

            $this->errorNo      = curl_errno($ch);
            $this->errorMessage = curl_error($ch);

            curl_close ($ch);
            
            return $headers;
        }

        function getContentLength($url)
        {
            if ($this->debug) echo "CurlActor::getContentLength() : $url \n";
            
            $headers = $this->getHeaders($url);
            
            echo $headers;
            
            $lines = explode("\n", $headers);
            
            for($i = 0; isset($lines[$i]); $i++)
            {
                if ( strstr($lines[$i], "Content-Length:") )
                {
                    $contentLength = trim( str_replace("Content-Length:", "", $lines[$i]) );
                    
                    return $contentLength;
                }
            }
            
            return -1;
        }
        
        function getErrorNo()
        {
            return $this->errorNo;
        }
        
        function getErrorMessage()
        {
            return $this->errorMessage;
        }
        
        function isRedirect($url)
        {
            // get headers && judge if "Location: xxxx" is existed.
            
            if ($this->debug) echo "CurlActor::isRedirect() : $url \n";
            
            $headers = $this->getHeaders($url);

            $lines = explode("\n", $headers);
            
            for($i = 0; isset($lines[$i]); $i++)
            {
                if ( strstr($lines[$i], "Location:") )
                {
                    $location = trim( str_replace("Location:", "", $lines[$i]) );
                    
                    return $location;
                }
            }
            
            return false;
        }
    
        function doRequest($method, $url, $vars) 
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'c:/temp/banwan/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:/temp/banwan/cookie.txt');
    
            if ($method == 'POST') 
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
            }
    
            $this->content = curl_exec($ch);
            
            curl_close($ch);
        }
    
        function get($url) 
        {
           return $this->doRequest('GET', $url, 'NULL');
        }
    
        function post($url, $vars) 
        {
           return $this->doRequest('POST', $url, $vars);
        }        
    }
}
?>