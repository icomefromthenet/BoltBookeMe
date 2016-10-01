<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the top level column options
 *  
 * @website https://datatables.net/reference/option/columns.data
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ColumnOption implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
            "defaultContent"  => null,
            "render"          => null,
            "data"            => null, // where going to use render default to null but still an option
       ];
       
   }
   
   
   /**
    * Set the default content that used if the data has empty value
    * or need static content in the field like an action button
    * 
    * @return self
    * @param string    $sDefault      The default content
    */ 
   public function setDefaultContent($sDefault)
   {
       $this->aConfigStruct['defaultContent'] = $sDefault;
       
       return $this;
   }
   
    /**
    * Set the render to use the column option
    * 
    * @return self
    * @param string    $sDefault      The default content
    */ 
   public function setRenderOption(ColumnRenderOption $oOption)
   {
       $this->aConfigStruct['render'] = $oOption;
       
       return $this;
   }
   
   /**
    * Set the render method to use callback function
    * 
    * @return self
    * @param string    $sDefault      The default content
    */ 
   public function setRenderFunc(ColumnRenderFunc $oOption)
   {
       $this->aConfigStruct['render'] = $oOption;
       
       return $this;
   }
   
   /**
    * Sets the data index,
    * 
    * Note: render will override this setting.
    * 
    * @return self
    * @param string    $sIndex      The index in the dataset
    */ 
   public function setDataIndex($sIndex)
   {
        $this->aConfigStruct['data'] = $sIndex;
       
       return $this;
   }
   
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       $aConfig = $this->aConfigStruct;
       
       
       if($aConfig['render'] instanceof DataTableOptionInterface) {
           $aConfig['render'] = $aConfig['render']->getStruct();
       }
       
       
       
       return $aConfig;
   }
   
    
}
/* End of class */