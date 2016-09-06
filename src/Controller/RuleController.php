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
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;


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
        $oCtr->get('',[$this,'onRuleView'])
             ->bind('bookme-rule');
        

        $oCtr->get('list',[$this,'onRuleList'])
              ->bind('bookme-rule-list');
   
        $oCtr->get('new/one',[$this,'onNewRulePageOne'])
              ->bind('bookme-rule-new-one');
   
        $oCtr->get('new/two',[$this,'onNewRulePageTwo'])
              ->bind('bookme-rule-new-two');
   
        $oCtr->get('new/three',[$this,'onNewRulePageThree'])
              ->bind('bookme-rule-new-three');
  
        return $oCtr;
    }
    
    
    public function onRuleView()
    {
        
        
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
       $sRuleTable          = $aConfig['tablenames']['bm_rule'];
       
       // these two are mutually exclusive
       $sRuleOnStartAfter   = $oRequest->query->get(':sRuleOnStartAfter');
       $sRuleOnFinishBefore = $oRequest->query->get(':sRuleOnFinishBefore');
       
       $sRuleType           = $oRequest->query->get(':sRuleType');
       $sRuleNameFilter     = $oRequest->query->get(':sRuleNameFilter');
       $iCalYearFilter      = $oRequest->query->get(':iCalYear');
      
       $aQuery              = [];
       $aParams             = [':sRuleType' => $sRuleType];
       $aTypes              = [':sRuleType' => Type::STRING];
       
       $aQuery[] = ' select  r.* ';
       $aQuery[] = " $sRuleTable r ";
       $aQuery[] = " WHERE `r`.`sRuleType` = :sRuleType ";
       
       if(false === empty($sRuleOnStartAfter) && true == empty($sRuleOnFinishBefore)) {
            $aParams[':sRuleOnStartAfter'] =  date_create_from_format('YYYYMMDD',$sRuleOnStartAfter);
            $aTypes[':sRuleOnStartAfter']  = Type::DATE;
            
            $aQuery[] = " AND `r`.`start_from` >= :sRuleOnStartAfter ";    
       }
       
       if(false === empty($sRuleOnFinishBefore) && true === empty($sRuleOnStartAfter)) {
            $aParams[':sRuleOnFinishBefore'] =  date_create_from_format('YYYYMMDD',$sRuleOnFinishBefore);
            $aTypes[':sRuleOnFinishBefore']  = Type::DATE;
            
            $aQuery[] = " AND `r`.`end_at` <= :sRuleOnFinishBefore ";
       }
      
       if(false === empty($sRuleNameFilter)) {
           $aParams[':sRuleOnStartAfter'] =  $sRuleNameFilter;
            $aTypes[':sRuleOnStartAfter']  = Type::STRING;
            
            $aQuery[] = " AND `r`.`rule_name` >= concat(:sRuleOnStartAfter,'%') ";  
           
       }
       
       if(false === empty($iCalYearFilter)) {
           $aParams[':iCalYear'] =  $iCalYearFilter;
            $aTypes[':iCalYear']  = Type::getType(Type::INTEGER);
            
            $aQuery[] = " AND `r`.`cal_year` >= :iCalYear ";  
       }
        
      
       
       $sQuery  = implode(PHP_EOL,$aQuery);
      
      
       $aResult = $oDatabase->fetchAll($sQuery,$aParams,$aTypes);
        
      
       //return $app['twig']->render('admin_calendar.twig', ['title' => 'Setup Calendars','calendars' => $aCalendarYearList, 'nextYear' => $iNextCalendarYear, 'timeslots' => $aTimeslots], []);
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
       
        return $app['twig']->render('rule_page_one.twig', $aTemplateParams, []);
            
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
        $sRuleTypeId        = $request->query->get('sRuleTypeId');
        
        if(true === empty($iTimeslotId)) {
            $this->getFlash()->error('A Timeslot has not been selected');
            return $this->onNewRulePageOne($app, $request);
        }
        
        if(true === empty($iCalYear)) {
            $this->getFlash()->error('A Calendar Year not been selected');
            return $this->onNewRulePageOne($app, $request);
        }
        
        if(true === empty($sRuleTypeId)) {
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
            'sRuleTypeId'       => $sRuleTypeId,
            'bSingleDay'        => $bSingleDay,
            'aDayTimeslots'     => $aDayTimeslots,
            'iOpenSlotMinute'   => $iOpenSlotMinute,
            'iCloseSlotMinute'  => $iCloseSlotMinute,
            'sRuleName'         => $sRuleName,
            'sRuleDescription'  => $sRuleDescription,
         
        ];
       
        return $app['twig']->render('rule_page_two.twig', $aTemplateParams, []);
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
        $sRuleTypeId        = $request->query->get('sRuleTypeId');
        
        
        #process vars from this page
        
        $sStartDate         = $request->query->get('sStartDate'); 
        $sEndDate           = $request->query->get('sEndDate');
        $aRepeatDayofWeek   = $request->query->get('sRepeatDayofWeek');
        $aRepeatDayofMonth  = $request->query->get('sRepeatDayofMonth');
        $aRepeatsMonthofYear = $request->query->get('sRepeatsMonthofYear');
        $aRepeatWeekofYear   = $request->query->get('sRepeatsWeekofYear');
        
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
            'sRuleTypeId'       => $sRuleTypeId,
            'bSingleDay'        => $bSingleDay,
            'iOpenSlotMinute'   => $iOpenSlotMinute,
            'iCloseSlotMinute'  => $iCloseSlotMinute,
            'sRuleName'         => $sRuleName,
            'sRuleDescription'  => $sRuleDescription,
            'sStartDate'        => $sStartDate,
            'sEndDate'          => $sEndDate,
            'aRepeatDayofWeek'  => $aRepeatDayofWeek,
            'aRepeatDayofMonth' => $aRepeatDayofMonth,
            'aRepeatsMonthofYear' => $aRepeatsMonthofYear, 
            'aRepeatWeekofYear'  => $aRepeatWeekofYear,
            'aWeekTimeslots'    => $aWeekTimeslots,
        ];
        
        return $app['twig']->render('rule_page_three.twig', $aTemplateParams, []);
    }


    public function onRulePost(Application $app, Request $request)
    {
       $oDatabase     = $this->getDatabaseAdapter();
       $oNow          = $this->getNow();
       $oCommandBus   = $this->getCommandBus();
       $sStepThreeUrl = $app['url_generator']->generate('bookme-rule-new-three');
        
       
        $bSingleDay         = $request->request->get('bSingleDay'); 
        $iOpenSlotMinute    = $request->request->get('iOpenSlotMinute'); 
        $iCloseSlotMinute   = $request->request->get('iCloseSlotMinute'); 
        $sRuleName          = $request->request->get('sRuleName'); 
        $sRuleDescription   = $request->request->get('sRuleDescription'); 
        $iTimeslotId        = $request->request->get('iTimeslotId');  
        $iCalYear           = $request->request->get('iCalYear');
        $sRuleTypeId        = $request->request->get('sRuleTypeId');
        $sStartDate         = $request->request->get('sStartDate'); 
        $sEndDate           = $request->request->get('sEndDate');
        $aRepeatDayofWeek   = $request->request->get('sRepeatDayofWeek');
        $aRepeatDayofMonth   = $request->request->get('sRepeatDayofMonth');
        $aRepeatsMonthofYear = $request->query->get('sRepeatsMonthofYear');
        $aRepeatWeekofYear   = $request->query->get('sRepeatsWeekofYear');
      
    
        
        // Convert Repeat Rules into Cron string
        
        $sRepeatDayofWeek = implode(',',$aRepeatDayofWeek);
        $sRepeatDayofMonth = implode(',',$aRepeatDayofMonth);
        $sRepeatMonthofYear = implode(',',$aRepeatsMonthofYear);
        $sRepeatWeekofYear  = implode(',',$aRepeatWeekofYear);
        
        return $app->redirect($sStepThreeUrl.'?'.http_build_query($request->request->all()));
    
    }
   
}
/* End of Calendar Admin Controller */
