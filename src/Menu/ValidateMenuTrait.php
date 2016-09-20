<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

use Valitron\Validator;


trait ValidateMenuTrait
{
 
 
    public function validate()
    {
        
        if(!$this instanceof ValidationRulesInterface) {
            throw MenuException::notImplementInterface($this);
        }
        
        $aRules     = $this->getRules();
        $aData      = $this->getData();
        $oValidator = new Validator($aData);
        
        $oValidator->rules($aRules);
        
        $bValid = $oValidator->validate();
        
        if(false === $bValid) {
            throw MenuException::hasFailedValidation($this,$oValidator->errors());
        }  
      
        return true;
    }
 
   
    
    
    
}
/* End of Class */

