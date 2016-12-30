<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the a button click action
 * 
 * @website https://datatables.net/reference/option/buttons.buttons.action
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ActionCallback implements DataTableOptionInterface
{
   /**
    * @var string the function name that hander render
    */ 
   protected $sFunctionName;
   
   
   
   public function __construct($sFunctionName)
   {
       $this->sFunctionName = $sFunctionName;
       
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['action' => new FunctionReferenceType($this->sFunctionName)];
   }
   
    
}
/* End of class */