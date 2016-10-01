<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;


/**
 * Defines the expected interface of object that configure Datatable
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
interface DataTableConfigInterface
{
   
  
   
   /**
    * Implemented to allow default to be set 
    * 
    * @return void
    */ 
   public function setDefaults();
   
   
   //------------------------------------------
   # API to add Config Options
   
   
   public function addPlugin(DataTableOptionInterface $oOption);
   
   public function addOptionSet(DataTableOptionInterface $oOption);
   
   public function addSchema(DataTableOptionInterface $oOption);
   
   
   //-------------------------------------------
   # Option Access to allow later modification
   
   public function getPlugin($sPluginClass);
   
   public function getOptionSet($sOptionSet);
   
   public function getSchema();

   public function getEventRegistry();    
}
/* End of Interface */