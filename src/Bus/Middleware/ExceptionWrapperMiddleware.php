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
     * Ensure any exceptions are wrapped with this components custom exception.
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
            if(!$e instanceof BookMeException) {
                throw new BookMeException($e->getMessage(),0,$e);    
            } else {
                throw $e;
            }
            
        }
        
        return $returnValue;
    }
  
  
  
}
/* End of Clas */