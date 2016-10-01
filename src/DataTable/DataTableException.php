<?php 
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;


/**
 * Custom exceptions for datatables builders
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DataTableException extends BookMeException
{
    
     /**
     * Error raised when event that does not exist
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
    
    /**
     * Error raised when column event that does not exist
     * 
     * @return static
     */
    public static function errorColumnDoesNotExist($sColumnName)
    {
        $exception = new static(
            "Unable to load column at $sColumnName ", null, null
        );
        
        return $exception;
    }
    
    
}
/* End of Class */