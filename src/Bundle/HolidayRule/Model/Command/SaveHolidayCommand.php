<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Model\Command;

use DateTime;
use Yasumi\Holiday;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to save the holiday that been used to generate a rule
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class SaveHolidayCommand implements  ValidationInterface
{
    /**
     * @var Yasumi\Holiday
     */ 
    protected $oHoliday;
 
    
    protected $iScheduleId;
    
    
    protected $iRuleId;
 
 
    public function __construct(Holiday $oHoliday, $iRuleId, $iScheduleId)
    {
        $this->oHoliday    = $oHoliday;
        $this->iRuleId     = $iRuleId;
        $this->iScheduleId = $iScheduleId;
    }
 
 
    public function geHolidayDatetime()
    {
        return $this->iAppointmentId;
    }
    
    
    public function getScheduleId()
    {
        return $this->iScheduleId;
    }
    
    
    public function getRuleId()
    {
        return $this->iRuleId;
    }
 
    
    
    //---------------------------------------------------------
    # validation interface
   
   public function getRules()
    {
        return [
            'integer' => [
                ['iScheduleId'],['iRuleId']
            ]
            ,'min' => [
                 ['iScheduleId',1],['iRuleId',1]
            ]
            ,'required' => [
                ['iScheduleId'],['iRuleId'],['oHoliday']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'oHoliday'      => $this->oHoliday,
            'iScheduleId'   => $this->iScheduleId,
            'iRuleId'       => $this->iRuleId
        ];
    }
    
    
}
/* End of Clas */