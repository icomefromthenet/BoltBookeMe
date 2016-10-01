<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableException;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Collection of columns
 *  
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ColumnSchema implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aColumns;
   
   
   
   public function __construct()
   {
       $this->aColumns = [];
       
   }
   
   /**
    * Add a column to the schema
    * 
    * @return self
    * @param string                     $sName      The name of the column for later lookups
    * @param DataTableOptionInterface   $oColumn    The column config object to add    
    */ 
   public function addColumn($sName, DataTableOptionInterface $oColumn )
   {
       $this->aColumns[$sName] = $oColumn;
       
       return $this;
   }
   
   /**
    * Fetch a column by its lookup name
    * 
    * @retun DataTableOptionInterface
    * @param string     $sName      The columns lookup name
    */ 
   public function getColumn($sName)
   {
       if(false === $this->hasColumn($sName)) {
           throw DataTableException::errorColumnDoesNotExist($sName);
       }
       
       return $this->aColumns[$sName];
   }
   
   /**
    * Check if column exists at name
    * 
    * @return boolean 
    * @param string     $sName      The column lookup name
    */ 
   public function hasColumn($sName)
   {
       return isset($this->aColumns[$sName]);
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       $aConfig = [];
       
       foreach($this->aColumns as $oColumn) {
           $aConfig[] = $oColumn->getStruct();
       }
       
       
       return ['columns' =>$aConfig];
   }
   
    
}
/* End of class */