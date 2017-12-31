<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Datatable;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQueryHandler;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field\CalendarYearField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field\ActiveTimeslotField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Field\RuleTypeField;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Field\ScheduleTeamField;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity;



class FilterQueryTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
   
    
   protected function handleEventPostFixtureRun()
   {
      $oNow         = $this->getNow();
      $oService     = $this->getTestAPI();
      
      return;
   }  
   
   
    /**
    * @group Form
    */ 
    public function testCustomFieldsAndFilterQueries()
    {
       
        // Calendar Year Entity
        
        $oCalYear               = new CalendarYearEntity();
        $oCalYear->y            = $this->getNow()->format('Y');
        
        $oCalYear->y_start      = clone $this->getNow();
        $oCalYear->y_start->setDate(1,1,$oCalYear->y);
        
        $oCalYear->y_end        = clone $this->getNow();
        $oCalYear->y_end->setDate(31,12,$oCalYear->y);
        
        $oCalYear->current_year = true;
        
        // Timeslot Entity
        
        $oFiveMinuteTimeslot = new TimeslotEntity();
        $oFiveMinuteTimeslot->timeslot_id     = $this->aDatabaseId['five_minute'];
        $oFiveMinuteTimeslot->timeslot_length = 5;
        $oFiveMinuteTimeslot->is_active_slot  = true;
        
        // Ruletype Entity
        
        $oRuleType  = new RuleTypeEntity();
        $oRuleType->rule_type_id     = 1;
        $oRuleType->rule_code        = 'workday';
        $oRuleType->is_work_day      = true;
        $oRuleType->is_exclusion     = false;
        $oRuleType->is_inc_override  = false;
       
        // Team Entity
        $oTeamEntity = new TeamEntity();
        $oTeamEntity->team_id         = $this->aDatabaseId['team_one'];
        $oTeamEntity->registered_date = $this->getNow();
        $oTeamEntity->team_name       = 'Bob Team';
       
       
        $this->TestCalendarYearField($oCalYear);
        $this->TestActiveTimeslotField($oFiveMinuteTimeslot);
        $this->TestRuleTypeField($oRuleType);
        $this->TestScheduleTeamField($oTeamEntity);
       
        $this->TestRuleQuery($oFiveMinuteTimeslot, $oCalYear, $oRuleType);
        $this->TestMemberQuery($oCalYear, $oTeamEntity);
       
    }
    
    
    
    protected function TestCalendarYearField(CalendarYearEntity $oCalYear)
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        $form = $oFactory
            ->createBuilder('form',[])
            ->add('cal_year',CalendarYearField::class)
            ->getForm();
        
        
        $formData = array(
          'cal_year' => $oCalYear->getCalendarYear()
        );
        
        $form->submit($formData);
        
        $this->assertTrue($form->isSynchronized());
        
        $formData = $form->getData();
        
        $this->assertEquals($formData['cal_year']->getCalendarYear(), $oCalYear->getCalendarYear());
        
    }
    
    protected function TestActiveTimeslotField(TimeslotEntity $oTimeslot)
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        $form = $oFactory
            ->createBuilder('form',[])
            ->add('timeslot_id',ActiveTimeslotField::class)
            ->getForm();
        
        
        $formData = array(
          'timeslot_id' => $oTimeslot->getTimeslotId()
        );
        
        $form->submit($formData);
        
        $this->assertTrue($form->isSynchronized());
        
        $formData = $form->getData();
        
        $this->assertEquals($formData['timeslot_id']->getTimeslotId(), $oTimeslot->getTimeslotId());
        
    }
  
    protected function TestRuleTypeField(RuleTypeEntity $oRuleType)
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory
            ->createBuilder('form',[])
            ->add('rule_type_id',RuleTypeField::class)
            ->getForm();
        
        
        $formData = array(
          'rule_type_id' => $oRuleType->getRuleTypeId()
        );
        
        $form->submit($formData);
        
        $this->assertTrue($form->isSynchronized());
        
        $formData = $form->getData();
        
        $this->assertEquals($formData['rule_type_id']->getRuleTypeId(),$oRuleType->getRuleTypeId());
        
    }
    
    protected function TestScheduleTeamField(TeamEntity $oTeamEntity)
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory
            ->createBuilder('form',[])
            ->add('team_id',ScheduleTeamField::class)
            ->getForm();
        

        $formData = array(
          'team_id' => $oTeamEntity->team_id
        );
        
        $form->submit($formData);
        
        $this->assertTrue($form->isSynchronized());
        
        $formData = $form->getData();
        
        $this->assertEquals($formData['team_id']->getTeamId(),$oTeamEntity->getTeamId());
        

    }
    
    
    protected function TestRuleQuery(TimeslotEntity $oTimeslot, CalendarYearEntity $oCalYear, RuleTypeEntity $oRuleType)
    {
     
        $oContainer    = $this->getContainer();
        
        # Fetch Search Query From Container
        
        $oSearchHandler = new SelectQueryHandler($this->getContainer());
        $oRuleQuery     = $oSearchHandler->getQuery('rule');
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\RuleSearchQuery',$oRuleQuery);
        
        # Do a Dry run and verify filters and directives are applied
        
        $aParams =[
            'oApplyFrom'    => new \DateTime(),
            'oEndBefore'    => new \DateTime(), 
            'iRuleTypeId'   => $oRuleType,
            'iCalYear'      => $oCalYear,
            'iTimeslotId'   => $oTimeslot,
        ];
        
        
        $oQueryBuilder     = $oRuleQuery->setParameters($aParams)->build();
        
        $sRuleFilterQuery  = $oQueryBuilder->getSQL();
        $aRuleFilterParams = $oQueryBuilder->getParameters();
        
        $this->assertNotEmpty($sRuleFilterQuery);
        $this->assertNotEmpty($aRuleFilterParams);
        
        # Test timeslot Join Directive
        $this->assertRegExp('/INNER JOIN bolt_bm_timeslot tslot/', $sRuleFilterQuery);
        $this->assertRegExp('/INNER JOIN bolt_bm_rule_type rt/', $sRuleFilterQuery);
        
        # Test the start from
        $this->assertRegExp('/r.start_from >=/', $sRuleFilterQuery);
        $this->assertEquals($aParams['oApplyFrom'], $aRuleFilterParams['StartFrom']);
        
        # Test Apply Before
        $this->assertRegExp('/r.end_at <=/', $sRuleFilterQuery);
        $this->assertEquals($aParams['oEndBefore'], $aRuleFilterParams['EndAt']);
        
        # Timeslot 
        $this->assertRegExp('/r.timeslot_id =/', $sRuleFilterQuery);
        $this->assertEquals($aParams['iTimeslotId']->getTimeslotId(), $aRuleFilterParams['iTimeslotId']);
       
        
        # Cal Year
        $this->assertRegExp(preg_quote('/year(r.start_from) =/'), $sRuleFilterQuery);
        $this->assertEquals($aParams['iCalYear']->getCalendarYear(), $aRuleFilterParams['iCalYear']);
       
        
        # Rule Type
        $this->assertRegExp('/r.rule_type_id =/', $sRuleFilterQuery);
        $this->assertEquals($aParams['iRuleTypeId']->getRuleTypeId(), $aRuleFilterParams['iRuleTypeId']);
       
        
        
    }
    
    
    public function TestMemberQuery(CalendarYearEntity $oCalYear, TeamEntity $oTeam)
    {
        $oSearchHandler = new SelectQueryHandler($this->getContainer());
        $oQuery     = $oSearchHandler->getQuery('member');
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\MemberSearchQuery',$oQuery);
        
        # Do a Dry run and verify filters and directives are applied
        
        $aParams =[
            'oCreatedAfter' => new \DateTime(), 
            'oCreatedBefore'  => new \DateTime(),
            'iCreatedYear'   => 2016,
            'iCalYear'   => $oCalYear,
            'iScheduleTeam' => $oTeam,
        ];
        
        
        $oQueryBuilder = $oQuery->setParameters($aParams)->build();
        
        $sFilterQuery  = $oQueryBuilder->getSQL();
        $aFilterParams = $oQueryBuilder->getParameters();
        
        $this->assertNotEmpty($sFilterQuery);
        $this->assertNotEmpty($sFilterQuery);
   
        
        # Created in Cal Year
        $this->assertRegExp(preg_quote('/year(m.registered_date) =/'), $sFilterQuery);
        $this->assertEquals($aParams['iCreatedYear'], $aFilterParams['iCreatedYear']);
    
        # Created Before
        $this->assertRegExp(preg_quote('/m.registered_date <=/'), $sFilterQuery);
        $this->assertEquals($aParams['oCreatedBefore'], $aFilterParams['oCreatedBefore']);
     
        
        # Created After
        $this->assertRegExp(preg_quote('/m.registered_date >=/'), $sFilterQuery);
        $this->assertEquals($aParams['oCreatedAfter'], $aFilterParams['oCreatedAfter']);
        
        
        # Last Schedule Cal Year Test
        $this->assertRegExp(preg_quote('/(curSch.calendar_year = :iCalYear)/'), $sFilterQuery);
        $this->assertEquals($aParams['iCalYear']->getCalendarYear(), $aFilterParams['iCalYear']);
    
        # Member in team
        $this->assertRegExp(preg_quote('/st.team_id = :iScheduleTeam/'), $sFilterQuery);
        $this->assertEquals($aParams['iScheduleTeam']->getTeamId(), $aFilterParams['iScheduleTeam']);
    
        
    }
  
    
}
/* end of file */
