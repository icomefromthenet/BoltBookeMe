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
        $oDefaultOptions->overrideDefault('scrollY','60vh');
        $this->addOptionSet($oDefaultOptions);
        
        
        # set Ajax Options
        $oAjaxOption = new General\AjaxOptions();
        $oAjaxOption->setDataUrl($this->sDataUrl);
        $oAjaxOption->setHttpRequestMethod('GET');
        $oAjaxOption->setResponseDataType('json');
        $oAjaxOption->setResponseDataIndex('result');
        
        $this->addOptionSet($oAjaxOption);
        
        # set the Scroller Plugin
        $oScrollerPlugin = new Plugin\ScrollerPlugin();    
        $oScrollerPlugin->setUseLoadingIndicator(true);
        $oScrollerPlugin->setUseTrace(false);
        
        $this->addPlugin($oScrollerPlugin);
        
        // {"jobId":"0b04e273-e130-39d2-8cb6-ff76abc2be45","retryCount":3,"dateAdded":{"date":"2016-10-10 00:00:00","timezone_type":3,"timezone":"UTC"},"stateId":3,"jobData":"1","lockTimeout":{"date":"2016-10-10 04:00:00","timezone_type":3,"timezone":"UTC"},"handle":"55fabc26-47ab-3db2-a5e9-1e80ec4f0621","retryLast":null}
        //
        
        # Setup Column Schema
       
       
        // Job Id
        $oJobIdColumn = new Schema\ColumnOption();
        $oJobIdColumn->setDefaultContent('-')
                    ->setDataIndex('jobId')
                    ->setColumnHeading('Job Id')
                    ->setColumnName('job_id');
                   
        // Retry Count
        $oRetryCountColumn = new Schema\ColumnOption();
        $oRetryCountColumn->setDefaultContent('-')
                            ->setDataIndex('retryCount')
                            ->setColumnHeading('Retry Count')
                            ->setColumnName('retry_count');
        
        
        // dateAdded
        $oDateAddedColumn = new Schema\ColumnOption();
        $oDateAddedColumn->setDefaultContent('-');
        $oDateAddedColumn->setDataIndex('dateAdded.date')
                            ->setColumnHeading('Date Added')
                            ->setColumnName('date_added');
        
        // stateId
        $oStateIdColumn = new Schema\ColumnOption();
        $oStateIdColumn->setDefaultContent('-')
                        ->setDataIndex('stateId')
                        ->setColumnHeading('Job Status')
                        ->setColumnName('state_id');
        
        // jobData
        $oJobDataColumn = new Schema\ColumnOption();
        $oJobDataColumn->setDefaultContent('-')
                        ->setDataIndex('jobData')
                        ->setColumnHeading('Member Schedule Id')
                        ->setColumnName('job_data');
        
        // lockTimeout
        $oLockoutColumn = new Schema\ColumnOption();
        $oLockoutColumn->setDefaultContent('-')
                       ->setDataIndex('lockTimeout.date')
                       ->setColumnHeading('Lockout Timer')
                       ->setColumnName('lockout');
        
        // handle
        $oHandleColumn = new Schema\ColumnOption();
        $oHandleColumn->setDefaultContent('-')
                    ->setDataIndex('handle')
                    ->setColumnHeading('Worker Handler Id')
                    ->setColumnName('handle_id')
                    ->setColumnVisible(false);
        
        // retryLast
        $oLastRetry = new Schema\ColumnOption();
        $oLastRetry->setDefaultContent('-')
                    ->setDataIndex('retryLast.date')
                    ->setColumnHeading('Last Retry Date')
                     ->setColumnName('last_retry_date');
        
        
        // Append Columns
        $this->getSchema()->addColumn('Job ID',$oJobIdColumn);
        $this->getSchema()->addColumn('Retry Count', $oRetryCountColumn);
        $this->getSchema()->addColumn('Date Added', $oDateAddedColumn);
        $this->getSchema()->addColumn('Job Status',$oStateIdColumn);
        $this->getSchema()->addColumn('Member Schedule Id',$oJobDataColumn);
        $this->getSchema()->addColumn('Worker Handler Id',$oHandleColumn);
        $this->getSchema()->addColumn('Date Last Retry',$oLastRetry);
        $this->getSchema()->addColumn('Lockout Timer',$oLockoutColumn);
        
        
        # add init listener event
        
        $this->getEventRegistry()->addEvent(DataTableEventRegistry::CORE_INIT, 'window.func',null);
        
        
        
    }

    
    
    
}
/* End of class */
