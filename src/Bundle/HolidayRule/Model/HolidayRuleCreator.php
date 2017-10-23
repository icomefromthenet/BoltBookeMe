<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Command\SaveHolidayCommand;


class HolidayRuleCreator
{
    
    /**
     *  @var Connection
     */ 
    protected $oDatabase;
    

    public function __construct (Connection $oDatabase) 
    {
        $this->oDatabase = $oDatabase;
        
    }
    
    
    public function newRule()
    {
        
    }
    
    public function forMember($iMemberId)
    {
        
    }
        
        
    public function forCalendarYear()
    {
        
        
    }
    
}
/* End of Class */