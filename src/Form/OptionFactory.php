<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;


/**
 * Helper to build inline builder objects
 *
 * @example
 *
 * OptionFactory::createObjectBuilder($oOutput)
 *              ->addPrimitive('a',100)
 *              ->addPrimitive('b',100)
 *              ->addArrayValue('c',OptionFactory::createArrayBuilder()
 *                                               ->addPrimitive(100)
 *                                               ->addPrimitive(200)
 *              ))
 *              ->getJSON();
 * Output:
 * {
 *  'a' : 100   
 *  'b' : 100  
 *  'c' : [ 100, 200 ] 
 * }
 * 
 * @author Lewis Dyer <getintouch@icomfromthenet.com>
 * @since 1.0
 */
class OptionFactory
{
 
    static public function createObjectBuilder(StringOutput $oOutput) 
    {
        return new JSONObjectBuilder($oOutput);
    }
    
    
    static public function createArrayBuilder(StringOutput $oOutput) 
    {
        return new JSONArrayBuilder($oOutput);
    }
 
}
/* End of Class */