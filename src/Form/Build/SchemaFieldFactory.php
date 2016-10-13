<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;

/**
 * Helper to build fields with known type
 *
 * 
 * @author Lewis Dyer <getintouch@icomfromthenet.com>
 * @since 1.0
 */
class SchemaFieldFactory extends OptionFactory
{
    
    /**
     * Create a integer field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createIntegerField(StringOutput $oOutput)
    {
        $oField = new SchemaField($oOutput);
        return $oField->addPrimitive('type','integer');
    }
    
    /**
     * Create a string field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createStringField(StringOutput $oOutput)
    {
         $oField = new SchemaField($oOutput);
         return $oField->addPrimitive('type','string');
    }
    
    /**
     * Create a Number field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createNumberField(StringOutput $oOutput)
    {
         $oField = new SchemaField($oOutput);
         return $oField->addPrimitive('type','number');
    }
    
    /**
     * Create a boolean field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createBooleanField(StringOutput $oOutput)
    {
        $oField = new SchemaField($oOutput);
        return $oField->addPrimitive('type','boolean');
    }
    
    /**
     * Create a array field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createArrayField(StringOutput $oOutput)
    {
        $oField = new SchemaField($oOutput);
        return $oField->addPrimitive('type','array');
    }
    
    /**
     * Create a object field type
     * 
     * @return JSONObjectBuilder
     */ 
    public static function createObjectField(StringOutput $oOutput)
    {
        $oField = new SchemaField($oOutput);
        return $oField->addPrimitive('type','object');
    }
    
}
/* End of class */