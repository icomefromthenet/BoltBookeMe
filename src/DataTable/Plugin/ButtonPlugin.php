<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button\StandardButton;

/**
 * Configures the jQuery DataTable Buttons Plugin
 * 
 * @website https://datatables.net/reference/option/buttons
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ButtonPlugin implements DataTableOptionInterface
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
  
   
   /**
    * Adds a button to this collection
    * 
    * @param string  $sIndex
    * @param Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button\StandardButton    $oButton
    * @return this
    */ 
   public function addButton($sIndex, StandardButton $oButton)
   {
      $this->aConfigStruct['buttons'][$sIndex] = $oButton; 
       
      return $this;
   }
   
   /**
    * Fetch button at given index
    * 
    * @param string  $sIndex
    * return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button\StandardButton
    */ 
   public function getButton($sIndex)
   {
      if(isset($this->aConfigStruct['buttons'][$sIndex])) {
         return $this->aConfigStruct['buttons'][$sIndex]; 
      }
      
      return null;
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       
       $aButtons = [];
       foreach($this->aConfigStruct['buttons'] as $oButton) {
          $aButtons[] = $oButton->getStruct();
       }
       
       $this->aConfigStruct['buttons'] = $aButtons;
       
       return ['buttons' => $this->aConfigStruct];
   }
   
    
}
/* End of class */