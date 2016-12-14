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
class FormFieldFactory extends OptionFactory
{
    
    /**
     * Create an actions container form field type
     * 
     * @return FormField
     */ 
    public static function createActionsType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','actions');
    }
    
    /**
     * Create an textarea form field type
     * 
     * @return FormField
     */ 
    public static function createTextareaType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','textarea');
    }
    
    /**
     * Create an section container form field type
     * 
     * @return FormField
     */ 
    public static function createSectionType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','section');
    } 
    
    /**
     * Create an fieldset container form field type
     * 
     * @return FormField
     */ 
    public static function createFieldsetType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','fieldset');
    }
    
    /**
     * Create an Radio box form field type
     * 
     * @return FormField
     */ 
    public static function createRadiosType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','radios');
    }
    
    /**
     * Create an Select form field type
     * 
     * @return FormField
     */ 
    public static function createSelectType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','select');
    }
    
    /**
     * Create an Checkboxes group form field type
     * 
     * @return FormField
     */ 
    public static function createCheckboxesType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','checkboxes');
    }
    
    /**
     * Create an Checkbox form field type
     * 
     * @return FormField
     */ 
    public static function createCheckboxeType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','checkbox');
    }
    
    /**
     * Create an Date html5 form field type
     * 
     * @return FormField
     */ 
    public static function createDateType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','date');
    }
     
    /**
     * Create an Datetime html5 form field type
     * 
     * @return FormField
     */ 
    public static function createDateTimeType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','datetime');
    }
    
    /**
     * Create an Time html5 form field type
     * 
     * @return FormField
     */ 
    public static function createTimeType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','time');
    }
    
    /**
     * Create an Email html5 form field type
     * 
     * @return FormField
     */ 
    public static function createEmailType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','email');
    }
    
    /**
     * Create an Month html5 form field type
     * 
     * @return FormField
     */ 
    public static function createMonthType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','month');
    }
    
     /**
     * Create an Week html5 form field type
     * 
     * @return FormField
     */ 
    public static function createWeekType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','week');
    }
    
    /**
     * Create an Telephone html5 form field type
     * 
     * @return FormField
     */ 
    public static function createTelephoneType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','tel');
    }
    
    /**
     * Create an Password html5 form field type
     * 
     * @return FormField
     */ 
    public static function createPassowrdType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','password');
    }
    
     /**
     * Create an Text form field type
     * 
     * @return FormField
     */ 
    public static function createTextType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','text');
    }
    
     /**
     * Create an Range html5 form field type
     * 
     * @return FormField
     */ 
    public static function createRangeType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','range');
    }
    
    /**
     * Create an Submit Button form field type
     * 
     * @return FormField
     */ 
    public static function createSubmitType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','submit');
    }
    
    /**
     * Create an Submit Button form field type
     * 
     * @return FormField
     */ 
    public static function createJQueryDateType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','jdate');
    }
    
    /**
     * Create an Submit Button form field type
     * 
     * @return FormField
     */ 
    public static function createJQueryDateTimeType(StringOutput $oOutput)
    {
        $oField = new FormField($oOutput);
        return $oField->addPrimitive('type','jDateTime');
    }
    
    
    /**
     * Returns an empty field collection which array of fields
     * 
     * @return FormFieldCollection
     */ 
    public static function createFormFieldCollection(StringOutput $oOutput)
    {
        return new FormFieldCollection($oOutput);
    }
    
}
/* End of class */