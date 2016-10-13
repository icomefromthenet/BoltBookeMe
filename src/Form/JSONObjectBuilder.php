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
class JSONObjectBuilder extends AbstractJSONBuilder implements OptionBuilderInterface
{
    protected function setDefaults()
    {
        
    }
     
     
     /**
     * Add value to the internal sruct
     * 
     * @return self
     * @param string    $sIndex     the index
     * @param mixed     $mValue     the value to include in the local sruct
     */ 
    protected function add($sIndex, $mValue)
    {
        $this->aItems[$sIndex] = $mValue;
        
        return $this;
    }
    
    /**
     * Add a simple value, ie int,string,double,float
     * 
     * @return self
     * @param string    $sIndex     The Index
     * @param mixed     $mValue
     */ 
    public function addPrimitive($sIndex, $mValue)
    {
       return $this->add($sIndex, $mValue);  
    }
    
     /**
     * Adds a JsonArray and descend into the builder
     * 
     * @return self
     * @param string            $sIndex     The Index
     * @param JSONArrayBuilder  $oValue
     */  
    public function addArrayValue($sIndex, JSONArrayBuilder $oValue)
    {
       return  $this->add($sIndex, $oValue);
    }
    
    /**
     * Adds a JsonObject and descend into the builder
     * 
     * @return self
     * @param string            $sIndex     The Index
     * @param JSONObjectBuilder $oValue
     */ 
    public function addObjectValue($sIndex, JSONObjectBuilder $oValue)
    {
       return $this->add($sIndex,$oValue);
    }
    
    /**
     * Add a function reference
     * 
     * @return self
     * @param string                 $sIndex     The Index
     * @param FunctionReferenceType  $oValue
     */ 
    public function addFuncRef($sIndex, FunctionReferenceType $oValue)
    {
        return $this->add($sIndex, $oValue);
    }
    
    
    /**
     * Convert the options into an array map ready for the output writer
     * 
     * @return array
     */ 
    public function getStruct()
    {
        $aLocalStruct = parent::getStruct();
        
        if(empty($aLocalStruct)) {
            return new  \stdClass();
        }
        
        return $aLocalStruct;
    }
    
    /**
     * Fetch the assigned option index
     * 
     * @return mixed the option
     * @param string    $sIndex     The index to retrieve
     */ 
    public function getOption($sIndex) 
    {
        return $this->aItems[$sIndex];
    }
    
    /**
     * Return if this option exists.
     * 
     * @return boolan true if option exists
     * @param string    $sIndex     The index to check
     */ 
    public function hasOption($sIndex)
    {
        return isset($this->aItems[$sIndex]);
    }
    
    
    
    
}
/* End of File */