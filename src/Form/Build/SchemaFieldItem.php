<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * Container element for a Field Item
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class SchemaFieldItem extends JSONObjectBuilder
{
   
    
    protected function setDefaults()
    {
        $this->addPrimitive('type','object')
             ->addObjectValue('properties', OptionFactory::createObjectBuilder($this->oOutput)); 
    }
    
    
    /**
    * Adds a field into this items
    * 
    * @return self
    * @param string         $sFieldName
    * @param SchemaField    $oField
    */ 
    public function addField($sFieldName, SchemaField $oField)
    {
       $this->getOption('properties')->addObjectValue($sFieldName, $oField);
       
       return $this;
    }

}
/* End of Class */