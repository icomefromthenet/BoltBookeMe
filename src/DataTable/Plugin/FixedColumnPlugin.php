<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the jQuery DataTable Fixed Header Plugin
 * 
 * 
 * @website https://datatables.net/extensions/fixedcolumns/ 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class FixedColumnPlugin implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
           'iLeftColumns'   => 1,
	       'iRightColumns'  => 0,
	       'fnDrawCallback' => null,
	       'sHeightMatch'   => 'semiauto'
	   ];
       
   }
   
  
   /**
    * Set the number of columns on the left to be fixed
    * 
    * @return self
    * @param integer    $iNumberFixed      The fixed number
    */ 
   public function setNumberFixedLeftColumn($iNumberFixed)
   {
       $this->aConfigStruct['iLeftColumns'] = $iNumberFixed;
       
       return $this;
   }
   
   /**
    * Set the number of columns on the right to be fixed
    * 
    * @return self
    * @param integer    $iNumberFixed      The fixed number
    */ 
   public function setNumberFixedRightColumn($iNumberFixed)
   {
       $this->aConfigStruct['iRightColumns'] = $iNumberFixed;
       
       return $this;
   }
   
    /**
    * Draw callback function which is called when FixedColumns has redrawn the fixed assets
    * 
    * @return self
    * @param integer    $iNumberFixed      The fixed number
    */ 
   public function setHeightCalculationCallback($sFunctionName)
   {
       $this->aConfigStruct['fnDrawCallback'] = new FunctionReferenceType($sFunctionName);
       
       return $this;
   }
   
   
    /**
    * Frequence to do a height calculation
    * 
    * @return self
    */ 
   public function setHeightCalculationAuto()
   {
       $this->aConfigStruct['sHeightMatch'] = 'auto';
       
       return $this;
   }
   
   
   /**
    * Frequence to do a height calculation
    * 
    * @return self
    */ 
   public function setHeightCalculationSemiAuto()
   {
       $this->aConfigStruct['sHeightMatch'] = 'semiauto';
       
       return $this;
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['fixedColumns' => $this->aConfigStruct];
   }
   
    
}
/* End of class */