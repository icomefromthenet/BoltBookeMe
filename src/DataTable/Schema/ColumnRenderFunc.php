<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the render method of a column with a function.
 * 
 * @website https://datatables.net/reference/option/columns.render
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ColumnRenderFunc implements DataTableOptionInterface
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
       return ["render" => $this->sFunctionName];
   }
   
    
}
/* End of class */