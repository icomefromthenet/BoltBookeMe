<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form;

/**
 * Method needed to build json struct
 *
 * 
 * @author Lewis Dyer <getintouch@icomfromthenet.com>
 * @since 1.0
 */
interface OptionBuilderInterface
{
 
    public function getStruct();
 
    public function getJSON();
    
}
/* End of Interface */