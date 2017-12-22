<?php
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

// Install base location
if (!defined('TEST_ROOT')) {
    define('TEST_ROOT', realpath(__DIR__. '/../vendor/bolt/bolt/'));
}


// PHPUnit's base location
if (!defined('PHPUNIT_ROOT')) {
    define('PHPUNIT_ROOT', realpath(TEST_ROOT . '/tests/phpunit/unit'));
}



// PHPUnit's temporary web root… It doesn't exist yet, so we can't realpath()
if (!defined('PHPUNIT_WEBROOT')) {
    define('PHPUNIT_WEBROOT', PHPUNIT_ROOT . '/web-root');
}


if (!defined('NUT_PATH')) {
    define('NUT_PATH', realpath(TEST_ROOT . '/app/nut'));
}



if (!defined('EXTENSION_AUTOLOAD')) {
    define('EXTENSION_AUTOLOAD',  realpath(dirname(__DIR__) . '/vendor/autoload.php'));
}

if (!defined('BOOKME_EXTENSION_PATH')) {
    define('BOOKME_EXTENSION_PATH',  realpath('/../'.dirname(__DIR__)));
}

// Vendor Auto Load
require_once __DIR__.'/../vendor/autoload.php';

// Auto Load Extensions
require_once EXTENSION_AUTOLOAD;

// Get Database Connection 

function getDoctrineDatabaseConnection()
{
   
   static $conn;
   
   if(empty($conn)) {
   
       $config = new \Doctrine\DBAL\Configuration();
        
       $connectionParams =  [
            'dbname'       => $GLOBALS['DEMO_DATABASE_SCHEMA'],
            'driver'       => $GLOBALS['DEMO_DATABASE_TYPE'],
            'password'     => $GLOBALS['DEMO_DATABASE_PASSWORD'],
            'prefix'       => 'bolt_',
            'user'         => getenv('C9_USER') == false ? $GLOBALS['DEMO_DATABASE_USER'] :getenv('C9_USER'),
            'host'         => getenv('IP') == false ? $GLOBALS['DEMO_DATABASE_HOST'] : getenv('IP'),
            'port'         => $GLOBALS['DEMO_DATABASE_PORT'],
        ];
        
        
       $conn  = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    
   }
    
   return $conn;
}


function getDatabaseTableList()
{
    
     return [
         'bm_ints'              => 'bolt_ints'   
        ,'bm_calendar'          => 'bolt_bm_calendar'    
        ,'bm_calendar_weeks'    => 'bolt_bm_calendar_weeks'      
        ,'bm_calendar_months'   => 'bolt_bm_calendar_months'  
        ,'bm_calendar_quarters' => 'bolt_bm_calendar_quarters'  
        ,'bm_calendar_years'    => 'bolt_bm_calendar_years'
        
        ,'bm_timeslot'          => 'bolt_bm_timeslot'
        ,'bm_timeslot_day'      => 'bolt_bm_timeslot_day'
        ,'bm_timeslot_year'      => 'bolt_bm_timeslot_year'
        
        ,'bm_schedule_membership' => 'bolt_bm_schedule_membership'
        ,'bm_schedule_team'       => 'bolt_bm_schedule_team'
        ,'bm_schedule'            => 'bolt_bm_schedule'
        ,'bm_schedule_slot'       => 'bolt_bm_schedule_slot'
        ,'bm_schedule_team_members' => 'bolt_bm_schedule_team_members'
       
       
        ,'bm_booking'             => 'bolt_bm_booking'
        ,'bm_booking_conflict'    => 'bolt_bm_booking_conflict'
        
        ,'bm_rule_type'           => 'bolt_bm_rule_type'
        ,'bm_rule'                => 'bolt_bm_rule'
        ,'bm_rule_series'         => 'bolt_bm_rule_series'
        ,'bm_rule_schedule'       => 'bolt_bm_rule_schedule'
        
        ,'bm_tmp_rule_series'     => 'bm_tmp_rule_series'
        
        ,'bm_customer'            => 'bolt_bm_customer'
        ,'bm_activity'            => 'bolt_bm_activity'
        ,'bm_appointment_status'  => 'bolt_bm_appointment_status'
        ,'bm_appointment'         => 'bolt_bm_appointment'
        
        ,'bm_queue'               => 'bolt_bm_queue'
        ,'bm_queue_monitor'       => 'bolt_bm_queue_monitor'
        ,'bm_queue_transition'    => 'bolt_bm_queue_transition'
        
        // Holiday Rule Bundle
        
        ,'bm_holiday'             => 'bolt_bm_holiday',
        
        // Voucher Bundle
        'bm_voucher_group'         => 'bolt_bm_voucher_group',
        'bm_voucher_gen_rule'      => 'bolt_bm_voucher_gen_rule',
        'bm_voucher_type'          => 'bolt_bm_voucher_type',
        'bm_voucher_instance'      => 'bolt_bm_voucher_instance',
        
        // Ledger Tables
        'ledger_account'           => 'bolt_bm_ledger_account',
        'ledger_account_group'     => 'bolt_bm_ledger_account_group',
        'ledger_org_unit'          => 'bolt_bm_ledger_org_unit',
        'ledger_user'              => 'bolt_bm_ledger_user',
        'ledger_journal_type'      => 'bolt_bm_ledger_journal_type',
        'ledger_transaction'       => 'bolt_bm_ledger_transaction',
        'ledger_entry'             => 'bolt_bm_ledger_entry',
        'ledger_daily'             => 'bolt_bm_ledger_daily',
        'ledger_daily_user'        => 'bolt_bm_ledger_daily_user',
        'ledger_daily_org'         => 'bolt_bm_ledger_daily_org',
        
        ];
    
}


function getTestDate()
{
    $oDatabase = getDoctrineDatabaseConnection();
    
    $oDBPlatform  = $oDatabase->getDatabasePlatform();
    $oDateType    = Doctrine\DBAL\Types\Type::getType(Doctrine\DBAL\Types\Type::DATE); 
    $sNow         = $oDatabase->fetchColumn("select date_format(NOW(),'%Y-%m-%d')  ",[],0,[]);
        
    return $oDateType->convertToPHPValue($sNow,$oDBPlatform);
    
}


function runFixture()
{
   $oDatabase   = getDoctrineDatabaseConnection();
   $aTableNames = getDatabaseTableList();    
   $oNow        = getTestDate();   
   $aConfig     = [];
   
   
   // Common
   
   $oBasicFixture = new Fixture\BasicFixture($oDatabase, $oNow, $aTableNames);
   $oBasicFixture->runFixture([]);
   
   
   // Calendar
   
   $oCalendarFixture = new Fixture\CalendarFixture($oDatabase,$oNow, $aTableNames);
   $oCalendarFixture->runFixture([]);
   
   
   // Slots
   
   $oSlotFixture = new Fixture\SlotFixture($oDatabase,$oNow, $aTableNames);
   $aTimeSlots = $oSlotFixture->runFixture([]);

   $aConfig['five_minute']     = $aTimeSlots['iFiveMinuteTimeslot'];
   $aConfig['ten_minute']      = $aTimeSlots['iTenMinuteTimeslot'];
   $aConfig['seven_minute']    = $aTimeSlots['iSevenMinuteTimeslot'];
   $aConfig['fifteen_minute']  = $aTimeSlots['iFifteenMinuteTimeslot'];
   
   
   // Register new Members
   
   $oMembersFixture = new Fixture\MemberFixture($oDatabase, $oNow, $aTableNames);
   $aMembers        = $oMembersFixture->runFixture([]);
   
   $aConfig['member_one']   = $aMembers['iMemberOne'];
   $aConfig['member_two']   = $aMembers['iMemberTwo'];
   $aConfig['member_three']   = $aMembers['iMemberThree'];
   $aConfig['member_four']   = $aMembers['iMemberFour'];
   
   
   // Create Teams
   $oNewTeamFixture  = new Fixture\NewTeamFixture($oDatabase, $oNow, $aTableNames);
   $aTeams = $oNewTeamFixture->runFixture([]);
   
   $aConfig['team_two'] = $aTeams['iTeamTwo'];
   $aConfig['team_one'] = $aTeams['iTeamOne'];
   
   // Assign Teams to Members
  
    $aAssignTeamConfig = [
      ['TEAM_ID' => $aTeams['iTeamOne'] ,'MEMBERSHIP_ID' => $aMembers['iMemberOne']   ],
      ['TEAM_ID' => $aTeams['iTeamOne'] ,'MEMBERSHIP_ID' => $aMembers['iMemberTwo']   ],
      ['TEAM_ID' => $aTeams['iTeamOne'] ,'MEMBERSHIP_ID' => $aMembers['iMemberThree'] ],
      ['TEAM_ID' => $aTeams['iTeamOne'] ,'MEMBERSHIP_ID' => $aMembers['iMemberFour']  ],
      
    ]; 
     
    $oAssignTeamFixture = new Fixture\AssignTeamFixture($oDatabase, $oNow, $aTableNames);
    $oAssignTeamFixture->runFixture($aAssignTeamConfig);
  
  
  
   // Create Schedules 
   
   $aScheduleConfig = [
    'iMemberOneSchedule'    => ['CALENDAR_YEAR' => $oNow->format('Y'), 'MEMBERSHIP_ID' => $aMembers['iMemberOne'],   'TIMESLOT_ID' => $aTimeSlots['iFiveMinuteTimeslot'] ],
    'iMemberTwoSchedule'    => ['CALENDAR_YEAR' => $oNow->format('Y'), 'MEMBERSHIP_ID' => $aMembers['iMemberTwo'] ,  'TIMESLOT_ID' => $aTimeSlots['iFiveMinuteTimeslot'] ],
    'iMemberThreeSchedule'  => ['CALENDAR_YEAR' => $oNow->format('Y'), 'MEMBERSHIP_ID' => $aMembers['iMemberThree'], 'TIMESLOT_ID' => $aTimeSlots['iFiveMinuteTimeslot'] ],
    'iMemberFourSchedule'   => ['CALENDAR_YEAR' => $oNow->format('Y'), 'MEMBERSHIP_ID' => $aMembers['iMemberFour'],  'TIMESLOT_ID' => $aTimeSlots['iFiveMinuteTimeslot'] ],
       
    ];
   
    $oScheduleFixture  = new Fixture\NewScheduleFixture($oDatabase, $oNow, $aTableNames);
    $aNewSchedules     = $oScheduleFixture->runFixture($aScheduleConfig); 
   
   
    $aConfig['schedule_member_one']   = $aNewSchedules['iMemberOneSchedule'];
    $aConfig['schedule_member_two']   = $aNewSchedules['iMemberTwoSchedule'];
    $aConfig['schedule_member_three'] = $aNewSchedules['iMemberThreeSchedule'];
    $aConfig['schedule_member_four']  = $aNewSchedules['iMemberFourSchedule'];
   
   
   
     
   return $aConfig;       
    
}


return runFixture();
