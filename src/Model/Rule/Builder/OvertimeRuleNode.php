<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;


/**
 * Node that can be extended for Overtime Rules Types
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class OvertimeRuleNode extends CommonRuleNode
{
   
   protected function doGetRuleTypeId()
   {
       return 4;
   }
 
    
    
}
/* End of File */