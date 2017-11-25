<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;


class MockApptHandlerSuccess
{
    
    protected $iAppt;
    
    public function __construct($iAppt) 
    {
        $this->iAppt = $iAppt;    
    }
    
    
    public function handle(CreateApptCommand $oCommand) 
    {
        $oCommand->iAppointmentId = $this->iAppt;
    }
    
}
/* End of File */