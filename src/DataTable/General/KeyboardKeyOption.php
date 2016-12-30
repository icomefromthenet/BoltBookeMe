<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the jQuery DataTable ajax settings
 * 
 * @website https://datatables.net/reference/option/buttons.buttons.key
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class KeyboardKeyOption implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
           'shiftKey' => false,
           'altKey'  => false,
           'ctrlKey' => false,
           'metaKey' => false,
           'key'     => null,
       ];
       
   }
   
   /**
    * The character to listen for. The character is case insensitive.
    * 
    * @return this
    * @param string $sKey the keyboard key
    */ 
   public function setKeyboardKey($sKey)
   {
        $this->aConfigStruct['key'] = $sKey;    
               
       return $this;
   }
   
   /**
    * When set to true activation will only occur if the shift key is also being held.
    *
    * @param boolean $bRequires
    * @return this
    */
   public function setRequiresShiftKey($bRequired)
   {
       $this->aConfigStruct['shiftKey'] = $bRequired;
       
       return $this;
   }
   
   /**
    * When set to true activation will only occur if the alt key is also being held.
    * 
    * @param boolean $bRequires
    * @return this
    */ 
   public function setRequiresAltKey($bRequired)
   {
       $this->aConfigStruct['altKey'] = $bRequired; 
       
       return $this;
   }
   
   /**
    * When set to true activation will only occur if the ctrl key is also being held.
    * 
    * @param boolean $bRequires
    * @return this
    */ 
   public function setRequiresCtrlKey($bRequired)
   {
       $this->aConfigStruct['ctrlKey'] = $bRequired; 
       
       return $this;
   }
   
   
   /**
    * When set to true activation will only occur if the cmd key (Mac) or Windows key (Windows) is also being held.
    * 
    * @return $this;
    */ 
   public function setRequiresMetaKey($bRequired)
   {
       $this->aConfigStruct['metaKey'] = $bRequired; 
       
       return $this;
   }
   
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return $this->aConfigStruct;
   }
   
    
}
/* End of class */