<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Booking\Command;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to schedule a manual booking a booking done by a user
 * that will not require maxbooking or a lead time check.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ManualBookingCommand  extends TakeBookingCommand 
{




}
/* End of Clas */