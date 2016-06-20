<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware;

use League\Tactician\Middleware;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;



/**
 * This middle ware will ensure any exceptions are wrapped with this components
 * custom exception.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ExceptionWrapperMiddleware implements Middleware
{

  
  
    /**
     * Will Validate the command if it implements the valdiation interface
     * 
     * @throws Bolt\Extension\IComeFromTheNet\BookMe\BookMeException
     * @param mixed $oCommand
     * @param callable $next
     * 
     */ 
    public function execute($oCommand, callable $next)
    {
        
        try {
        
            $returnValue = $next($oCommand);
        
        } catch(\RuntimeException $e) {
            throw new BookMeException($e->getMessage(),0,$e);
        }
        
        return $returnValue;
    }
  
  
  
}
/* End of Clas */