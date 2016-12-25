<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TeamType extends AbstractType
{
    
    protected $oSchedule;
    
    
    
    public function __construct()
    {
        
        
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'm' => 'Male',
                'f' => 'Female',
            )
        ));
    }

    
    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */