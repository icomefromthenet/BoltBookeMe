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
            if($oYear->getCurrentYearFlag()) {
                $iCurrentYear = $oYear->getCalendarYear();
            }
        }
        
        $resolver->setDefaults([
            'choices'     =>$aYears,
            'required'    => true,
            'empty_data'  => $iCurrentYear,
            'choices_as_values' => true,
            'choice_label' => function($oCalYear, $key, $index) {
                /** @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\CalendarYearEntity $category */
                if($oCalYear !== null) {
                    return $oCalYear->getCalendarYear(). ' Year'; 
                }
            },
            'choice_value' => 'getCalendarYear'
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */