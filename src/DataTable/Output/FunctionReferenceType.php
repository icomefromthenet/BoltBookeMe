<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output;

/**
 * Used to contain a frunction reference in javascript a FQN to a 
 * javascript function.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0
 */ 
class FunctionReferenceType 
{
   
   protected $sReferenceName;
   
   public function __construct($sReferenceName)
   {
        $this->sReferenceName = $sReferenceName;
   }
   
   
   public function getValue()
   {
       return $this->sReferenceName;
   }
    
}
/* End of Class */