<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Model;

trait GuidTrait 
{

   /*
    *   Taken from the PHP documentation website.
    *
    *   Kristof_Polleunis at yahoo dot com
    *
    *   A guid function that works in all php versions:
    *   MEM 3/30/2015 : Modified the function to allow someone
    *       to specify whether or not they want the curly
    *       braces on the GUID.
    *
    * @link http://php.net/manual/en/function.com-create-guid.php
    * @return string a guid
    * @access protected
    */    
    public function guid($opt = true )
    {       
        
        //  Set to true/false as your default way to do this.
        if(true === function_exists('com_create_guid')){
            if( $opt ){ 
                return com_create_guid(); 
            }
            else { 
                return trim( com_create_guid(), '{}' ); 
            }
        }
        else {
            mt_srand( (double)microtime() * 10000 );    // optional for php 4.2.0 and up.
            $charid = strtoupper( md5(uniqid(rand(), true)) );
            $hyphen = chr( 45 );    // "-"
            $left_curly = $opt ? chr(123) : "";     //  "{"
            $right_curly = $opt ? chr(125) : "";    //  "}"
            $uuid = $left_curly
                . substr( $charid, 0, 8 ) . $hyphen
                . substr( $charid, 8, 4 ) . $hyphen
                . substr( $charid, 12, 4 ) . $hyphen
                . substr( $charid, 16, 4 ) . $hyphen
                . substr( $charid, 20, 12 )
                . $right_curly;
            return $uuid;
        }
        
    }    
    
    
}
