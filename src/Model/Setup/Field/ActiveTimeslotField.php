<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;


class ActiveTimeslotField extends AbstractType
{
    
    protected $oTimeslotRepository;
    
    
    public function __construct(ReadOnlyRepository $oTimeslotRepository)
    {
        $this->oTimeslotRepository = $oTimeslotRepository;
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $aChoices       = [];
      
        $aSlots     = $this->oTimeslotRepository->findAllActiveSlots();
        
        foreach($aSlots as $oSlot) {
            $aChoices[$oSlot->getTimeslotId()] = $oSlot->getSlotLength(). ' Min'; 
        }
        
        $resolver->setDefaults([
            'choice_list' => new ChoiceList(array_keys($aChoices),array_values($aChoices)),
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Timeslot'
            
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */