<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


abstract class AbstractJSONBuilder implements OptionBuilderInterface
{
    /**
     * @var array the data items
     */ 
    protected $aItems;
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Ouput\StringOutput 
     */
    protected $oOutput;
   
   
    abstract protected function setDefaults();
    
    
   
    public function __construct(StringOutput $oOutput)
    {
        $this->aItems  = [];
        $this->oOutput = $oOutput;
       
        $this->setDefaults(); 
    }
    
    /**
     * Convert the options into an array map ready for the output writer
     * 
     * @return array
     */ 
    public function getStruct()
    {
        $aLocalStruct = [];
        
        foreach($this->aItems as $mIndex => $oItem) {
            switch(true) {
                case $oItem instanceof FunctionReferenceType:
                     $aLocalStruct[$mIndex] = $oItem;
                break;                
                case $oItem instanceof JSONObjectBuilder:
                    $aLocalStruct[$mIndex] = $oItem->getStruct();
                break;
                case $oItem instanceof JSONArrayBuilder:
                    $aLocalStruct[$mIndex] = $oItem->getStruct();
                break;
                // must be a Primitive
                default : $aLocalStruct[$mIndex] = $oItem;
            }
            
        }
        
        return $aLocalStruct;
    }
    
    /**
     * Return json by running the struct through the output writer 
     * 
     * @return string   json
     */ 
    public function getJSON()
    {
        $aStruct = $this->getStruct();
        return $this->oOutput->write($aStruct)->bytes();
    }
    
}
/* End of File */