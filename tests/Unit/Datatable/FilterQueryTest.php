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
    * @group Setup
    */ 
    public function testCustomRepo()
    {
       
       $this->TestRuleQuery();
       $this->TestMemberQuery();
       
    }
    
    public function testCustomFields()
    {
        
        $this->TestCalendarYearField();
        $this->TestActiveTimeslotField();
        $this->TestRuleTypeField();
        $this->TestScheduleTeamField();
        
    }
    
    protected function TestCalendarYearField()
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory->create(CalendarYearField::class);

        $formData = array(
          'cal_year' => 2016
        );
        
        $form->submit($formData);


        //$this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());
        
    }
    
    protected function TestActiveTimeslotField()
    {
        $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory->create(ActiveTimeslotField::class);

        $formData = array(
          '1' => 15
        );
        
        $form->submit($formData);


        //$this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());
        
    }
  
    protected function TestRuleTypeField()
    {
         $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory->create(RuleTypeField::class,array('example_option' => 'aaaa'));

        $formData = array(
          '1' => 2016
        );
        
        $form->submit($formData);


        //$this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());
        
    }
    
    protected function TestScheduleTeamField()
    {
         $oApp           = $this->getContainer();
        $oFactory       = $oApp['form.factory'];
        
        
        $form = $oFactory->create(ScheduleTeamField::class);

        $formData = array(
          '1' => 2016
        );
        
        $form->submit($formData);
        
         //$this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());

    }
    
    
    protected function TestRuleQuery()
    {
        
        # Fetch Search Query From Container
        
        $oSearchHandler = new SelectQueryHandler($this->getContainer());
        $oRuleQuery     = $oSearchHandler->getQuery('rule');
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\DataTable\RuleSearchQuery',$oRuleQuery);
        
        # Do a Dry run and verify filters and directives are applied
        
        $aParams =[
            'oApplyFrom' => new \DateTime(),
            'oEndBefore' => new \DateTime(), 
            'iRuleTypeId' => 1,
            'iCalYear' => 2016,
            'iTimeslotId' => 2,
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
        $this->assertEquals($aParams['iTimeslotId'], $aRuleFilterParams['iTimeslotId']);
       
        
        # Cal Year
        $this->assertRegExp(preg_quote('/year(r.start_from) =/'), $sRuleFilterQuery);
        $this->assertEquals($aParams['iCalYear'], $aRuleFilterParams['iCalYear']);
       
        
        # Rule Type
        $this->assertRegExp('/r.rule_type_id =/', $sRuleFilterQuery);
        $this->assertEquals($aParams['iRuleTypeId'], $aRuleFilterParams['iRuleTypeId']);
       
        
        
        # Execute a query using handler and test rule mapping 
        $aParams            = [];
        $oRuleQuery         = $oSearchHandler->getQuery('rule');
        
        $aQueryResultSet = $oSearchHandler->executeQuery($oRuleQuery,$aParams);
        
        $this->assertGreaterThanOrEqual(2,count($aQueryResultSet));
        
        # Test Dates columns are mapped which means its iterating over mapping rules
        $oRecord = $aQueryResultSet->get(1);

        $this->assertInstanceOf('\DateTime',$oRecord['startFrom']);
        $this->assertInstanceOf('\DateTime',$oRecord['endAt']);
        
        
        
    }
    
    
    public function TestMemberQuery()
    {
        $oSearchHandler = new SelectQueryHandler($this->getContainer());
        $oQuery     = $oSearchHandler->getQuery('member');
        
        $this->assertInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\DataTable\MemberSearchQuery',$oQuery);
        
        # Do a Dry run and verify filters and directives are applied
        
        $aParams =[
            'oCreatedAfter' => new \DateTime(), 
            'oCreatedBefore'  => new \DateTime(),
            'iCreatedYear'   => 2016,
            'iCalYear'   => 2017,
            'iScheduleTeam' => 2,
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
        $this->assertEquals($aParams['iCalYear'], $aFilterParams['iCalYear']);
    
        # Member in team
        $this->assertRegExp(preg_quote('/st.team_id = :iScheduleTeam/'), $sFilterQuery);
        $this->assertEquals($aParams['iScheduleTeam'], $aFilterParams['iScheduleTeam']);
    
        
    }
  
    
}
/* end of file */
