<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;


/**
 * Node that can be extended for Holiday Rules Types
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class HolidayRuleNode extends CommonRuleNode
{
   
   protected function doGetRuleTypeId()
   {
       return 3;
   }
 
    
    
}
/* End of File */