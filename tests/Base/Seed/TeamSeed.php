<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class ClosedScheduleSeed extends BaseSeed
{
    
   
   protected $aNewTeams;
    
    
    
    protected function closeSchedule($iScheduleId, $oCloseDate)
    {
        $oDatabase             = $this->oDatabaseAdapter;
        $aTableNames            = $this->getTableNames();
        
        
        
        
        return true;
             
    }
   
    
 
    
    
    protected function doExecuteSeed()
    {
       
        foreach($this->aClosedSchedules as $sKey => $aSchedule) {
            $this->closeSchedule(
                $aSchedule['SCHEDULE_ID'],
                $aSchedule['CLOSE_DATE']
            );
        }
        
        
        
        return true;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aNewTeams)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aNewTeams = $aNewTeams;
   
    }
    
    
}
/* End of Class */
