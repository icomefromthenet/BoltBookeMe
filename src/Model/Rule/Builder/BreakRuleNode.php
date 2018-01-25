<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;


/**
 * Node that can be extended for Break Rules Types
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class BreakRuleNode extends CommonRuleNode
{
   
   protected function doGetRuleTypeId()
   {
       return 2;
   }
 
    
    
}
/* End of File */