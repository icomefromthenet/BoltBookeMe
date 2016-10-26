<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\Custom;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\FormContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;

/**
 * Container that render a bootstrap inline form
 * 
 * @since 1.0
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class InlineFormContainer extends FormContainer
{
    
    
    public function setDefaults()
    {
        parent::setDefaults();
        
        
        $this->addFuncRef('fieldTemplate',new FunctionReferenceType('bookme.form.inlineTemplate'));
    }
    
    
}
/* End of Class */

