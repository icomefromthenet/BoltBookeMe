<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Mock;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment\Command\CreateApptCommand;


class MockApptHandlerFail
{
    
    public function handle(CreateApptCommand $oCommand) 
    {
        $oCommand->iAppointmentId = null;
    }
    
}
/* End of File */