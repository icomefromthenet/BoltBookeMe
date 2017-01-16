<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Schedule\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;


class ScheduleMemberField extends AbstractType
{
    
    protected $oScheduleRepo;
    
    
    public function __construct(ReadOnlyRepository $oScheduleRepo)
    {
        $this->oScheduleRepo = $oScheduleRepo;
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $aChoices       = [];
      
        $aRuleTypes     = $this->oScheduleRepo->findRuleTypes();
        
        
        foreach($aRuleTypes as $oRuleType) {
            $aChoices[$oRuleType->getRuleTypeId()] = ucfirst($oRuleType->getRuleTypeCode()); 
        }
        
        $resolver->setDefaults([
            'choice_list' => new ChoiceList(array_keys($aChoices),array_values($aChoices)),
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Rule Type',
           
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */