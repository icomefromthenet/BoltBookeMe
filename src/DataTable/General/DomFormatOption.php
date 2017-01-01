<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the jQuery DataTable DOM format option, see website for details
 * 
 * @website https://datatables.net/reference/option/dom
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DomFormatOption implements DataTableOptionInterface
{
   
   const BUTTON_OPTION = 'B';
  
   const FILTER_OPTION = 'f';
   
   const TABLE_OPTION = 't';
   
   const TABLE_INFO_OPTION = 'i';

   const TABLE_NUM_ROWS_OPTION = 'l';

   const TABLE_PAGE_OPTION = 'p';
    
   const PROCESS_DISPLAY_OPTION = 'r';
   
   
   /**
    * @var array
    */ 
   protected $sFormatOption;
   
   
   
   public function __construct($sFormatOption)
   {
       $this->sFormatOption = $sFormatOption;
   }
   
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['dom' => $this->sFormatOption];
   }
   
    
}
/* End of class */