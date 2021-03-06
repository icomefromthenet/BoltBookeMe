<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base;

use DateInterval;
use DateTime;
use Bolt\Application;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;


use Bolt\Extension\IComeFromTheNet\BookMe\Provider;


use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\CalAddYearCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotToggleStatusCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotAddCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterTeamCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\StartScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\StopScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\ResumeScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\ToggleScheduleCarryCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;


use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\CreateRuleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\AssignRuleToScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Command\RemoveRuleFromScheduleCommand;


use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\TakeBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\WebBookingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command\ClearBookingCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Customer\Command\CreateCustomerCommand;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\AssignApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CancelApptCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\MoveApptWaitingCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CompleteApptCommand;


/**
 * Core Library Service.
 * 
 * Before this library can be used you must setup the schema and inserted any
 * basic data e.g the INTS DB Table needs seed vales.
 * 
 * Before you can take your first booking but after built the schema and seed you must
 * 
 * 1. Add 1 to many Calendar Years (recommend 10 at most).
 * 2. Add 1 timeslot e.g 5 minutes.
 * 3. Register 1 member.
 * 4. Create a schedule for that member.
 * 5. Create at least 1 Avability Rule.
 * 
 * 
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class BookMeService
{

    protected $oContainer;


    protected $aConfig;


    /**
     * Class Constructor
     * 
     * @param   BookMeContainer     $oContainer     The Service Container
     */ 
    public function __construct(Application $oContainer, $aConfig)
    {
       $this->oContainer = $oContainer;
       $this->aConfig    = $aConfig;
        
    }



    //----------------------------------------
    // Calendar, Timeslots 
    // 
    //
    //----------------------------------------

    /**
     * Add a new calendar years to the calender tables.
     * 
     * @param integer $iYearsToAdd  The number of years to add to calender.
     * @throws BookMeException
     * @return Boolean 
     * @access public
     */ 
    public function addCalenderYears($iYearsToAdd, DateTime $oStartYear = null)
    {
        $oCommand = new CalAddYearCommand($iYearsToAdd, $oStartYear);
        
        return $this->getCommandBus()->handle($oCommand);
    }

    
    
    /**
     * Add a new timeslot to the database, if a duplicate exists an exception is thrown
     * 
     * @param integer $iTimeSlotLengthMinutes   The slot length in minutes
     * @param integer $iCalYear                 The Calendar year to add this slot onto
     * @return integer                          The slot new database id
     * @access public
     * @throws BookMeException if duplicate exists or command failes for unknown reasons
     */ 
    public function addTimeslot($iTimeSlotLengthMinutes, $iCalYear)
    {
        $oCommand = new SlotAddCommand($iTimeSlotLengthMinutes,$iCalYear);
        
        $this->getCommandBus()->handle($oCommand);
        
        return $oCommand->getTimeSlotId();
    }
    
    
    
    
    /**
     * Toggle between a timeslot between active and inactive
     * 
     * @return boolean true if command successful
     * @throws BookMeException if their are no updates
     * 
     */ 
    public function toggleSlotAvability($iTimeslotDatabaseId)
    {
        $oCommand = new SlotToggleStatusCommand($iTimeslotDatabaseId);
        
        return $this->getCommandBus()->handle($oCommand);
    }
    
    
    


    //----------------------------------------
    // Membership, Schedules and Teams
    //
    //----------------------------------------
    
    /**
     * Register an existing entity for scheduling. 
     * 
     * This does not store a reference to that  existing entity this will be the responsibility
     * of library user.
     * 
     * A membership does not expire and can not be disabled. To stop scheduling for the
     * entity all schedules should be retired by adding an exclusion rule for the remainder of the
     * schedule calendar year.
     * 
     * @return integer the membership database id
     * @access public
     * @param string the members full name
     * @throws Bolt\Extension\IComeFromTheNet\BookMe\Bus\Exception\MembershipException if operation fails
     */
    public function registerMembership($sMemberName)
    {
        $oCommand = new RegisterMemberCommand($sMemberName);
        
        try {
        
        $this->getCommandBus()->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        }
       
        return $oCommand->getMemberId();
        
    }
    
    /**
     * Register a new team.
     * 
     * Each Schedule Assigned to a team must have the same timeslot
     * 
     * @access public
     * @return integer  The new team database id
     * @throws Bolt\Extension\IComeFromTheNet\BookMe\Bus\Exception\MembershipException if operation fails
     * @param string    $sTeamName   Team name
     * 
     */ 
    public function registerTeam($sTeamName)
    {
        $oCommand = new RegisterTeamCommand($sTeamName);   
        
         try {
        
            $this->getCommandBus()->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        }
       
        return $oCommand->getTeamId();
     
        
    }
    
    
    /**
     * Start a new schedule for a member. 
     * 
     * @param integer   $iMemberDatabaseId      The member to use
     * @param integer   $iTimeSlotDatabbaseId   The timslot which split a calendar day
     * @param integer   $iCalendarYear          The Calendar year to use
     * 
     */
    public function startSchedule($iMemberDatabaseId, $iTimeSlotDatabbaseId, $iCalendarYear)
    {
        $oCommand = new StartScheduleCommand($iMemberDatabaseId, $iTimeSlotDatabbaseId, $iCalendarYear);  
        
        try {
        
            $this->getCommandBus()->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        }
       
        return $oCommand->getScheduleId();
     
        
    }
    
    
    /**
     * Stop a schedule from taking new bookings and prevent from being rollover
     * 
     * @param integer   $iScheduleDatabaseId    The Schedule to close
     * @param DateTime  $oStopDate              The date during the calendar year to stop from
     */ 
    public function stopSchedule($iScheduleDatabaseId, DateTime $oStopDate)
    {
        $oCommand = new StopScheduleCommand($iScheduleDatabaseId, $oStopDate);  
        
        try {
        
            $this->getCommandBus()->handle($oCommand);
       
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        }
       
        return true;
   
    }
    
    /**
     * Opens a closed schedule to take new books and rollover.
     * 
     * @param integer   $iScheduleDatabaseId    The schedule to open.
     */ 
    public function resumeSchedule($iScheduleDatabaseId)
    {
        $oCommand = new ResumeScheduleCommand($iScheduleDatabaseId);  
        
        try {
        
            $this->getCommandBus()->handle($oCommand);
        
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        }
       
        return true;
        
    }
    
    
    /**
     * Assigns a member to a team
     * 
     * Note:
     *  1. A Member can only have 1 schedule per calendar year and belong to one timeslot per year
     *  2. Team members must share the same timeslot.
     *  3. While a member can belong to many teams each team must share the same timeslot.
     * 
     * @param   integer     $iMemberDatabaseId      The member to assign
     * @param   integer     $iTeamDatabaseId        The team to use
     * @param   integer     $iScheduleId            The Schedule to use
     *
     */ 
    public function assignTeamMember($iMemberDatabaseId, $iTeamDatabaseId, $iScheduleId)
    {
        
        $oCommand = new AssignTeamMemberCommand($iMemberDatabaseId, $iTeamDatabaseId, $iScheduleId);
        
        try {
        
         $this->getCommandBus()->handle($oCommand);
         
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
         
        return true;
    }
    
    
    /**
     * Remove a member from a team
     * 
     * @param   integer     $iMemberDatabaseId     The member to assign
     * @param   integer     $iTeamDatabaseId       The Team to remove from
     * @param   integer     $iScheduleId           The Schedule to use
     */ 
    public function withdrawlTeamMember($iMemberDatabaseId, $iTeamDatabaseId, $iScheduleId)
    {
        
        $oCommand = new WithdrawlTeamMemberCommand($iMemberDatabaseId, $iTeamDatabaseId, $iScheduleId);
        
        try {
        
            $this->getCommandBus()->handle($oCommand);
        
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
    }
    
    
    //----------------------------------------
    // Rules
    //
    //----------------------------------------
   
    /**
     * Create a rule that marks slots as open and ready for work, this rule apply to a single calendar day
     * 
     * @param DateTime  $oDate               The Calendar date to apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRuleName           A Name for the rule
     * @param string    $sRuleDesc           A Description for the rule
     */ 
    public function createSingleWorkDayRule(DateTime $oDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRuleName, $sRuleDesc = null)
    {
        $oStartDate = clone $oDate;
        $oEndDate  = clone $oDate;
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 1, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, '*', '*', '*', '*', true, $sRuleName, $sRuleDesc);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }
   
    /**
     * Create a rule that marks slots as open and ready for work, this rule apply to many calendar days
     * 
     * @param DateTime  $oStartFromDate      The Calendar date to start apply this rule to.
     * @param DateTime  $oEndtAtDate         The Calendar date to stop apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRepeatDayofweek   The day of week cron def
     * @param string    $sRepeatDayofmonth  The day of month cron def
     * @param string    $sRepeatMonth       The month cron def
     * @param string    $sRepeatWeekofyear  the week of year cron def
     * @param string    $sRuleName          A name for the rule
     * @param string    $sRuleDesc          A Description for the rule
     */ 
    public function createRepeatingWorkDayRule(DateTime $oStartFromDate, DateTime $oEndtAtDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek, $sRepeatDayofmonth, $sRepeatMonth, $sRepeatWeekofyear, $sRuleName , $sRuleDesc = null)
    {
        $oCommand = new CreateRuleCommand($oStartFromDate, $oEndtAtDate, 1, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek,$sRepeatDayofmonth,$sRepeatMonth,$sRepeatWeekofyear,false, $sRuleName, $sRuleDesc);
        
        try {
             $this->getCommandBus()->handle($oCommand);
        
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
       
        
        return $oCommand->getRuleId();
        
    }
    
    /**
     * Create a rule that marks slots as closed/busy, this rule apply to a single calendar day
     * 
     * @param DateTime  $oDate               The Calendar date to apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRuleName           A Name for the rule
     * @param string    $sRuleDesc          A Description for the rule
     */ 
    public function createSingleBreakRule(DateTime $oDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRuleName, $sRuleDesc = null) 
    {
        $oStartDate = clone $oDate;
        $oEndDate  = clone $oDate;
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 2, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, '*', '*', '*', '*', true, $sRuleName, $sRuleDesc);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }     
    
    /**
     * Create a rule that marks slots as closed, this rule apply to many calendar days
     * 
     * @param DateTime  $oStartFromDate      The Calendar date to start apply this rule to.
     * @param DateTime  $oEndtAtDate         The Calendar date to stop apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRepeatDayofweek   The day of week cron def
     * @param string    $sRepeatDayofmonth  The day of month cron def
     * @param string    $sRepeatMonth       The month cron def
     * @param string    $sRepeatWeekofyear  The week of year cron def
     * @param string    $sRuleName          A name for the rule
     * @param string    $sRuleDesc          A Description for the rule
     */ 
    public function createRepeatingBreakRule(DateTime $oStartFromDate, DateTime $oEndtAtDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek, $sRepeatDayofmonth, $sRepeatMonth, $sRepeatWeekofyear, $sRuleName, $sRuleDesc = null)
    {
        
        $oCommand = new CreateRuleCommand($oStartFromDate, $oEndtAtDate, 2, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek,$sRepeatDayofmonth,$sRepeatMonth, $sRepeatWeekofyear, false, $sRuleName, $sRuleDesc);
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }
   
    /**
     * Create a rule that marks slots as closed, this rule apply to a single calendar day
     * 
     * @param DateTime  $oDate               The Calendar date to apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRuleName           A name for the rule
     * @param string    $sRuleDesc          A Description for the rule
     */     
    public function createSingleHolidayRule(DateTime $oDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRuleName, $sRuleDesc = null)
    {
        $oStartDate = clone $oDate;
        $oEndDate  = clone $oDate;
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 3, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, '*', '*', '*', '*', true, $sRuleName, $sRuleDesc);
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
    }
    
    /**
     * Create a rule that marks slots as closed for work, this rule apply to many calendar days
     * 
     * @param DateTime  $oStartFromDate      The Calendar date to start apply this rule to.
     * @param DateTime  $oEndtAtDate         The Calendar date to stop apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRepeatDayofweek   The day of week cron def
     * @param string    $sRepeatDayofmonth  The day of month cron def
     * @param string    $sRepeatMonth       The month cron def
     * @param string    $sRepeatWeekofyear  The week of year cron def
     * @param string    $sRuleName          A name for the rule
     * @param string    $sRuleDesc           A Description for the rule
     */ 
    public function createRepeatingHolidayRule(DateTime $oStartFromDate, DateTime $oEndtAtDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek, $sRepeatDayofmonth, $sRepeatMonth,$sRepeatWeekofyear, $sRuleName, $sRuleDesc = null)
    {
        
        
        $oCommand = new CreateRuleCommand($oStartFromDate, $oEndtAtDate, 3, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek,$sRepeatDayofmonth,$sRepeatMonth,$sRepeatWeekofyear,false, $sRuleName, $sRuleDesc);
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }
    
    /**
     * Create a rule that marks slots as open and ready for work event if marked as a break/holiday, this rule apply to a single calendar day
     * 
     * @param DateTime  $oDate               The Calendar date to apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRuleName           A name for the rule
     * @param string    $sRuleDesc           A Description for the rule
     */ 
    public function createSingleOvertmeRule(DateTime $oDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRuleName, $sRuleDesc = null)
    {
        $oStartDate = clone $oDate;
        $oEndDate  = clone $oDate;
        
        $oCommand = new CreateRuleCommand($oStartDate, $oEndDate, 4, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, '*', '*', '*', '*', true, $sRuleName, $sRuleDesc);
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }
    
    /**
     * Create a rule that marks slots as open and ready for work even if marked for break/holiday, this rule apply to many calendar days
     * 
     * @param DateTime  $oStartFromDate      The Calendar date to start apply this rule to.
     * @param DateTime  $oEndtAtDate         The Calendar date to stop apply this rule to.
     * @param integer   $iTimeslotDatabaseId The database id of the timeslot
     * @param integer   $iOpeningSlot        The slot number during the day to start 
     * @param integer   $iClosingSlot        The closing slot number to stop after
     * @param string    $sRepeatDayofweek   The day of week cron def
     * @param string    $sRepeatDayofmonth  The day of month cron def
     * @param string    $sRepeatMonth       The month cron def
     * @param string    $sRepeatWeekofyear  The week of year cron def
     * @param string    $sRuleName          A name for the rule
     * @param string    $sRuleDesc           A Description for the rule
     */ 
    public function createRepeatingOvertimeRule(DateTime $oStartFromDate, DateTime $oEndtAtDate, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek, $sRepeatDayofmonth, $sRepeatMonth, $sRepeatWeekofyear, $sRuleName, $sRuleDesc = null)
    {
        
        $oCommand = new CreateRuleCommand($oStartFromDate, $oEndtAtDate, 4, $iTimeslotDatabaseId, $iOpeningSlot, $iClosingSlot, $sRepeatDayofweek,$sRepeatDayofmonth,$sRepeatMonth, $sRepeatWeekofyear, false, $sRuleName, $sRuleDesc);
        
        try {
        
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getRuleId();
        
    }
    
    
    /**
     * Create a relation between a rule and a schedule, on the next schedule refresh this rule
     * will be applied
     * 
     * @param   Integer     $iRuleDatabaseId        The rule to link to a schedule
     * @param   Integer     $iScheduleDatabaseId    The schedule to link the rule to
     * @param   Boolean     $bRolloverRule          If This rule should be rolled over come new schedule year
     * 
     */ 
    public function assignRuleToSchedule($iRuleDatabaseId, $iScheduleDatabaseId, $bRolloverRule = false)
    {
        $oCommand = new AssignRuleToScheduleCommand($iScheduleDatabaseId,$iRuleDatabaseId,$bRolloverRule);    
        
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
            
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
    }
    
    /**
     * This will delete a realtion bewtween a rule and a schedule, on the next schedule refresh it
     * will be removed.
     * 
     * @param   Integer     $iRuleDatabaseId        The rule to unlink from a schedule
     * @param   Integer     $iScheduleDatabaseId    The schedule to unlink the rule from
     * 
     */ 
    public function removeRuleFromSchedule($iRuleDatabaseId, $iScheduleDatabaseId)
    {
        $oCommand = new RemoveRuleFromScheduleCommand($iScheduleDatabaseId, $iRuleDatabaseId);
        
        
        try {
            $this->getCommandBus()->handle($oCommand);
        
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
    }
    
    
    /**
     * Apply rules to a schedule and remove any those not related.
     * 
     * This should be called after all relations are done
     * 
     * @param   Integer     $iScheduleDatabaseId    The schedule to refresh 
     */ 
    public function resfreshSchedule($iScheduleDatabaseId)
    {
        $oCommand = new RefreshScheduleCommand($iScheduleDatabaseId, false);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
    }
    
    
    //----------------------------------------
    // Booking
    //
    //----------------------------------------
   
     /**
     * This will create a booking reserving the chosen slots on the schedule
     * 
     * If slots can not be reserved then an exception will be thrown
     * 
     * @param integer       $iScheduleId        The database id of the schedule to use      
     * @param DateTime      $oOpeningSlot       The opening of the first Slot
     * @param DateTime      $oClosingSlot       The closing date the last slot. 
     * 
     * @return integer the booking database id
     */  
    public function takeManualBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
    {
        return $this->takeBooking($iScheduleId, $oOpeningSlot, $oClosingSlot);
    }
   
   /**
     * This will create a booking reserving the chosen slots on the schedule
     * 
     * If slots can not be reserved then an exception will be thrown
     * 
     * @param integer       $iScheduleId        The database id of the schedule to use      
     * @param DateTime      $oOpeningSlot       The opening of the first Slot
     * @param DateTime      $oClosingSlot       The closing date the last slot. 
     * @param integer       $iMaxBookings       The max bookings allowed on the day
     * @param DateInterval  $oInterval          The lead time.      
     * 
     * @return integer the booking database id
     */  
    public function takeWebBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot, $iMaxBookings = 1, DateInterval $oInterval)
    {
        $oNow = $this->oContainer['bm.now'];
        
        $oCommand = new WebBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot, $oNow, $iMaxBookings, $oInterval);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getBookingId();
        
    }
   
    /**
     * This will create a booking reserving the chosen slots on the schedule
     * 
     * If slots can not be reserved then an exception will be thrown
     * 
     * @param integer       $iScheduleId        The database id of the schedule to use      
     * @param DateTime      $oOpeningSlot       The opening of the first Slot
     * @param DateTime      $oClosingSlot       The closing date the last slot. 
     * 
     * @return integer the booking database id
     */ 
    public function takeBooking($iScheduleId, DateTime $oOpeningSlot, DateTime $oClosingSlot)
    {
        $oCommand = new TakeBookingCommand($iScheduleId, $oOpeningSlot, $oClosingSlot);
        
        try {
            
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getBookingId();
    }
    
    /**
     * This will remove a booking which free the assigned slots
     * 
     * 1. Remove the booking record
     * 2. Clear booking from schedule
     * 3. Remove any conflict list
     * 
     * @param integer $iBookingId   The Booking Database id
     * 
     */ 
    public function cancelBooking($iBookingId)
    {
        $oCommand = new ClearBookingCommand($iBookingId);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
    }
    
    //----------------------------------------
    // Customer
    //
    //----------------------------------------
    
    /**
     * This will create a new customer record
     * 
     * @return integer the customers database id
     * @param string $sFirstName
     * @param string $sLastName
     * @param string $sEmail
     * @param string $sMobile
     * @param string $sLandline
     * @param string $sAddressLineOne
     * @param string $sAddressLineTwo
     * @param string $sCompanyName
     
     */ 
    public function createCustomer($sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName)
    {
        $oCommand = new CreateCustomerCommand($sFirstName, $sLastName, $sEmail, $sMobile, $sLandline, $sAddressLineOne, $sAddressLineTwo, $sCompanyName);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getCustomerId();
    }
   
   
    //----------------------------------------
    // Appointment
    //
    //----------------------------------------
    
    /**
     * Create a new appointment.
     * 
     * @param integer $iCustomerId
     * @parmm string  $sInstruction
     * @return integer the appointment new database id
     */ 
    public function createAppointment($iCustomerId,$sInstruction)
    {
        $oCommand = new CreateApptCommand($iCustomerId,$sInstruction);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return $oCommand->getAppointmentId();
        
    }
    
    
    
    public function addAppointmentToWaitingList($iAppointmentId)
    {
        $oCommand = new MoveApptWaitingCommand($iAppointmentId);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
        
    }
    
    
    public function completeAppointment($iAppointmentId)
    {
        $oCommand = new CompleteApptCommand($iAppointmentId);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
        
    }
    
    
    public function assignAppointment($iAppointmentId, $iBookingId, $sInstruction)
    {
         $oCommand = new AssignApptCommand($iAppointmentId, $iBookingId, $sInstruction);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
        
    }
    
    public function cancelAppointment($iAppointmentId)
    {
          $oCommand = new CancelApptCommand($iAppointmentId);
        
        try {
            $this->getCommandBus()->handle($oCommand);
        } catch (ValidationException $e) {
           
            return $e->getValidationFailures();
        } 
        
        return true;
        
    }
    
    
    //--------------------------------------------------------------------------
    # Accessors
    
    /**
     * Fetch this services DI container
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\BookMeContainer
     */ 
    public function getContainer()
    {
        return $this->oContainer;
    }


    public function getCommandBus()
    {
        return $this->getContainer()->offsetGet('bm.commandBus');
    }

}
/* End of File */