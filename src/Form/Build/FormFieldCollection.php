<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * A collection fields inside the form schema that converted into a form field at runtime
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class FormFieldCollection extends JSONArrayBuilder
{
   
    
    protected function setDefaults()
    {
    }
    
    
    /**
     * Create a field and return the
     * 
     * @param string        $sFieldName     The field name
     * @param FormField   $oSchema        The field properties
     * @return self
     */ 
    public function addField(FormField $oFormField)
    {
        $this->addObjectValue($oFormField);
    
        return $this;
    }
    
    /**
     * Fetch a field by the index
     * 
     * @param integer the index which field was added
     */
    public function getField($iIndex) 
    {
        return $this->aItems[$iIndex];
    }
    
    /**
     * Fetch the field by a name, ie the value assigned to key property
     * 
     * @return FormField
     * @param string the name of the field from the schema 
     */ 
    public function getFieldByName($sFieldName)
    {
       $oField = null;
       
       foreach($this->aItems as $oFormField) {
           if($oFormField->getOption('key') === $sFieldName) {
               $oField = $oFormField;
               break;
           }
       }
       
       
       return $oField;
    }
    
    
}
/* End of Class */