<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\AbstractDataTableManager;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Output;

/**
 * DataTable for the Schedule Rules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class RuleDataTable extends AbstractDataTableManager
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
        //$oDefaultOptions->overrideDefault('scrollY','40vh');
        $this->addOptionSet($oDefaultOptions);
        
        
        # set Ajax Options
        $oAjaxOption = new General\AjaxOptions();
        $oAjaxOption->setDataUrl($this->sDataUrl);
        $oAjaxOption->setHttpRequestMethod('GET');
        $oAjaxOption->setResponseDataType('json');
        $oAjaxOption->setResponseDataIndex('results');
        
        $this->addOptionSet($oAjaxOption);
        
        # set the Scroller Plugin
        $oScrollerPlugin = new Plugin\ScrollerPlugin();    
        $oScrollerPlugin->setUseLoadingIndicator(true);
        $oScrollerPlugin->setUseTrace(false);
        
        $this->addPlugin($oScrollerPlugin);
        
        
        # Setup Column Schema
     
        // RuleId
        $oRuleIdColumn = new Schema\ColumnOption();
        $oRuleIdColumn->setDefaultContent('-')
                    ->setDataIndex('ruleId')
                    ->setColumnHeading('Rule Id')
                    ->setColumnName('ruleId');
                   
        // Rule Name
        $oRuleNameColumn = new Schema\ColumnOption();
        $oRuleNameColumn->setDefaultContent('-')
                            ->setDataIndex('ruleName')
                            ->setColumnHeading('Retry Name')
                            ->setColumnName('ruleName');
        
        // Rule Description
        $oRuleDescColumn = new Schema\ColumnOption();
        $oRuleDescColumn->setDefaultContent('-')
                        ->setDataIndex('ruleDesc')
                        ->setColumnHeading('Rule Desc')
                        ->setColumnName('ruleDesc');
        
        
        // Start Date 
        // e.g {"date":"2016-10-10 00:00:00","timezone_type":3,"timezone":"UTC"}
        $oStartDateColumn = new Schema\ColumnOption();
        $oStartDateColumn->setDefaultContent('-')
                         ->setDataIndex('startFrom.date')
                            ->setColumnHeading('Start Date')
                            ->setColumnName('startFrom');
        
        // End Date
        // e.g {"date":"2016-10-10 00:00:00","timezone_type":3,"timezone":"UTC"}
        $oEndDateColumn = new Schema\ColumnOption();
        $oEndDateColumn->setDefaultContent('-')
                        ->setDataIndex('endAt.date')
                        ->setColumnHeading('End Date')
                        ->setColumnName('endAt');
        
        // Single Day Rule
        // e.g 1
        $oSingleDayColumn = new Schema\ColumnOption();
        $oSingleDayColumn->setDefaultContent('-')
                        ->setDataIndex('isSingleDay')
                        ->setColumnHeading('Singe Day Rule')
                        ->setColumnName('isSingleDay');
        
        // Calendar Year
        $oCalendarYearColumn = new Schema\ColumnOption();
        $oCalendarYearColumn->setDefaultContent('-')
                       ->setDataIndex('calYear')
                       ->setColumnHeading('Calendar Year')
                       ->setColumnName('calYear')
                       ->setColumnVisible(false);
        
        // Rule Type Id
        $oRuleTypeColumn = new Schema\ColumnOption();
        $oRuleTypeColumn->setDefaultContent('-')
                        ->setDataIndex('ruleTypeId')
                        ->setColumnHeading('Rule Type Id')
                        ->setColumnVisible(false)
                        ->setColumnName('ruleTypeId');
                        
        
        // Rule Type Code   
        $oRuleTypeCodeColumn = new Schema\ColumnOption();
        $oRuleTypeCodeColumn->setDefaultContent('-')
                            ->setDataIndex('ruleCode')
                            ->setColumnHeading('Rule Type')
                            ->setColumnName('ruleCode')
                            ->setColumnVisible(false);
     
        $oTimeslotIdColumn = new Schema\ColumnOption();
        $oTimeslotIdColumn->setDefaultContent('-')
                          ->setDataIndex('timeslotId')
                          ->setColumnHeading('Timeslot Id')
                          ->setColumnName('timeslotId')
                          ->setColumnVisible(false);
                          
        
        $oOpenSlotColumn = new Schema\ColumnOption();
        $oOpenSlotColumn->setDefaultContent('-')
                        ->setDataIndex('openSlot')
                        ->setColumnHeading('Start Time')
                        ->setColumnName('openSlot');
        
        $oCloseSlotColumn = new Schema\ColumnOption();
        $oCloseSlotColumn->setDefaultContent('-')
                         ->setDataIndex('closeSlot')
                         ->setColumnHeading('Finish Time')
                         ->setColumnName('closeSlot');
        
        $oTimeslotLengthColumn = new Schema\ColumnOption();
        $oTimeslotLengthColumn->setDefaultContent('-')
                        ->setDataIndex('timeslotLength')
                        ->setColumnHeading('Timeslot')
                        ->setColumnName('timeslotLength')
                        ->setRenderFunc(new Schema\ColumnRenderFunc('bookme.datatable.render.timeslotLength'));
                        
       
        
        // Append Columns
        $this->getSchema()->addColumn('Rule Id',$oRuleIdColumn);
        $this->getSchema()->addColumn('Rule Name',$oRuleNameColumn);
        $this->getSchema()->addColumn('Rule Desc',$oRuleDescColumn);
        $this->getSchema()->addColumn('Start Date',$oStartDateColumn);
        $this->getSchema()->addColumn('End Date',$oEndDateColumn);
        $this->getSchema()->addColumn('Single Day', $oSingleDayColumn);
        $this->getSchema()->addColumn('Calendar Year',$oCalendarYearColumn);
        $this->getSchema()->addColumn('Rule Type Id', $oRuleTypeColumn);
        $this->getSchema()->addColumn('Rule Type Code',$oRuleTypeCodeColumn);
        $this->getSchema()->addColumn('Timeslot Id', $oTimeslotIdColumn);
        $this->getSchema()->addColumn('Timeslot Length', $oTimeslotLengthColumn);
        $this->getSchema()->addColumn('Opening Slot', $oOpenSlotColumn);
        $this->getSchema()->addColumn('Closing Slot', $oCloseSlotColumn);
        
        
        # add init listener event
        
        $this->getEventRegistry()->addEvent(DataTableEventRegistry::CORE_INIT, 'window.func',null);
        
        
        
    }

    
    
    
}
/* End of class */
