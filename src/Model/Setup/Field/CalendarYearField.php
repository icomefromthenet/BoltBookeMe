<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;


class CalendarYearField extends AbstractType
{
    
    protected $oCalYearRepository;
    
    
    public function __construct(ReadOnlyRepository $oCalYearRepository)
    {
        $this->oCalYearRepository = $oCalYearRepository;
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $aChoices       = [];
        $iCurrentYear   = null;
      
        $aYears     = $this->oCalYearRepository->findAllCalendarYears();
        
        foreach($aYears as $oYear) {
            $iYear = $oYear->getCalendarYear();
            
            $aChoices[$iYear] = $iYear; 
            
            if($oYear->getCurrentYearFlag()) {
                $iCurrentYear = $iYear;
            }
        }
        
        $resolver->setDefaults([
            'choices'     => $aChoices,
            'required'    => true,
            'empty_data'  => $iCurrentYear,
            'choice_label' => function ($value, $key, $index) {
                return $value.' Year';
            },
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */