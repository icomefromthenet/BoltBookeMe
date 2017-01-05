<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\AbstractDataTableManager;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Plugin;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Output;

/**
 * DataTable for the Members
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class MemberDataTable extends AbstractDataTableManager
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
        
          
        # Setup Select Plugin
        $oSelectPlugin = new Plugin\SelectPlugin();
        $oSelectPlugin->setItemRows()
                      ->setSelectStyleSingleRow()
                      ->setSelectCssClassName('success');
        
        
        $this->addPlugin($oSelectPlugin);
        
        
        # Add Action Buttons
        
        $oButtonButton = new Plugin\ButtonPlugin();
        
        
        $oAddMember = new Plugin\Button\StandardButton();
        $oAddMember->setButtonText('<i class="fa fa-address-card"></i> Add Member')
                          ->setInitialEnableState(true)
                          ->setActionCallback(new Plugin\Button\ActionCallback('bookme.datatable.button.onMemberAdd'))
                          ->setCSSClassName('btn-primary')
                          ->setHtmlAttributeTitle('Add New Member');
        
        $oButtonButton->addButton('add',$oAddMember);
        
        $oEditMember  = new Plugin\Button\StandardButton();
        $oEditMember->setButtonText('<i class="fa fa-pencil-square"></i> Edit Member')
                    ->setInitialEnableState(true)
                    ->setActionCallback(new Plugin\Button\ActionCallback('bookme.datatable.button.onMemberEdit'))
                    ->setCSSClassName('btn-default')
                    ->setHtmlAttributeTitle('Edit Member');
        
        
        $oButtonButton->addButton('edit',$oEditMember);
        
        $this->addPlugin($oButtonButton);
        
        
        # Setup Table Dom Formatting
        //$oDomFormat = new General\DomFormatOption('<Bf<t>ip>');
        
        //$this->addOptionSet($oDomFormat);
        
        
        # Setup Column Schema
     
        // membershipId
        $oMemberIdColumn = new Schema\ColumnOption();
        $oMemberIdColumn->setDefaultContent('-')
                    ->setDataIndex('membershipId')
                    ->setColumnHeading('Membership Id')
                    ->setColumnName('membershipId');
        
        // registeredDate
        $oMemberRegisterColumn = new Schema\ColumnOption();
        $oMemberRegisterColumn->setDefaultContent('-')
                    ->setDataIndex('registeredDate.date')
                    ->setColumnHeading('Registration Date')
                    ->setColumnName('registeredDate');
        
        $oMemberNameColumn = new Schema\ColumnOption();
        $oMemberNameColumn->setDefaultContent('-')
                    ->setDataIndex('memberName')
                    ->setColumnHeading('Member Name')
                    ->setColumnName('memberName');
                    
        $oScheduleIdColumn = new Schema\ColumnOption();
        $oScheduleIdColumn->setDefaultContent('-')
                    ->setDataIndex('scheduleId')
                    ->setColumnHeading('Schedule Id')
                    ->setColumnVisible(false)
                    ->setColumnName('scheduleId');
        
        $oCalYearColumn = new Schema\ColumnOption();
        $oCalYearColumn->setDefaultContent('-')
                    ->setDataIndex('calYear')
                    ->setColumnHeading('Current Schedule Year')
                    ->setColumnName('calYear');
        
        $oCarryOverColumn = new Schema\ColumnOption();
        $oCarryOverColumn->setDefaultContent('-')
                    ->setDataIndex('isCarryover')
                    ->setColumnHeading('Rollover Next Schedule')
                    ->setColumnVisible(false)
                    ->setColumnName('isCarryover');
        
        $oScheduleStopDateColumn = new Schema\ColumnOption(); 
        $oScheduleStopDateColumn->setDefaultContent('-')
                    ->setDataIndex('closeDate.date')
                    ->setColumnHeading('Schedule Stop Date')
                    ->setColumnName('closeDate');
        
        
        // Append Columns
    
        $this->getSchema()->addColumn('membershipId',$oMemberIdColumn);
        $this->getSchema()->addColumn('registeredDate',$oMemberRegisterColumn);
        $this->getSchema()->addColumn('memberName', $oMemberNameColumn);
        $this->getSchema()->addColumn('scheduleId', $oScheduleIdColumn);
        $this->getSchema()->addColumn('calYear',$oCalYearColumn);
        $this->getSchema()->addColumn('isCarryover', $oCarryOverColumn);
        $this->getSchema()->addColumn('closeDate', $oScheduleStopDateColumn);
        
        
        # add init listener event
        
        $this->getEventRegistry()->addEvent(DataTableEventRegistry::CORE_INIT, 'window.func',null);
        
      
        
    }

    
    
    
}
/* End of class */
