<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;


class CalendarYearType extends AbstractType
{
    
    protected $oCalYearRepository;
    
    
    public function __construct(ReadOnlyRepository $oCalYearRepository)
    {
        $this->oCalYearRepository = $oCalYearRepository;
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $aYears =$this->oCalYearRepository->createQueryBuilder();
        
        
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