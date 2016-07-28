<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;


/**
 * Represent a appointment in our database
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class ApptEntity implements ValidationInterface
{
    
    public $iAppointmentId;
    
    public $iCustomerId;
    
    public $iBookingId;
    
    public $sInstructions;    
        
    public $sStatusCode;    

    public $sApptNumber;

    //---------------------------------------------------------
    # validation interface
    
    
    public function getRules()
    {
        return [
            'integer' => [
                ['iAppointmentId'],['iCustomerId'],['iBookingId']
            ]
            ,'min' => [
                 ['iAppointmentId',1],['iCustomerId',1],['iBookingId',1],['sApptNumber',1]
            ]
            ,'lengthMax' => [
                ['sInstructions',1000],['sStatusCode',2],['sApptNumber',25]
            ]
            ,'required' => [
                ['iCustomerId'],['sStatusCode']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
             'iAppointmentId'      => $this->iAppointmentId,
             'iCustomerId'         => $this->iCustomerId,
             'iBookingId'          => $this->iBookingId,
             'sInstructions'       => $this->sInstructions,
             'sStatusCode'         => $this->sStatusCode,
             'sApptNumber'         => $this->sApptNumber,
          
        ];
    }
    
}
/* End of customer */