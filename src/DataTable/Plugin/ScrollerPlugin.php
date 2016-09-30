<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;

/**
 * Configures the jQuery DataTable Scroller Plugin
 * 
 * @website https://datatables.net/extensions/scroller/ 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class ScrollerPlugin implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
        	"trace"         => false,
        	"rowHeight"     => "auto",
        	"serverWait"    => 200, //applies when using server wait which not doing
        	"displayBuffer" => 9,
        	"boundaryScale" => 0.5,
        	"loadingIndicator" => false,
	    ];
       
   }
  
   
   /**
    * Set if the loading indicator should be used during scrolling.
    * 
    * @return self
    * @param boolean    $bUseIndicator      True to use the indicator
    */ 
   public function setUseLoadingIndicator($bUseIndicator)
   {
       $this->aConfigStruct['loadingIndicator'] = $bUseIndicator;
       
       return $this;
   }
   
   /**
    * Sets the boundry  scaling factor to decide when to redraw the table 
    * 
    * @return self
    * @param  float     $fScale     The scale value
    */ 
   public function setBoundryScale($fScale)
   {
        $this->aConfigStruct['boundaryScale'] = $fScale;
       
       return $this;
   }
   
   
   /**
    * Sets the boundry  scaling factor to decide when to redraw the table 
    * 
    * @return self
    * @param  float     $fSize     The buffer size
    */ 
   public function setDisplayBuffer($fSize)
   {
        $this->aConfigStruct['displayBuffer'] = $fSize;
       
       return $this;
   }
   
   /**
    *  Scroller will attempt to automatically calculate the height of rows for it's internal
	* calculations. However the height that is used can be overridden using this parameter.
	* 
	* @return self
	* @param integer|string     $mHeight       The method or fixed value
    */ 
   public function setRowHeight($mHeight)
   {
         $this->aConfigStruct['rowHeight'] = $mHeight;
       
        return $this;
   }
   
   /**
    * Indicate if Scroller show show trace information on the console or not
    * 
    * @return self
    * @param  float     $bUseTrace     To use console trace
    */ 
   public function setUseTrace($bUseTrace)
   {
       $this->aConfigStruct['trace'] = $bUseTrace;
       
       return $this;
   }
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return ['scroller' => $this->aConfigStruct, 'deferRender' => true];
   }
   
    
}
/* End of class */