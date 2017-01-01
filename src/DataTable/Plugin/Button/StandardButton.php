<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\KeyboardKeyOption;

/**
 * Configures the jQuery DataTable Button Instance
 * 
 * @website https://datatables.net/extensions/select/ 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class StandardButton implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
         'enabled' => true,
         'action'  => null,
         'init'    => null,
         'key'     => null,
       ];
       
   }
  
   /**
    * Function describing the action to take on activation
    * 
    * @param Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button\ActionCallback $oCallback
    * @return this
    */ 
   public function setActionCallback(ActionCallback $oCallback)
   {
      $this->aConfigStruct['action'] = $oCallback;
   
      return $this;
   }
   
   /**
    * Sets the CSS Button class name 
    *
    * @param string  $sCssClass
    * @return this
    * 
    */
   public function setCSSClassName($sCssClass)
   {
      $this->aConfigStruct['className'] = $sCssClass;
      
      return $this;
   }
  
   /**
    * Sets the Initial enabled state
    * 
    * @return this
    * @param boolean $bIsEnabled 
    * 
    */ 
   public function setInitialEnableState($bIsEnabled)
   {
      $this->aConfigStruct['enabled'] = $bIsEnabled;
      
      return $this;
   }

   /**
    * Button initialisation callback function 
    * 
    * @param Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin\Button\InitCallback $oCallback
    * @return this
    */ 
   public function setInitCallback(InitCallback $oCallback)
   {
      $this->aConfigStruct['init'] = $oCallback;
      
      return $this;
   }
   
   /**
    * Set keyboard Key that can cause activation of this button
    * 
    * @param Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General\KeyboardKeyOption $oOption
    * @return $this;
    */ 
   public function setKeyboardKey(KeyboardKeyOption $oOption)
   {
      $this->aConfigStruct['key'] = $oOption;
      
      return $this;
   }


   /**
    * Button name for use in selectors
    * 
    * @return this
    * @param string  $sName
    */ 
   public function setButtonSelector($sName)
   {
      $this->aConfigStruct['name'] = $sName;
      
      return $this;
   }
   
   /**
    * Text visibable in the button
    * 
    * @return this
    * @param string  $sButtonText
    */ 
   public function setButtonText($sButtonText)
   {
      $this->aConfigStruct['text'] = $sButtonText;
      
      return $this;
   }

   /**
    * Set the HTML Title attribute on the button 
    * 
    * @return this
    * @param string  $sTitle
    */
   public function setHtmlAttributeTitle($sTitle)
   {
      $this->aConfigStruct['titleAttr'] = $sTitle;
      
      return $this;
   }

  
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
      $aConfig = $this->aConfigStruct;
       
       
      if($aConfig['action'] instanceof DataTableOptionInterface) {
           $aConfig = array_merge($aConfig,$aConfig['action']->getStruct());
      }
      
      if($aConfig['init'] instanceof DataTableOptionInterface) {
           $aConfig = array_merge($aConfig,$aConfig['init']->getStruct());
      }

      if($aConfig['key'] instanceof DataTableOptionInterface) {
           $aConfig['key'] = $aConfig['key']->getStruct();
      }


      return $aConfig;
   }
   
    
}
/* End of class */