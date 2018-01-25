<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;


/**
 * Node that can be extended for Workday Rules Types
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class WorkdayRuleNode extends CommonRuleNode
{
   
   protected function doGetRuleTypeId()
   {
       return 1;
   }
 
    
    
}
/* End of File */