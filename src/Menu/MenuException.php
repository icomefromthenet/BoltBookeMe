<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;



class MenuException extends BookMeException
{
    
     /**
     * @var mixed
     */
    public $oItem;
    
    /**
     * @var array of errors messages
     */ 
    public $aErrors;
    
    
    public static function getClassName($oClass)
    {
        if ($oClass instanceof MenuItem) {
            return get_class($oClass) . '::'. $oClass->getMenuItemName();
            
        } elseif ($oClass instanceof MenuGroup) {
            
            return get_class($oClass) . '::'. $oClass->getGroupName();
        }
        else {
            
            return get_class($oClass);
        }
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedValidation($item, array $aErrors)
    {
        $exception = new static(
            'Validation has failed for menu '. self::getClassName($item)
        );
        
        $exception->oItem = $item;
        $exception->aErrors  = $aErrors;
        
        return $exception;
    }
    
     /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function notImplementInterface($item)
    {
        $exception = new static(
            'Item does not implement Validation Rules Interface has failed for menu '. get_class($item)
        );
        
        $exception->oItem = $item;
        $exception->aErrors  = [];
        
        return $exception;
    }
    
    
    public function getMenuItem()
    {
        return $this->oItem;
    }
    
     /**
     * Return the errors found during validation
     * 
     * @return array
     */ 
    public function getValidationFailures()
    {
        return $this->aErrors;
    }
    
}
/* End of Class */
