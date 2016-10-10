<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Model;

use DateTime;
use LaterJob\Queue;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Command\RefreshScheduleCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\ScheduleException;


/**
 * Reapply rules series to a schedule
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RefreshScheduleDecorator
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    The database adpater
     */ 
    protected $oDatabaseAdapter;
    
    /**
     * @var LaterJob\Queue
     */ 
    protected $oQueue;
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Handler\RefreshScheduleHandler
     */ 
    protected $oRefreshHandler;
    
    /**
     * @var DateTime The current date time to pass into queue when create a new job
     */ 
    protected $oNow;
    
    

    public function __construct(RefreshScheduleHandler $oRefreshHandler, array $aTableNames, Connection $oDatabaseAdapter, Queue $oQueue, DateTime $oNow)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        $this->oRefreshHandler  = $oRefreshHandler;
        $this->oNow             = $oNow;
        $this->oQueue           = $oQueue;
    }
    
    
    public function handle(RefreshScheduleCommand $oCommand)
    {
        $mResult = null;
        
        if(true === $oCommand->sendToQueue()) {
            // Save into Job Queue instead of processing
            $mResult = $this->oQueue->send($this->oNow, $oCommand->getScheduleId());
            
        } else {
            $mResult = $this->oRefreshHandler->handle($oCommand);
        }
        
        return $mResult;
    }
     
    
}
/* End of File */