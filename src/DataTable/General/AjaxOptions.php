<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the jQuery DataTable ajax settings
 * 
 * This proxy for the jQuery Ajax constructor http://api.jquery.com/jQuery.ajax/
 * 
 * @website https://datatables.net/reference/option/ajax
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class AjaxOptions implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
            "url" => '',
            "method" => 'GET',
            "data" => [],
            "dataType" => 'json',
       ];
       
   }
   
   
   /**
    * Set the data url
    * 
    * @return self
    * @param string    $sUrl      The data url
    */ 
   public function setDataUrl($sUrl)
   {
       $this->aConfigStruct['url'] = $sUrl;
       
       return $this;
   }
   
   /**
    * Sets the index in response to look for result set
    * 
    * @return string the object index
    */ 
   public function setResponseDataIndex($sIndex)
   {
      $this->aConfigStruct['dataSrc'] = $sIndex;
      return $this;
   }
   
   
   /**
    * Set the http request method
    * 
    * @return self
    * @param string    $sType      The request type GET|POST|PUT|DELETE
    */ 
   public function setHttpRequestMethod($sType)
   {
       $this->aConfigStruct['method'] = $sType;
       
       return $this;
   }
  
  /**
    * Add data that used in ajax request
    * 
    * @return self
    * @param string    $sIndex      The index in the query string
    * @param string    $mValue      The value to include in request
    */ 
   public function setRequestParam($sIndex,$mValue)
   {
       $this->aConfigStruct['data'][$sIndex] = $mValue;
       
       return $this;
   }
   
    /**
    * Set response datatype
    * 
    * @return self
    * @param string    $sValue      The response datatype string|json|html|xml
    */ 
   public function setResponseDataType($sValue)
   {
       $this->aConfigStruct['dataType'] = $sValue;
       
       return $this;
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ["ajax" => $this->aConfigStruct, "serverSide"  => false];
   }
   
    
}
/* End of class */