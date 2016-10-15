<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;


/**
 * A field inside the form schema that converted into a form field at runtime
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class FormField extends JSONObjectBuilder
{
   
    
    protected function setDefaults()
    {
          
    }
    
    
    //-------------------------------------------------------------
    # Field Attribtes
    
    /**
     * Set the key of this form field, same name as the schema field this
     * form field is meant to refereance, this option as we can add extra
     * via creating form field that have key set to null
     * 
     * @return self;
     * @param string    $sKey
     */ 
    public function setKey($sKey)
    {
        return $this->addPrimitive('key', $sKey);
    }
    
    /**
     * Sets the type of form control that will be rendered at runtime
     * 
     * @return self
     * @param string    $sType
     */ 
    public function setType($sType) 
    {
        return $this->addPrimitive('type',$sType);
    }
    
    /**
     * Sets the title that can be used overide the value from
     * schema definition or if this extra field need set this value
     * 
     * @return self
     * @param string    $sTitle
     */ 
    public function setTitle($sTitle)
    {
         return $this->addPrimitive('title',$sTitle);
    }
    
    /**
     *  JSON Form not to insert a label for the input fiel
     * 
     * @return self
     * @param boolean   $bNoTitle
     */ 
    public function setNoTitle($bNoTitle)
    {
        return $this->addPrimitive('notitle',$bNoTitle);
    }
    
    /**
     * Insert a suffix after the generated form input
     * 
     * @return self
     * @param string    $sAppend
     */ 
    public function setAppend($sAppend)
    {
         return $this->addPrimitive('append',$sAppend);
    }
    
    /**
     * Insert a prefix before the generated form input
     * 
     * @return self
     * @param string    $sAppend
     */ 
    public function setPrepend($sPrepend)
    {
         return $this->addPrimitive('prepend',$sPrepend);
    }
    
    /**
     * JSON Form displays the description property defined in the schema next to the input
     * 
     * @param string    $sDescription
     * @return self
     */ 
    public function setDescription($sDescription)
    {
        return $this->addPrimitive('description', $sDescription);
    }
    
    /**
     * Use this property to define additional classes for the generated field container. 
     * Classes must be separated by whitespaces
     * 
     * @return self
     * @param string    $sClass
     */ 
    public function setContainerCSSClass($sClass)
    {
        return $this->addPrimitive('htmlClass', $sClass);
    }
    
    
    
     /**
     * Use this property to define additional classes for the generated form control. 
     * Classes must be separated by whitespaces
     * 
     * @return self
     * @param string    $sClass
     */ 
    public function setFieldCSSClass($sClass)
    {
        return $this->addPrimitive('fieldHtmlClass',$sClass);
    }
    
    
    
    /**
     * Sets the placeholder attribute of the input field
     * 
     * @return self
     * @param string $sPlaceholder
     */ 
    public function setPlaceholder($sPlaceholder)
    {
        return $this->addPrimitive('placeholder',$sPlaceholder);
    }
    
    /**
     * The disabled attribute of the underlying input field
     * 
     * @return self
     * @param boolean $bIsDisabled
     */ 
    public function setDisabled($bIsDisabled)
    {
        return $this->addPrimitive('disabled',$bIsDisabled);
    }
    
    /**
     * Sets the readonly attribute of the underlying input field
     * 
     * @return self
     * @param boolean $bIsReadOnly
     */ 
    public function setReadOnly($bIsReadOnly)
    {
        return $this->addPrimitive('readonly',$bIsReadOnly);
    }
    
    /**
     * JSON Form will include the field with the empty string value in the final values, 
     * else the fields with empty 
     * value will not be included in the final values object.
     * 
     * @return self
     * @param boolean $bIncludeEmpty
     */ 
    public function setIncludeEmpty($bIncludeEmpty) 
    {
        return $this->addPrimitive('allowEmpty',$bIncludeEmpty);
    }
    
    
    
    
    /**
     * Adds sub field via the items prperty
     * 
     * @return self
     * @param FormFieldCollection   $oFormFieldCollection
     */ 
    public function addItems(FormFieldCollection $oFormFieldCollection)
    {
        if(false === $this->hasField('items')) {
            $this->addObjectValue('items',$oFormFieldCollection);
         
        }
        
        return $this;
    }
    
    
}
/* End of Class */