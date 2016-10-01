<?php 
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExcpetion;


/**
 * Custom exceptions for datatables builders
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DataTableException extends BookMeExcpetion
{
    
     /**
     * Error raised when Deregister event that does not exist
     * 
     * @return static
     */
    public static function errorEventDoesNotExist($sEventName, $sFuncRef)
    {
        $exception = new static(
            "Unable to remove event at $sEventName for handler $sFuncRef", null, null
        );
        
        return $exception;
    }
    
    
}
/* End of Class */