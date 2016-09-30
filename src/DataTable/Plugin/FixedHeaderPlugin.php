<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;

/**
 * Configures the jQuery DataTable Fixed Header Plugin
 * 
 * 
 * @website https://datatables.net/reference/option/fixedHeader 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class FixedHeaderPlugin implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
           'header'         => true,
	       'footer'         => false,
	       'headerOffset'   => 0,
	       'footerOffset'   => 0
	   ];
       
   }
   
   /**
    * Set if should be a fixed header
    * 
    * @return self
    * @param boolean    $bHeaderMode    True if should be a fixed header
    */ 
   public function setHeaderMode($bHeaderMode)
   {
      $this->aConfigStruct['header'] = $bHeaderMode;
      
      return $this;
   }
   
    /**
    * Set if should be a fixed footer
    * 
    * @return self
    * @param boolean    $bFooterMode    True if should be a fixed footer
    */ 
   public function setFooterMode($bFooterMode)
   {
       $this->aConfigStruct['footer'] = $bFooterMode;
       
       return $this;
   }
   
   /**
    * Set and offset on header
    * 
    * @return self
    * @param integer    $iHeaderOffset      The offset to set
    */ 
   public function setHeaderOffset($iHeaderOffset)
   {
       $this->aConfigStruct['headerOffset'] = $iHeaderOffset;
      
       return $this;
   }
   
    /**
    * Set and offset on footer
    * 
    * @return self
    * @param integer    $iFooterOffset      The offset to set
    */ 
   public function setFooterOffset($iFooterOffset)
   {
       $this->aConfigStruct['footerOffset'] = $iFooterOffset;
       
       return $this;
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['fixedHeader' => $this->aConfigStruct];
   }
   
    
}
/* End of class */