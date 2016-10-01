<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;

/**
 * Configures the jQuery DataTable Select Plugin
 * 
 * @website https://datatables.net/extensions/select/ 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class SelectPlugin implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
            'style' => 'api',
            'items'  => 'row',
            'info'  => true,
        ];
       
   }
  
   
   /**
    * Only a single item can be selected
    * 
    * @return self
    */ 
   public function setSelectStyleSingleRow()
   {
       $this->aConfigStruct['style'] = 'single';
       
       return $this;
   }
   
   /**
    * Only the api can use the select
    * 
    * @return self
    */ 
   public function setSelectStyleDefault()
   {
       $this->aConfigStruct['style'] = 'api';
       
       return $this;
   }
   
   
   /**
    * Multiple rows can be selected 
    * 
    * @return self
    */ 
   public function setSelectStyleMultiRow()
   {
       $this->aConfigStruct['style'] = 'multi';
       
       return $this;
   }
   
   /**
    * This is the most comprehensive option and provides complex behaviours such as 
    * ctrl/cmd clicking to select
    * 
    * @return self
    */ 
   public function setSelectStyleOS()
   {
       $this->aConfigStruct['style'] = 'os';
       
       return $this;
       
   }
   
   /**
    * A hybrid between the os style
    * Able to use shift to select multi rows
    * 
    * @return self
    */ 
   public function setSelectStyleHybrid()
   {
       $this->aConfigStruct['style'] = 'multi+shift';
       
       return $this;
       
   }
   
   /**
    * Rows are the selected item
    * 
    * @return self
    */ 
   public function setItemRows()
   {
        $this->aConfigStruct['items'] = 'row';
       
       return $this;
   
   }
  
   /**
    * Columns are the selected item
    * 
    * @return self
    */ 
   public function setItemColumns()
   {
        $this->aConfigStruct['items'] = 'column';
       
       return $this;
   
   }
   
   /**
    * Cells are the selected item
    * 
    * @return self
    */ 
   public function setItemCells()
   {
        $this->aConfigStruct['items'] = 'cell';
       
       return $this;
   
   }
   
   /**
    * Css class that used applied to selected rows
    * 
    * @return self
    * @param string     $sClassName     A css class name
    */ 
   public function setSelectCssClassName($sClassName)
   {
       $this->aConfigStruct['className'] = $sClassName;
       
       return $this;
   }
   
   /**
    * A selector string to filter the allowd selectable items in the table
    * 
    * @return self
    * @param string     $sCssFilter     A selector that jquery understand
    */ 
   public function setSelectorFilter($sCssFilter)
   {
       $this->aConfigStruct['selector'] = $sCssFilter;
       
       return $this;
   }
   
   /**
    * The selected details are displayed in info section of the datatable
    * 
    * @return self
    * @param boolean    $bShouldDisplay     Display or not display
    */ 
   public function setSelectedInfoDisplayed($bShouldDisplay)
   {
       $this->aConfigStruct['info'] = $bShouldDisplay;
       
       return $this;
   }
   
   /**
    * If row selection can be cleared by clicking outside of the table
    * 
    * @return self
    * @param boolean    $bCanBlur   True if can blur selected items
    */ 
   public function setBlurable($bCanBlur)
   {
        $this->aConfigStruct['blurable'] = $bCanBlur;
        
        return $this;
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['select' => $this->aConfigStruct];
   }
   
    
}
/* End of class */