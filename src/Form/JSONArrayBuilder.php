<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;

/**
 * Helper to build json definition.
 *
 * 
 * @author Lewis Dyer <getintouch@icomfromthenet.com>
 * @since 1.0
 */
class JSONArrayBuilder extends AbstractJSONBuilder implements OptionBuilderInterface
{
    
    
    protected function setDefaults()
    {
        
    }
    
    /**
     * Add value to the internal sruct
     * 
     * @return self
     * @param mixed $mValue the value to include in the local sruct
     */ 
    protected function add($mValue)
    {
        $this->aItems[] = $mValue;
        
        return $this;
    }
    
    /**
     * Add a simple value, ie int,string,double,float
     * 
     * @return self
     * @param mixed $mValue
     */ 
    public function addPrimitive($mValue)
    {
       return $this->add($mValue);  
    }
    
     /**
     * Adds a JsonArray and descend into the builder
     * 
     * @return self
     * @param JSONArrayBuilder $oValue
     */  
    public function addArrayValue(JSONArrayBuilder $oValue)
    {
       return  $this->add($oValue);
    }
    
    /**
     * Adds a JsonObject and descend into the builder
     * 
     * @return self
     * @param JSONObjectBuilder $oValue
     */ 
    public function addObjectValue(JSONObjectBuilder $oValue)
    {
       return  $this->add($oValue);
    }
    
    /**
     * Add a function reference
     * 
     * @return self
     * @param FunctionReferenceType $oValue
     */ 
    public function addFuncRef(FunctionReferenceType $oValue)
    {
        return $this->add($oValue);
    }
    
    
    /**
     * Fetch the assigned option index
     * 
     * @return mixed the option
     * @param integer    $iIndex     The index to retrieve
     */ 
    public function getOption($iIndex) 
    {
        return $this->aItems[$iIndex];
    }
    
    /**
     * Return if this option exists.
     * 
     * @return boolan true if option exists
     * @param integer    $iIndex     The index to check
     */ 
    public function hasOption($iIndex)
    {
        return isset($this->aItems[$iIndex]);
    }
   
    
}
/* End of File */