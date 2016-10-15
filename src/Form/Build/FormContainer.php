<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * Container element for a JsonForm Definition
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class FormContainer extends JSONObjectBuilder
{
   
    
    protected function setDefaults()
    {
        $this->addObjectValue('schema', new SchemaFieldCollection($this->oOutput))
             ->addArrayValue('form', new FormFieldCollection($this->oOutput))
             ->addObjectValue('params', OptionFactory::createObjectBuilder($this->oOutput))
             ->addObjectValue('value', OptionFactory::createObjectBuilder($this->oOutput));
          
    }
    
    
    
    //---------------------------------------------------------------------
    # Form Events
    
    /**
     * Bind event to the submit valid event which used to handle a submit
     * action when validation passes
     * 
     * @return self
     */ 
    public function addEventOnSubmitValid(FunctionReferenceType $oFuncRef)
    {
        return $this->addFuncRef('onSubmitValid', $oFuncRef);
    }
    
    /**
     * Add event to submit handler, which used to handle both valid
     * and invalid submissions
     * 
     * @return self
     */ 
    public function addEventOnSubmit(FunctionReferenceType $oFuncRef)
    {
        return $this->addFuncRef('onSubmit', $oFuncRef); 
    }
    
    /**
     * Fetch the schema which describes the data stored in form elements
     * 
     * @return SchemaFieldCollection
     */ 
    public function getSchema()
    {
        return $this->getOption('schema');
    }
    
    /**
     * Fetches the form property which used to control
     * the layout of form items
     * 
     * @return JSONObjectBuilder
     */ 
    public function getForm()
    {
        return $this->getOption('form');
    }
    
    /**
     * Fetch the params section of the container
     * 
     * @return JSONObjectBuilder
     */ 
    public function getParams()
    {
        return $this->getOption('params');
    }
    
    /**
     * Fetch the value section of the containe, used to set inital values
     * 
     * @return JSONObjectBuilder
     */ 
    public function getValues()
    {
        return $this->getOption('value');
    }
}
/* End of Class */