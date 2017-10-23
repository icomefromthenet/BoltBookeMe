<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Command;

use DateTime;
use Yasumi\Holiday;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to Generate a Holiday Rule.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateHolidayCommand implements  ValidationInterface
{
    /**
     * @var Yasumi\Holiday
     */ 
    protected $oHoliday;
 
    
    protected $iScheduleId;
    
    
    protected $iNewRuleId;
    
  
 
 
    public function __construct(Holiday $oHoliday, $iScheduleId)
    {
        $this->oHoliday    = $oHoliday;
        $this->iScheduleId = $iScheduleId;
        $this->iNewRuleId     = null;
    }
 
 
    public function geHolidayDatetime()
    {
        return $this->iAppointmentId;
    }
    
    
    public function getScheduleId()
    {
        return $this->iScheduleId;
    }
    
    
    public function getNewRuleId()
    {
        return $this->iNewRuleId;
    }
    
    
    public function setNewRuleId($iNewRuleId)
    {
        $this->iNewRuleId = $iNewRuleId;
    }
    
    
    //---------------------------------------------------------
    # validation interface
   
   public function getRules()
    {
        return [
            'integer' => [
                ['iScheduleId'],
            ]
            ,'min' => [
                 ['iScheduleId',1],
            ]
            ,'required' => [
                ['iScheduleId'],['oHoliday']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'oHoliday'      => $this->oHoliday,
            'iScheduleId'   => $this->iScheduleId,
            'iNewRuleId'    => $this->iNewRuleId
        ];
    }
    
    
}
/* End of Clas */