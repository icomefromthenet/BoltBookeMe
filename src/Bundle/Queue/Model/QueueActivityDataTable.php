<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\AbstractDataTableManager;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Output;

/**
 * DataTable for the Schedule Rebuild Queue
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class QueueActivityDataTable extends AbstractDataTableManager
{
    
    protected $sDataUrl;
    
    
    public function __construct(Output $oOutput, $sDataUrl )
    {
        $this->sDataUrl = $sDataUrl;
    
        parent::__construct($oOutput);
    }
    
    
    public function setDefaults()
    {
        # Set Default Options
        $oDefaultOptions = new General\DefaultOptions();
        $this->addOptionSet($oDefaultOptions);
        
        
        # set Ajax Options
        $oAjaxOption = new General\AjaxOptions();
        $oAjaxOption->setDataUrl($this->sDataUrl);
        $oAjaxOption->setHttpRequestMethod('GET');
        $oAjaxOption->setResponseDataType('json');
        
        $this->addOptionSet($oAjaxOption);
        
        # set the Scroller Plugin
        $oScrollerPlugin = new Plugin\ScrollerPlugin();    
        $oScrollerPlugin->setUseLoadingIndicator(true);
        $oScrollerPlugin->setUseTrace(false);
        
        
        $this->addPlugin($oScrollerPlugin);
        
        # Setup Column Schema
       
        $oJobIdColumn = new Schema\ColumnOption();
        $oJobIdColumnRender = new Schema\ColumnRenderOption();
        $oJobIdColumnRender->setFilterIndex('jobId');
        $oJobIdColumnRender->setDisplayIndex('jobId');
        $oJobIdColumn->setRenderOption($oJobIdColumnRender);
        
        $oColumnB = new Schema\ColumnOption();
        $oColumnRednerWithCallback = new Schema\ColumnRenderFunc('window.func');
        $oColumnB->setRenderFunc($oColumnRednerWithCallback);
        
        $this->getSchema()->addColumn('columnA',$oJobIdColumn);
        $this->getSchema()->addColumn('columnB',$oColumnB);
        
        
        # add init listener event
        
        $this->getEventRegistry()->addEvent(DataTableEventRegistry::CORE_INIT, 'window.func',null);
        
        
        
    }

    
    
    
}
/* End of class */
