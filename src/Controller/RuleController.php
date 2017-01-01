<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Controller;

use DateTime;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bolt\Storage\Database\Connection;


use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQueryHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\BetterResultSet;


/**
 * View and manage schedule rules
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class RuleController extends CommonController implements ControllerProviderInterface
{
    

    public function connect(Application $app)
    {
        /** @var $ctr \Silex\ControllerCollection */
        $oCtr = $app['controllers_factory'];

        $oCtr->post('',[$this,'onRulePost']);
        
        $oCtr->get('',[$this,'onRuleRemoveConfirm'])
             ->bind('bookme-rule-remove-confirm');
        
        $oCtr->post('',[$this,'onRuleRemove'])
             ->bind('bookme-rule-remove');

        $oCtr->get('list',[$this,'onRuleList'])
              ->bind('bookme-rule-list');
   
         $oCtr->get('search',[$this,'onRuleSearch'])
              ->bind('bookme-rule-search');
   
   
        $oCtr->get('new/one',[$this,'onNewRulePageOne'])
              ->bind('bookme-rule-new-one');
   
        $oCtr->get('new/two',[$this,'onNewRulePageTwo'])
              ->bind('bookme-rule-new-two');
   
        $oCtr->get('new/three',[$this,'onNewRulePageThree'])
              ->bind('bookme-rule-new-three');
  
        return $oCtr;
    }
    
    /**
     * Render a delete confirmation page
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRuleRemoveConfirm(Application $app, Request $request)
    {
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        
        $aData = [
            'oForm'         => $oSearchForm->createView(),    
            'title'         => 'Remove Rule',
            'subtitle'      => 'Confirm removal of this rule',
        ];
        
        return $app['twig']->render('@BookMe/rule_remove.twig', $aData, []);
    }

    /**
     * Delete and rule 
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRuleRemove(Application $app, Request $request)
    {
        
        
    }


    /**
     * search for a schedule rule 
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRuleSearch(Application $app, Request $request)
    {
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $oResult             = new BetterResultSet();
        $oForm               = $this->getForm('rule.builder')->getForm();
        $aErrors             = [];
        
        $oForm->handleRequest($request);
    
        if ($oForm->isSubmitted() && $oForm->isValid()) {
            $aSearch = $oForm->getData();
            $oHandler = new SelectQueryHandler($this->oContainer);
            $oResult = $oHandler->executeQuery($oHandler->getQuery('rule'),$aSearch);
        
        } else {
            
            $aErrors = [];
            foreach ($oForm->getErrors(true, true) as $formError) {
                $aErrors[] = $formError->getMessage();
            }
            
        }
        
        
            
        return $app->json(['results'=> $oResult->getAll(), 'errors' => $aErrors]);
    }
    
    /**
     * Load a list of schedule rules
     *
     * @param Application   $app
     * @param Request       $request
     * @return Response
     */
    public function onRuleList(Application $app, Request $request)
    {
       
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $oDataTable          = $this->getDataTable('rule');
        $oSearchForm         = $this->getForm('rule.builder')->getForm();
     
        //bind request vars to datatable data url
        $oDataTable->getOptionSet('AjaxOptions')->setRequestParams($request->query->all());

        //incude request params as values to our form
        $oSearchForm->handleRequest($request);
       
        $aData = [
            'oForm'         => $oSearchForm->createView(),    
            'title'         => 'Schedule Rules',
            'subtitle'      => 'Review and Search Schedule Rules',
            'sConfigString' => $oDataTable->writeConfig(), 
            'aEvents'       => $oDataTable->getEvents(),
        ];


        return $app['twig']->render('@BookMe/rule_list.twig', $aData, []);
    }


    /**
     * Create a new rule step 1 which ask for:
     * 
     * 1. Calendar Year
     * 2. Rule Type
     * 3. Timeslot 
     * 
     * Can accept the rule type from the query string
     */ 
    public function onNewRulePageOne(Application $app, Request $request)
    {
       $oDatabase           = $this->getDatabaseAdapter();
       $oNow                = $this->getNow();
       $aConfig             = $this->getExtensionConfig();
       $sCalYearTable       = $aConfig['tablenames']['bm_calendar_years'];
       $sRuleTypeTable      = $aConfig['tablenames']['bm_rule_type'];
       $sTimeslotTable      = $aConfig['tablenames']['bm_timeslot'];
       
       # Load list of rule types
       # check if the rule type request matchs on on the list
        
       $sRequestRuleType = $request->query->get('sRuleTypeCode');
       $iCalYear         = $request->query->get('iCalYear');
       $iTimeslotId      = $request->query->get('iTimeslotId');
        
       if(true === empty($sRequestRuleType)) {
            $aRuleTypeList = $oDatabase->fetchAll("SELECT rule_type_id, rule_code 
                                                    FROM $sRuleTypeTable 
                                                    ORDER BY rule_code ASC");
            
       }
       else {
            $aRuleTypeList = $oDatabase->fetchAll("SELECT rule_type_id, rule_code 
                                                    FROM $sRuleTypeTable 
                                                    WHERE rule_code = :sRuleCode",[':sRuleCode' => $sRequestRuleType],[Type::STRING]);
       }
    
        
       if(0 === count($aRuleTypeList)) {
            throw new \RuntimeException('Unable to load rule types or match rule type to request value');
       }
        
        
       # Load a list of active calendars for display in the template
       $aCalendarYearList = $oDatabase->fetchAll("SELECT y 
                                                    FROM $sCalYearTable 
                                                    ORDER BY y ASC");
       
       if(empty($iCalYear)) {
        $iActiveCalYear   = $this->getNow()->format('Y');
       } else {
        $iActiveCalYear = $iCalYear;
       }
       
       
       
       # Load a list of timeslots
       $aTimeslots      = $oDatabase->fetchAll("SELECT timeslot_id, timeslot_length 
                                                    FROM $sTimeslotTable 
                                                    WHERE is_active_slot  = 1
                                                    ORDER BY timeslot_length ASC");
                                               
       $aTemplateParams = [
          'title'               => 'New Rule Page 1',
          'aCalendarYearList'   => $aCalendarYearList,
          'aRuleTypeList'       => $aRuleTypeList,
          'iActiveCalYear'      => $iActiveCalYear,
          'sRequestRuleType'    => $sRequestRuleType,
          'aTimeslots'          => $aTimeslots,
          'iTimeslotId'         => $iTimeslotId,
        ];
       
        
        
        return $app['twig']->render('@BookMe/rule_page_one.twig', $aTemplateParams, []);
            
    }
    
    /**
     * Create a new rule step 2 which ask for:
     * 
     * 1. Single or Repeat Rule
     * 2. Start Date
     * 3. End Date
     * 4. Rule Name
     * 5. Starting Time Slot
     * 6. Ending Time Slot
     * 
     * Will require accept the rule type,calendar year from the query string
     * 
     */ 
    public function onNewRulePageTwo(Application $app, Request $request)
    {
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $sTimeslotSlotTable  = $aConfig['tablenames']['bm_timeslot_day'];
       
        # Process vars from Page One
        
        $iTimeslotId        = $request->query->get('iTimeslotId');  
        $iCalYear           = $request->query->get('iCalYear');
        $iRuleTypeId        = $request->query->get('iRuleTypeId');
        
        if(true === empty($iTimeslotId)) {
            $this->getFlash()->error('A Timeslot has not been selected');
            return $this->onNewRulePageOne($app, $request);
        }
        
        if(true === empty($iCalYear)) {
            $this->getFlash()->error('A Calendar Year not been selected');
            return $this->onNewRulePageOne($app, $request);
        }
        
        if(true === empty($iRuleTypeId)) {
            $this->getFlash()->error('A Rule Type has not been selected');
            return $this->onNewRulePageOne($app, $request);
        }
        
        # Load data for Page two
        
        $bSingleDay         = $request->query->get('bSingleDay'); 
        $iOpenSlotMinute    = $request->query->get('iOpenSlotMinute'); 
        $iCloseSlotMinute   = $request->query->get('iCloseSlotMinute'); 
        $sRuleName          = $request->query->get('sRuleName'); 
        $sRuleDescription   = $request->query->get('sRuleDescription'); 
        
        
        $aDayTimeslots = $oDatabase->fetchAll("SELECT `timeslot_day_id`, `open_minute`, `close_minute` 
                                               FROM $sTimeslotSlotTable
                                               where timeslot_id = :iTimeSlotId
                                               ORDER BY `open_minute`",[':iTimeSlotId' => $iTimeslotId],[TYPE::INTEGER]);
    
    
        
        $aTemplateParams = [
            'title'             => 'New Rule Page 2',
            'iTimeslotId'       => $iTimeslotId,
            'iCalYear'          => $iCalYear,
            'iRuleTypeId'       => $iRuleTypeId,
            'bSingleDay'        => $bSingleDay,
            'aDayTimeslots'     => $aDayTimeslots,
            'iOpenSlotMinute'   => $iOpenSlotMinute,
            'iCloseSlotMinute'  => $iCloseSlotMinute,
            'sRuleName'         => $sRuleName,
            'sRuleDescription'  => $sRuleDescription,
         
        ];
       
        return $app['twig']->render('@BookMe/rule_page_two.twig', $aTemplateParams, []);
    }
    
     /**
     * Create a new rule step 3 which ask for:
     * 
     * 1. Repeat Rules Day.
     * 2. Repeat Rules Week.
     * 3. Repeat Rule Month.
     * 
     * Will require accept the rule type,calendar year Singe or Repeat Rule, 
     * Start Date, End Date, Rule Name, Starting Time Slot , Ending Time Slot
     * 
     * If page 2 return single instance then this will call onRulePost
     * 
     */ 
    public function onNewRulePageThree(Application $app, Request $request)
    {
        $oDatabase           = $this->getDatabaseAdapter();
        $oNow                = $this->getNow();
        $aConfig             = $this->getExtensionConfig();
        $sTimeslotSlotTable  = $aConfig['tablenames']['bm_timeslot_day'];
        $sCalWeekTable       = $aConfig['tablenames']['bm_calendar_weeks'];
        $sCalTable           = $aConfig['tablenames']['bm_calendar'];
        
       
        
        # process vars from page 2
        $bSingleDay         = $request->query->get('bSingleDay'); 
        $iOpenSlotMinute    = $request->query->get('iOpenSlotMinute'); 
        $iCloseSlotMinute   = $request->query->get('iCloseSlotMinute'); 
        $sRuleName          = $request->query->get('sRuleName'); 
        $sRuleDescription   = $request->query->get('sRuleDescription'); 
        $iTimeslotId        = $request->query->get('iTimeslotId');  
        $iCalYear           = $request->query->get('iCalYear');
        $iRuleTypeId        = $request->query->get('iRuleTypeId');
        
        
        #process vars from this page
        
        $sStartDate         = $request->query->get('sStartDate'); 
        $sEndDate           = $request->query->get('sEndDate');
        $aRepeatDayofWeek   = $request->query->get('sRepeatDayofWeek');
        $aRepeatDayofMonth  = $request->query->get('sRepeatDayofMonth');
        $aRepeatsMonthofYear = $request->query->get('sRepeatMonthofYear');
        $aRepeatWeekofYear   = $request->query->get('sRepeatsWeekofYear');
        $bSelectAllDayofWeek = $request->query->get('bSelectAllDayofWeek');
        $bSelectAllDayMonth  = $request->query->get('bSelectAllDayMonth');
        $bSelectAllWeek      = $request->query->get('bSelectAllWeek');
        $bSelectAllMonth     = $request->query->get('bSelectAllMonth');
        
        
        # load repeat view
        $aDayTimeslots = [];
        $oSTH = $oDatabase->executeQuery("SELECT `w`, `open_date`, `close_date`
                                               FROM $sCalWeekTable
                                               where y = :y
                                               ORDER BY `open_Date`",[':y' => $iCalYear],[TYPE::INTEGER]);
        
        $oInteger       = TYPE::getType(TYPE::INTEGER);
        $oDate          = TYPE::getType(TYPE::DATE);
        $oPlatform      = $oDatabase->getDatabasePlatform();
        $aWeekTimeslots = [];
        
        while ($row = $oSTH->fetch()) {
            $aWeekTimeslots[] = [
             'w'           => $oInteger->convertToPHPValueSQL($row['w'], $oPlatform),
             'open_date'   => $oDate->convertToPHPValueSQL($row['open_date'], $oPlatform),
             'close_date'  => $oDate->convertToPHPValueSQL($row['close_date'], $oPlatform),
            ];
        }
            
        # load single view
        
        
          $aTemplateParams = [
            'title'             => 'New Rule Page 3',
            'iTimeslotId'       => $iTimeslotId,
            'iCalYear'          => $iCalYear,
            'iRuleTypeId'       => $iRuleTypeId,
            'bSingleDay'        => $bSingleDay,
            'iOpenSlotMinute'   => $iOpenSlotMinute,
            'iCloseSlotMinute'  => $iCloseSlotMinute,
            'sRuleName'         => $sRuleName,
            'sRuleDescription'  => $sRuleDescription,
            'sStartDate'        => $sStartDate,
            'sEndDate'          => $sEndDate,
            'aRepeatDayofWeek'  => $aRepeatDayofWeek,
            'aRepeatDayofMonth' => $aRepeatDayofMonth,
            'aRepeatMonthofYear' => $aRepeatsMonthofYear, 
            'aRepeatWeekofYear'  => $aRepeatWeekofYear,
            'aWeekTimeslots'    => $aWeekTimeslots,
            
            'bSelectAllDayofWeek' => $bSelectAllDayofWeek,
            'bSelectAllDayMonth'  => $bSelectAllDayMonth,
            'bSelectAllWeek'      => $bSelectAllWeek,
            'bSelectAllMonth'     => $bSelectAllMonth,
        ];
        
        return $app['twig']->render('@BookMe/rule_page_three.twig', $aTemplateParams, []);
    }


    public function onRulePost(Application $app, Request $request)
    {
       $oDatabase     = $this->getDatabaseAdapter();
       $oNow          = $this->getNow();
       $oCommandBus   = $this->getCommandBus();
       $sStepThreeUrl = $app['url_generator']->generate('bookme-rule-new-three');
        
       
        $bSingleDay         = filter_var($request->request->get('bSingleDay'),FILTER_VALIDATE_BOOLEAN); 
        $iOpenSlotMinute    = filter_var($request->request->get('iOpenSlotMinute'),FILTER_VALIDATE_INT); 
        $iCloseSlotMinute   = filter_var($request->request->get('iCloseSlotMinute'),FILTER_VALIDATE_INT); 
        $sRuleName          = filter_var($request->request->get('sRuleName'),FILTER_SANITIZE_STRING); 
        $sRuleDescription   = filter_var($request->request->get('sRuleDescription'),FILTER_SANITIZE_STRING); 
        $iTimeslotId        = filter_var($request->request->get('iTimeslotId'),FILTER_VALIDATE_INT);  
        $iCalYear           = filter_var($request->request->get('iCalYear'),FILTER_VALIDATE_INT);
        $iRuleTypeId        = filter_var($request->request->get('iRuleTypeId'),FILTER_VALIDATE_INT);
        $sStartDate         = filter_var($request->request->get('sStartDate'),FILTER_SANITIZE_STRING); 
        $sEndDate           = filter_var($request->request->get('sEndDate'),FILTER_SANITIZE_STRING);
        $aRepeatDayofWeek    = $request->request->get('sRepeatDayofWeek');
        $aRepeatDayofMonth   = $request->request->get('sRepeatDayofMonth');
        $aRepeatsMonthofYear = $request->request->get('sRepeatMonthofYear');
        $aRepeatWeekofYear   = $request->request->get('sRepeatsWeekofYear');
        
        $bSelectAllDayofWeek = $request->request->get('bSelectAllDayofWeek');
        
        // Convert Repeat Rules into Cron string
        if(false == $bSingleDay) {
            
            $sRepeatDayofWeek   = implode(',',$aRepeatDayofWeek);
            $sRepeatDayofMonth  = implode(',',$aRepeatDayofMonth);
            $sRepeatMonthofYear = implode(',',$aRepeatsMonthofYear);
            $sRepeatWeekofYear  = implode(',',$aRepeatWeekofYear);
            
        } else {
              
            $sRepeatDayofWeek   = '*';
            $sRepeatDayofMonth  = '*';
            $sRepeatMonthofYear = '*';
            $sRepeatWeekofYear  = '*';
            
        }
        
        // Convert the dates into dateTime
        if(empty($sStartDate) || empty($sEndDate)) {
             $this->getFlash()->warning('Start or stop date has not been provided');
             
             return $app->redirect($sStepThreeUrl.'?'.http_build_query($request->request->all()));
             
        } else {
            $oStartDate  = date_create_from_format('d/m/Y',$sStartDate);
            $oEndDate    = date_create_from_format('d/m/Y',$sEndDate);
        }
        
        // Execute the command
        
        try {
            $oCommand = new CreateRuleCommand($oStartDate,$oEndDate, $iRuleTypeId, $iTimeslotId, $iOpenSlotMinute, $iCloseSlotMinute, $sRepeatDayofWeek, $sRepeatDayofMonth, $sRepeatMonthofYear, $sRepeatWeekofYear,$bSingleDay, $sRuleName, $sRuleDescription);
           
            $oCommandBus->handle($oCommand);                     
        }
        catch(ValidationException $e) {
            
            // print validation message to the user
            return $app['twig']->render('validation_error.twig', [
                 'title'             => 'Error: Unable to Save New Rule',
                'aFieldMap' =>  [
                    'rule_type_id'      => 'Rule Type',
                    'start_from'        => 'Start Date',
                    'end_at'            => 'End Date',
                    'repeat_minute'     => 'Repeat Minute',
                    'repeat_hour'       => 'Repeat Hour',
                    'repeat_dayofweek'  => 'Repeat Day of Week',
                    'repeat_dayofmonth' => 'Repeat Day of Month',
                    'repeat_month'      => 'Repeat Month',
                    'repeat_weekofyear' => 'Repeat Week of Year',
                    'opening_slot'      => 'Opening Timeslot',
                    'closing_slot'      => 'Closing Timeslot',
                    'timeslot_id'       => 'Timeslot',
                    'is_single_day'     => 'Singe or Repeat',
                    'rule_name'         => 'Rule Name',  
                    'rule_description'  => 'Rule Description',
                ],
                'aErrors'   => $e->getValidationFailures(),
                'sBackBtnUrl'  => $sStepThreeUrl.'?'.http_build_query($request->request->all()),
                'sBackBtnText' => 'Back to New Rule Page 3',    
            ], []);
 
            
        }
        catch(BookMeException $e) {
            
            $this->getFlash()->error('Unable to save new rule with error ::'.$e->getMessage());
          
            return $app->redirect($sStepThreeUrl.'?'.http_build_query($request->request->all()));

        }
        
        $this->getFlash()->info('Saved new Rule at id::'.$oCommand->getRuleId());
        
        
    }
   
}
/* End of Calendar Admin Controller */
