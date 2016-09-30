<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the jQuery DataTable generic default
 * 
 * 
 * @website https://datatables.net/manual/options
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DefaultOptions implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
          "serverSide"  => false, 
          "scrollX"     => false,
          "scrollY"     => false,
        ];
       
   }
   
   /**
    * Change a default option
    * 
    * @return self
    * @param string     $sOption        The option name
    * @param mixed      $mNewValue      The new value
    */ 
   public function overrideDefault($sOption,$mNewValue)
   {
       $this->aConfigStruct[$sOption] = $mNewValue;
       
       return $this;
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return  $this->aConfigStruct;
   }
   
    
}
/* End of class */