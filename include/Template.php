<?php

class Template 
{
    var $AYZZYXY;

    var $BYWZXWZ;
    var $BZWWYYY;
    
    var $BYWXWZY;
    
    var $AWXWZXY;
    var $BWXXWZW;
    var $BXZXYXW;
    
    var $BWWXZZZ;
    var $BYXYWXY;

    var $BZYWZYZ; 
    var $BWXYWXY;
    var $AYYWWXW;
    var $AXWWYWX;
    var $AWZWZWZ;
    var $AYYYWWW;
    var $BWXWZWW;
    var $BXXXYWW;
    var $AXXWZWX;

    public function Template($AYZZYXY, $BYWZXWZ = "<!-- start", $BZWWYYY = "<!-- end")
    {
        $this->BZYWZYZ = array();
        $this->BWXYWXY = array();
        $this->AYYWWXW = array();
        $this->AXWWYWX = array();
        $this->AWZWZWZ = array();
        $this->AYYYWWW = array();

        $this->BWWXZZZ = array();
        $this->BYXYWXY = array();

        $this->BWWXZZZ[0] = "TEMPLATE_ROOT";
        $this->BYXYWXY[0] = 0;
        
        $this->AYZZYXY = $AYZZYXY;
        
        $this->BYWZXWZ = $BYWZXWZ;
        $this->BZWWYYY   = $BZWWYYY;

        $this->AWXWZXY = FALSE;
        $this->BWXXWZW = TRUE;
        $this->AXXWZWX = 0;
        $this->BXZXYXW = false;
    }
    
    public function setClearTag($AXYWXXX)
    {
        $this->AWXWZXY = $AXYWXXX;
    }

    public function setClearSpace($AXYWXXX)
    {
        $this->BWXXWZW = $AXYWXXX;
    }

    public function setGlobalReplace($BZYZXXW, $BYWXWZY)
    {
        $this->BWXYWXY[$BZYZXXW] = $BYWXWZY;
    }

    public function setAutoBrackets($AXYWXXX)
    {
        $this->BXZXYXW = $AXYWXXX;
    }
    
    public function process()
    {
        $BZXYZWW = fopen($this->AYZZYXY, "r");
        
        while($AXYWWYY = fgets($BZXYZWW, 4096))
        {
            if ( $this->BWXXWZW )
            {
                $AXYWWYY = trim($AXYWWYY);
            }
            else
            {
                $AXYWWYY = rtrim($AXYWWYY);
            }
            
            $this->BWXWZWW[] = $AXYWWYY; // $this->globalReplace($AXYWWYY);
        }
        
        fclose($BZXYZWW);
        
        $this->BWXWZWW = $this->BXXXXYY(1, $this->BWWXZZZ[0], 0, $this->BWXWZWW);

        $this->BXXXYWW = implode("\n", $this->BWXWZWW);
    }
    
    public function globalReplace($AXYWWYY)
    {
        if ( !is_array($this->BWXYWXY) )
        {
            return $AXYWWYY;
        }
        
        reset($this->BWXYWXY);

        while ( list( $BZXZXYX , $BYYYWWY ) = each ($this->BWXYWXY) )
        {
            $AXYWWYY = str_replace($BZXZXYX, $BYYYWWY, $AXYWWYY);
        }
        
        return $AXYWWYY;
    }
    
    public function setReplace($BZYZXXW, $BWWWYYX, $AXYYZYX = 1)
    {
        $this->AXXWZWX ++;
        
        $this->BWWXZZZ[$AXYYZYX] = $BZYZXXW;
        $this->BYXYWXY[$AXYYZYX] = $this->AXXWZWX;
        
        $BZWYZWX = $this->BWWXZZZ[$AXYYZYX-1];
        $BWXWYWW = $this->BYXYWXY[$AXYYZYX-1];

        if ( $this->BXZXYXW )
        {
            $BWWWYYX = $this->BZWYYXW($BWWWYYX);
        }

        $this->BZYWZYZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][] = $BWWWYYX;
        $this->AYYYWWW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][] = $this->AXXWZWX;
    }

    public function setMultiReplace($BZYZXXW, $BWWWYYX, $AXYYZYX = 1)
    {
        for ($AYWYWYW = 0; isset($BWWWYYX[$AYWYWYW]); $AYWYWYW++) 
        { 
            $this->setReplace($BZYZXXW, $BWWWYYX[$AYWYWYW], $AXYYZYX);
        }
    }

    private function BZWYYXW($BWWWYYX)
    {
        $AYXYXXZ = array();

        foreach ($BWWWYYX as $BZXZXYX => $AWZZZWX) 
        {
            if ( is_numeric($BZXZXYX) )
            {
                continue;
            }

            $AYXYXXZ["{". $BZXZXYX ."}"] = $AWZZZWX;
        }

        return $AYXYXXZ;
    }
    
    public function setReplaceSegment($BZYZXXW, $BYWXWZY, $AXYYZYX = 1)
    {
        $this->AXXWZWX ++;
        
        $this->BWWXZZZ[$AXYYZYX] = $BZYZXXW;
        $this->BYXYWXY[$AXYYZYX] = $this->AXXWZWX;
        
        $BZWYZWX = $this->BWWXZZZ[$AXYYZYX-1];
        $BWXWYWW = $this->BYXYWXY[$AXYYZYX-1];

        $this->AYYWWXW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][] = $BYWXWZY;
        $this->AYYYWWW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][] = $this->AXXWZWX;
    }

    public function setSkip($BZYZXXW, $AXYYZYX = 1)
    {
        $this->AXXWZWX ++;

        $BZWYZWX = $this->BWWXZZZ[$AXYYZYX-1];
        $BWXWYWW = $this->BYXYWXY[$AXYYZYX-1];

        $this->AXWWYWX[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW] = TRUE;
    }

    public function setNothing($BZYZXXW, $AXYYZYX = 1)
    {
        $this->setReplace($BZYZXXW, array(), $AXYYZYX);
    }

    private function BXXXXYY($AXYYZYX, $BZWYZWX, $BWXWYWW, $BWXWZWW)
    {
        $AZXXZWW = array();
        
        for($AYWYWYW = 0; isset($BWXWZWW[$AYWYWYW]); $AYWYWYW++)
        {
            if ( !($BZYZXXW = $this->AYWWXWZ($BWXWZWW[$AYWYWYW])) )
            {
                $AZXXZWW[] = $BWXWZWW[$AYWYWYW];
                continue;
            }
            
            $BWWXWWW = NULL;
            
            $BZWXWZZ = $BWXWZWW[$AYWYWYW];
            
            for($AYWYWYW++; isset($BWXWZWW[$AYWYWYW]); $AYWYWYW++)
            {
                if ( $this->AXWWZWY($BWXWZWW[$AYWYWYW], $BZYZXXW) )
                {
                    $AZZYYYY = $BWXWZWW[$AYWYWYW];
                    break;
                }
                
                $BWWXWWW[] = $BWXWZWW[$AYWYWYW];
            }

            $BXYYZWW = NULL;

            if ( isset($this->AYYWWXW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][0]) )
            {
                $BXYYZWW = explode("\n", $this->AYYWWXW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][0]);
                $AZXXZWW = array_merge($AZXXZWW, $BXYYZWW);
            }
            else if ( isset($this->BZYWZYZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][0]) )
            {
                for($AXYZZXW = 0; isset($this->BZYWZYZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][$AXYZZXW]); $AXYZZXW++)
                {
                    $BXYYZWW = $this->BXXXXYY($AXYYZYX+1, 
                                              $BZYZXXW, 
                                              $this->AYYYWWW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][$AXYZZXW], 
                                              $BWWXWWW); 
                                                                      
                    $AYWZXZX = implode("\n", $BXYYZWW);
                    
                    reset($this->BZYWZYZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][$AXYZZXW]);
                    
                    while ( list( $BZXZXYX , $BYYYWWY ) = each ($this->BZYWZYZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW][$AXYZZXW]) )
                    {
                        $AYWZXZX = str_replace($BZXZXYX, $BYYYWWY, $AYWZXZX);
                    }
                    
                    $BXYYZWW = explode("\n", $AYWZXZX);
                    
                    if ( !$this->AWXWZXY )
                    {
                        $AZXXZWW[] = $BZWXWZZ;
                        $AZXXZWW = array_merge($AZXXZWW, $BXYYZWW);
                        $AZXXZWW[] = $AZZYYYY;
                    }
                    else
                    {
                        $AZXXZWW = array_merge($AZXXZWW, $BXYYZWW);
                    }
                }
            }
            else if ( isset($this->AWZWZWZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW]) &&
                      $this->AWZWZWZ[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW] == TRUE )
            {
                $BXYYZWW = $this->BXXXXYY($AXYYZYX+1, $BZYZXXW, $this->AYYYWWW[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW], explode("\n", $BYWZWXW)); 
            }
            else if ( isset($this->AXWWYWX[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW]) &&
                      $this->AXWWYWX[$AXYYZYX][$BZWYZWX][$BWXWYWW][$BZYZXXW] == TRUE )
            {
                unset($BXYYZWW);
            }
            else
            {
                unset($BXYYZWW);
            }
        }
        
        return $AZXXZWW;
    }

    private function AYWWXWZ($AXYWWYY)
    {
        $AXWXWXX = explode($this->BYWZXWZ, $AXYWWYY);
        
        if ( !isset($AXWXWXX[1]) )
        {
            return NULL;
        }
        
        $AXWXWXX = explode(" ", $AXWXWXX[1]);
        
        return (isset($AXWXWXX[1]) ? $AXWXWXX[1] : NULL);
    }

    private function AXWWZWY($AXYWWYY, $BZYZXXW)
    {
        $AXWXWXX = explode($this->BZWWYYY, $AXYWWYY);
        
        if ( !isset($AXWXWXX[1]) )
        {
            return FALSE;
        }
        
        $AXWXWXX = explode(" ", $AXWXWXX[1]);
        
        if ( isset($AXWXWXX[1]) && $AXWXWXX[1] == $BZYZXXW )
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function getContent()
    {
        return $this->BXXXYWW;
    }
    
    public function debug()
    {
        echo '[$this->BZYWZYZ]';
        print_r($this->BZYWZYZ);

        echo '[$this->AYYYWWW]';
        print_r($this->AYYYWWW);
    }

    public function output()
    {
        echo $this->getContent();
    }
    
    function alertAndBack($AZXZWXX, $BYYWYYW = -1)
    {
        echo "<script language='JavaScript'>\n";
        echo "<!--\n";
        echo "alert('".$AZXZWXX."');\n";
        echo "window.history.go(". $BYYWYYW .");\n";
        echo "//-->\n";
        echo "</script>\n";
    }

    function alertAndRedirect($AZXZWXX, $AYYYYXW)
    {
        echo "<script language='JavaScript'>\n";
        echo "<!--\n";
        echo "alert('".$AZXZWXX."');\n";
        echo "window.location.href='$AYYYYXW';\n";
        echo "//-->\n";
        echo "</script>\n";
    }
}

?>