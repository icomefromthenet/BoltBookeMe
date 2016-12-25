<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\AbstractRepoQuery;

/**
 * Build Query for the Schedule Calendar
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0  
 */ 
class CalendarYearQueryBuilder extends AbstractRepoQuery
{
   
   /**
    * Return the current year flaged as a virtual column
    * 
    * @return this
    */ 
   public function withCurrentYearColumn($sAlias = '')
   {
       $this->addSelect('(CASE YEAR(NOW()) 
                           WHEN '.$this->getField($sAlias,'y').' 
                           THEN 1 
                           ELSE 0  
                          END) as current_year');
       
       return $this;
   }
    
    
   
       
    
}
/* End of File */