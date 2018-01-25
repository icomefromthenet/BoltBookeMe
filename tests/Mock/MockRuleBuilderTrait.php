<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\RuleBuilderTrait;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;

class MockRuleBuilderTrait 
{
    use RuleBuilderTrait;
    
    
    public function runTest(ExtensionTest $oTest, ScheduleEntity $oSchedule, $oStartDate, $oEndDate, $oStartTime, $oEndTime, $sRuleName, $sRuleDescription)
    {
        $oTest->assertEquals($oSchedule, $this->oSchedule);
        
        $oTest->assertEquals($oStartDate, $this->oStartFromDate);
        $oTest->assertEquals($oEndDate, $this->oEndtAtDate);
        
        $oTest->assertEquals($oStartTime, $this->oStartingTime);
        $oTest->assertEquals($oEndTime, $this->oEndtAtTime);
        
        $oTest->assertEquals($sRuleName, $this->sRuleName);
        $oTest->assertEquals($sRuleDescription, $this->sRuleDesc);
        
        
    }
}
/* End of Class */