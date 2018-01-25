<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Builder;


/**
 * Node that can be extended for Surcharge Rules Types
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
abstract class SurchargeRuleNode extends CommonRuleNode
{
   
   protected function doGetRuleTypeId()
   {
       return 5;
   }
 
    
    
}
/* End of File */