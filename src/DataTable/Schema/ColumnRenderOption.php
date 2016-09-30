<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures the render method of a column with options
 * 
 * 
 * @website https://datatables.net/reference/option/columns.render
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ColumnRenderOption implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
             "_" => null,
            "filter"  => null,
            "display" => null
       ];
       
   }
   
   
   /**
    * Set the data index to use for filtering
    * 
    * @return self
    * @param string    $sIndex      The index name
    */ 
   public function setFilterIndex($sIndex)
   {
       $this->aConfigStruct['filter'] = $sIndex;
       
       return $this;
   }
   
   /**
    * Set the data index to use for display
    * 
    * @return self
    * @param string    $sIndex      The index name
    */ 
   public function setDisplayIndex($sIndex)
   {
       $this->aConfigStruct['display'] = $sIndex;
       
       return $this;
   }
   
    /**
    * Set the value to display if column value is missing
    * 
    * @return self
    * @param string    $sEmptyValue      The default value to display e.g 'n/a'
    */ 
   public function setEmptyDefault($sEmptyValue)
   {
       $this->aConfigStruct['_'] = $sEmptyValue;
       
       return $this;
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ["render" => $this->aConfigStruct];
   }
   
    
}
/* End of class */