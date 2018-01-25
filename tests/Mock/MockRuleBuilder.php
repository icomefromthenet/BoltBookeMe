<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder\CommonRuleNode;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleEntity;

class MockRuleBuilder extends CommonRuleNode
{
   
     
    protected function doGetRuleTypeId()
    {
        return 500;
    }
    
    protected function doGetRepeatDayofWeek()
    {
        return '6';
    }
    
    protected function doGetRepeatDayofMonth()
    {
        return '1-31';
    }
    
    protected function doGetRepeatMonth()
    {
        return '5-8';
    }
    
    protected function doGetRepeatWeekofYear()
    {
        return '10-52';
    }
    
    protected function doGetSingleDayRule()
    {
        return false;
    }
  
    protected function doGetDefaultRuleName()
    {
        return 'Default Rule A';
    }
  
    protected function doGetDefaultRuleDesc()
    {
        return 'Default Rule A Description';
    }
    
    
    
    
}
/* End of Class */