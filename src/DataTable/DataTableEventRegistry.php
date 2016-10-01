<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;

/**
 * Registers event handlers for the clientside DataTable events.
 * 
 * @see https://datatables.net/reference/event/
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DataTableEventRegistry
{
    
    
    // ------------------------------------------------------
    # Select Plugin EVents
    
    /*
    * @var Items (rows, columns or cells) have been selected
    */
    const PLUGIN_SELECT_ONSELECT = 'select';
    
    /*
    * @var Items (rows, columns or cells) have been deselected
    */
    const PLUGIN_SELECT_DESELECT = 'deselect';
    
    /*
    * @var A user action will cause items to be selected in the table
    */
    const PLUGIN_SELECT_USER_SELECT = 'user-select';
    
    //-------------------------------------------------------
    
    /*
    * @var Column sizing event - fired when the column widths are recalculated.
    */
    const CORE_COLUMN_SIZING   ='column-sizing';
    
    /**
     * @var Column visibility event - fired when the visibility of a column changes.
     */ 
    const CORE_COLUMN_VISIBILITY = 'column-visibility';
    
    /**
     * @var Table destroy event - fired when a table is destroyed.
     */ 
    const CORE_DESTORY = 'destroy';

    /**
     * @var Draw event - fired once the table has completed a draw.
     */ 
    const CORE_DRAW    = 'draw';
    
    /**
     * @var Error event - An error has occurred during DataTables' processing of data.
     */ 
    const CORE_ERROR  = 'error';


    /**
     * @var Initialisation complete event - fired when DataTables has been fully initialised and data loaded.
     */ 
    const CORE_INIT = 'init';

    /**
     * @var Page length change event - fired when the page length is changed.
     */ 
    const CORE_LENGTH = 'length';

    /**
     * @var order event - fired when the data contained in the table is ordered.
     */ 
    const CORE_ORDER = 'order';
    
    /**
     * @var Page change event - fired when the table's paging is updated.
     */
    const CORE_PAGE = 'page';

    /**
     * @var Initialisation started event - triggered immediately before data load.
     */ 
    const CORE_PREINIT = 'preInit';
    
    /**
     * @var Ajax event - fired before an Ajax request is made
     */ 
    const CORE_PREXHR = 'preXhr';
    
    
    /**
     * @var Processing event - fired when DataTables is processing data
     */
    const CORE_PROCESSING = 'processing';
    
    
    /**
     * @var Search event - fired when the table is filtered.
     */
    const CORE_SEARCH = 'search';
    
    
    /**
     * @var State loaded event - fired once state has been loaded and applied.
     */
    const CORE_STATELOADED = 'stateLoaded';
    
    
    /**
     * @var State load event - fired when loading state from storage.
     */
    const CORE_STATELOADPARAMS = 'stateLoadParams';
    
    
    /**
     * @var State save event - fired when saving table state information.
     */
    const CORE_STATESAVEPARAMS = 'stateSaveParams';
    
    
    /**
     * @var Ajax event - fired when an Ajax request is completed
     */
    const CORE_XHR   = 'xhr';
   
   
   //-------------------------------------------------------
   # Event API
   
   protected $aRegistry;
   
   /**
    * Add and event to the registry
    * 
    * @return self
    * @param string     $sEvent     An event name
    * @param string     $sFuncRef   A function reference
    * @param string     $sFilter    A selection filter that jquery understands
    * 
    */ 
   public function registerEvent($sEvent, $sFuncRef, $sFilter = null) 
   {
       if(isset($this->aRegistry[$sEvent])) {
           $this->aRegistry[$sEvent] = [];
       }
       
       $this->aRegistry[$sEvent][] = [$sEvent,$sFilter,new FunctionReferenceType($sFuncRef)];
       
       
       return $this;
       
   }
   
   /**
    * Looks if event been registered
    * 
    * @return boolean   true if found
    * @param string     $sEvent     An event name
    * @param string     $sFuncRef   A function reference
    */
   public function hasEventRegistered($sEvent,$sFuncRef)
   {
       $bFound = false;
       
       if(isset($this->aRegistry[$sEvent])) {
           foreach($this->aRegistry[$sEvent] as  $aEvent) {
               if($aEvent[2]->getValue() === $sFuncRef) {
                   $bFound = true;
               }
           }
       }
       
       
       return $bFound;
       
   }
   
   /**
    * Remove and event handler
    * 
    * @return self
    * @throws DataTableException    if event is not registered
    * @param string     $sEvent     An event name
    * @param string     $sFuncRef   A function reference
    */ 
   public function removeEvent($sEvent,$sFuncRef)
   {
        if(false === $this->hasEventRegistered()) {
            DataTableException::errorEventDoesNotExist($sEvent, $sFuncRef);
        } 
        
        $bFoundIndex = null;
        foreach($this->aRegistry[$sEvent] as $iIndex => $aEvent) {
           if($aEvent[2]->getValue() === $sFuncRef) {
               $bFoundIndex = $iIndex;
           }
        }
       
       unset($this->aRegistry[$sEvent][$bFoundIndex]);
       
       return $self;
   }
    
}
/* End of class */