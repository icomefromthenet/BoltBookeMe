<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule;

use Bolt\Storage\Query\QueryInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\SelectQuery;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Filter\ApplyFromFilter;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Filter\EndBeforeFilter;

/**
 * A Query of the Rule Entity
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com> 
 * @since 1.0
 */
class RuleQuery extends SelectQuery implements QueryInterface
{
   
 
    protected function setupDefaults()
    {
        $this->addFilter(new ApplyFromFilter($this->getQueryBuilder(),$this->getContentType()));
        $this->addFilter(new EndBeforeFilter($this->getQueryBuilder(),$this->getContentType()));
        
    }
 
    
}
/* End of File */