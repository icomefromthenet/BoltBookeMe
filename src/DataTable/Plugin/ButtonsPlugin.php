<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;

/**
 * Configures the jQuery DataTable Select Plugin
 * 
 * @website https://datatables.net/extensions/select/ 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class SelectPlugin implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
            'buttons' => []
        ];
       
   }
  
   
   
   public function addButton('index',ButtonConfig $oButton)
   {
       
       
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['buttons' => $this->aConfigStruct];
   }
   
    
}
/* End of class */