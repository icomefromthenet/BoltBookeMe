<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\DBAL\Types\Type;

/**
 * PHPUnit listener class
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class BoltListener implements \PHPUnit_Framework_TestListener
{
    /** @var array */
    protected $configs = [
        'config'       => 'app/config/config.yml.dist',
        'contenttypes' => 'app/config/contenttypes.yml.dist',
        'menu'         => 'app/config/menu.yml.dist',
        'permissions'  => 'app/config/permissions.yml.dist',
        'routing'      => 'app/config/routing.yml.dist',
        'taxonomy'     => 'app/config/taxonomy.yml.dist',
    ];
    /** @var string */
    protected $theme;
    /** @var boolean */
    protected $timer;
    /** @var array */
    protected $tracker = [];
    /** @var string */
    protected $currentSuite;
    /** @var boolean */
    protected $reset;

    /**
     * Called on init of PHPUnit exectution.
     *
     * @see PHPUnit_Util_Configuration
     *
     * @param array   $configs Location of configuration files
     * @param string  $theme   Location of the theme
     * @param boolean $reset   Reset test environment after run
     * @param boolean $timer   Create test execution timer output
     */
    public function __construct($configs = [], $theme = false, $reset = true, $timer = true)
    {
        $this->configs = $this->getConfigs($configs);
        $this->theme = $this->getTheme($theme);
        $this->reset = $reset;
        $this->timer = $timer;

        $this->buildTestEnv();
    }

    /**
     * Get a valid array of configuration files.
     *
     * @param array $configs
     *
     * @return array
     */
    protected function getConfigs(array $configs)
    {
        foreach ($configs as $name => $file) {
            if (empty($file)) {
                $configs[$name] = $this->getPath($name, $this->config[$name]);
            } else {
                $configs[$name] = $this->getPath($name, $file);
            }
        }

        return $configs;
    }

    /**
     * Get the path to the theme to be used in the unit test.
     *
     * @param string $theme
     *
     * @return string
     */
    protected function getTheme($theme)
    {
        if ($theme === false || (isset($theme['theme']) && $theme['theme'] === '')) {
            return $this->getPath('theme', 'theme/base-2016');
        } else {
            return $this->getPath('theme', $theme['theme']);
        }
    }

    

    /**
     * Resolve a file path.
     *
     * @param string $name
     * @param string $file
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getPath($name, $file)
    {
        if (file_exists($file)) {
            return $file;
        }

        
        if (file_exists(TEST_ROOT . '/' . $file)) {
            return TEST_ROOT . '/' . $file;
        }

        if (file_exists(TEST_ROOT . '/vendor/bolt/bolt/' . $file)) {
            return TEST_ROOT . '/vendor/bolt/bolt/' . $file;
        }
        
        if (file_exists(TEST_ROOT . '/../' . $file)) {
            return TEST_ROOT . '/../' . $file;
        }


        throw new \InvalidArgumentException("The file parameter '$name:' '$file' in the PHPUnit XML file is invalid.");
    }

    /**
     * Destructor that will be called at the completion of the PHPUnit execution.
     *
     * Add code here to clean up our test environment.
     */
    public function __destruct()
    {
        $this->cleanTestEnv();
    }

    /**
     * An error occurred.
     *
     * @see PHPUnit_Framework_TestListener::addError()
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * A failure occurred.
     *
     * @see PHPUnit_Framework_TestListener::addFailure()
     *
     * @param \PHPUnit_Framework_Test                 $test
     * @param \PHPUnit_Framework_AssertionFailedError $e
     * @param float                                   $time
     */
    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * A test was incomplete.
     *
     * @see PHPUnit_Framework_TestListener::addIncompleteTest()
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * A test  is deemed risky.
     *
     * @see PHPUnit_Framework_TestListener::addRiskyTest()
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * Test has been skipped.
     *
     * @see PHPUnit_Framework_TestListener::addSkippedTest()
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * A test started.
     *
     * @see PHPUnit_Framework_TestListener::startTest()
     *
     * @param \PHPUnit_Framework_Test $test
     */
    public function startTest(\PHPUnit_Framework_Test $test)
    {
    }

    /**
     * A test ended.
     *
     * @see PHPUnit_Framework_TestListener::endTest()
     *
     * @param \PHPUnit_Framework_Test $test
     * @param float                   $time
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $name = $test->getName();
        $this->tracker[$this->currentSuite . '::' . $name] = $time;
    }

    /**
     * A test suite started.
     *
     * @see PHPUnit_Framework_TestListener::startTestSuite()
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->currentSuite = $suite->getName();
    }

    /**
     * A test suite ended.
     *
     * @see PHPUnit_Framework_TestListener::endTestSuite()
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        unset($this->currentSuite);
    }

    /**
     * Build the pre-requisites for our test environment
     */
    private function buildTestEnv()
    {
        $fs = new Filesystem();
        
        
        if ($fs->exists(PHPUNIT_WEBROOT)) {
            $fs->remove(PHPUNIT_WEBROOT);
        } else {
            
            $fs->mkdir(PHPUNIT_WEBROOT, 0777);
        }
        
     
        // Create needed directories
        @$fs->mkdir(PHPUNIT_ROOT . '/resources/files/', 0777);
        @$fs->mkdir(PHPUNIT_ROOT . '/resources/translations/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/app/cache/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/app/config/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/app/database/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/extensions/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/extensions/local', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/files/', 0777);
        @$fs->mkdir(PHPUNIT_WEBROOT . '/theme/', 0777);

        
        // Mirror in required assets.
        $fs->mirror(TEST_ROOT . '/app/resources/',      PHPUNIT_WEBROOT . '/app/resources/',      null, ['override' => true]);
        $fs->mirror(TEST_ROOT . '/app/theme_defaults/', PHPUNIT_WEBROOT . '/app/theme_defaults/', null, ['override' => true]);
        $fs->mirror(TEST_ROOT . '/app/view/',           PHPUNIT_WEBROOT . '/app/view/',           null, ['override' => true]);
        
        
        // System link extension to local
        $fs->symlink(BOOKME_EXTENSION_PATH,PHPUNIT_WEBROOT.'/extensions/local/icomefromthenet/bookme',false);
        
        
        // Build a clean bolt database
        $oDatabase   = $this->getBoltDatabaseConnection();
        $oNow        = $this->getTestDate($oDatabase);
        $aTableNames = $this->getDatabaseTableList();
        
        $this->buildTestDatabase($oDatabase, $aTableNames, $oNow);
        
        
        // Copy in config files
        foreach ($this->configs as $config) {
            $fs->copy($config, PHPUNIT_WEBROOT . '/app/config/' . basename($config), true);
        }
       

        // Copy in the theme
        $name = basename($this->theme);
        $fs->mirror($this->theme, PHPUNIT_WEBROOT . '/theme/' . $name);


        // done run as create folders and worng dir, we set paths when make the app
        // Set the theme name in config.yml
        //system('php ' . NUT_PATH . ' config:set theme ' . $name);

        // Empty the cache
        //system('php ' . NUT_PATH . ' cache:clear');
        
        
    }

    /**
     * Clean up after test runs
     */
    private function cleanTestEnv()
    {
        // Empty the cache
        // bolt not have correct paths set so clear cache usless
        //system('php ' . NUT_PATH . ' cache:clear');
   
        // Remove the test database
        if ($this->reset) {
            $fs = new Filesystem();

            $fs->remove(PHPUNIT_ROOT . '/resources/files/');
            $fs->remove(PHPUNIT_WEBROOT);
        }

        // Write out a report about each test's execution time
        if ($this->timer) {
            $file = TEST_ROOT . '/app/cache/phpunit-test-timer.txt';
            if (is_readable($file)) {
                unlink($file);
            }

            arsort($this->tracker);
            foreach ($this->tracker as $test => $time) {
                $time = substr($time, 0, 6);
                file_put_contents($file, "$time\t\t$test\n", FILE_APPEND);
            }
        }

        echo "\n\033[32mTest timings written out to: " . TEST_ROOT . "/app/cache/phpunit-test-timer.txt\033[0m\n\n";
    }
    
    protected function getBoltDatabaseConnection()
    {
        $connectionParams = array(
            'dbname'    => $GLOBALS['DEMO_DATABASE_SCHEMA'],
            'user'      => getenv('C9_USER') == false ? $GLOBALS['DEMO_DATABASE_USER'] :getenv('C9_USER'),
            'password'  => $GLOBALS['DEMO_DATABASE_PASSWORD'],
            'host'      => getenv('IP') == false ? $GLOBALS['DEMO_DATABASE_HOST'] : getenv('IP'),
            'driver'    => $GLOBALS['DEMO_DATABASE_TYPE'],
            'port'      => $GLOBALS['DEMO_DATABASE_PORT'],
         );
         
        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
    }
    
    

    protected function getDatabaseTableList()
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


    protected function getTestDate($oDatabase)
    {
         
        
        $oDBPlatform  = $oDatabase->getDatabasePlatform();
        $oDateType    = Type::getType(Type::DATE); 
        $sNow         = $oDatabase->fetchColumn("select date_format(NOW(),'%Y-%m-%d')  ",[],0,[]);
            
        return $oDateType->convertToPHPValue($sNow,$oDBPlatform);
        
    }


    
    protected function buildTestDatabase($oDatabase, $aTableNames, DateTime $oNow)
    {
       
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
       $aConfig['six_minute'] = $aTimeSlots['iSixMinuteTimeslot'];
       
       // Register new Members
       
       $oMembersFixture = new Fixture\MemberFixture($oDatabase, $oNow, $aTableNames);
       $aMembers        = $oMembersFixture->runFixture([]);
       
       $aConfig['member_one']   = $aMembers['iMemberOne'];
       $aConfig['member_two']   = $aMembers['iMemberTwo'];
       $aConfig['member_three']   = $aMembers['iMemberThree'];
       $aConfig['member_four']   = $aMembers['iMemberFour'];
       $aConfig['member_five']   = $aMembers['iMemberFive']; // no schedule created yet
       
       
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
       
       
       // Creat Some Rules
       
       $oNewRuleFixture = new Fixture\NewRuleFixture($oDatabase, $oNow, $aTableNames);
       
       $aRuntimeData = [
            'iRepeatWorkDayRule' => [
                'RULE_TYPE_ID' => 1,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            
            ],
            
            'iSingleWorkDayRule' => [
                'RULE_TYPE_ID' => 1,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iRepeatBreakRule' => [
                'RULE_TYPE_ID' => 2,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iSingleBreakRule' =>[
                'RULE_TYPE_ID' => 2,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iRepeatHolidayRule' => [
                'RULE_TYPE_ID' => 3,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iSingleHolidayRule' => [
                'RULE_TYPE_ID' => 3,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iRepeatOvertimeRule' => [
                'RULE_TYPE_ID' => 4,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
            
            'iSingleOvertimeRule' => [
                'RULE_TYPE_ID' => 4,
                'TIMESLOT_ID'  => $aConfig['five_minute'],
            ],
           
        ];
       
        $aNewRules                   = $oNewRuleFixture->runFixture($aRuntimeData, $oNow);
        
        $aConfig['work_repeat']      = $aNewRules['iRepeatWorkDayRule'];
        $aConfig['work_single']      = $aNewRules['iSingleWorkDayRule'];
        $aConfig['break_repeat']     = $aNewRules['iRepeatBreakRule'];
        $aConfig['break_single']     = $aNewRules['iSingleBreakRule'];
        $aConfig['holiday_repeat']   = $aNewRules['iRepeatHolidayRule'];
        $aConfig['holiday_single']   = $aNewRules['iSingleHolidayRule'];
        $aConfig['overtime_repeat']  = $aNewRules['iRepeatOvertimeRule'];
        $aConfig['overtime_single']  = $aNewRules['iSingleOvertimeRule'];


        // Assign new Rules to Known Schedules, include a schedule refresh which applies the rules to a schedule
        $oAssignRuleFixture = new Fixture\AssignRuleFixture($oDatabase, $oNow, $aTableNames);
        $oAssignRuleFixture->runFixture([], $oNow);
        
        
        
        // Create Some New Customers
        $oCustomerFixture = new Fixture\CustomerFixture($oDatabase, $oNow, $aTableNames);
        $aNewCustomers = $oCustomerFixture->runFixture([],$oNow);
        
        $aConfig['appt_customer_one_1']  = $aNewCustomers['iCustomerOneId'];
        $aConfig['appt_customer_one_2']  = $aNewCustomers['iCustomerTwoId'];
        $aConfig['appt_customer_two_1']  = $aNewCustomers['iCustomerThreeId'];
        
        $aConfig['customer_1']  = $aNewCustomers['iCustomerOneId'];
        $aConfig['customer_2']  = $aNewCustomers['iCustomerTwoId'];
        $aConfig['customer_3']  = $aNewCustomers['iCustomerThreeId'];
        
  
        $GLOBALS['BM_TEST_DATABASE_ID'] = $aConfig;       

        
    }
    
        
    
}
/* End Class */
