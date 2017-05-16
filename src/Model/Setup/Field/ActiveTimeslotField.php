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
        
        $resolver->setDefaults([
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Timeslot',
            'choices'     => $this->oTimeslotRepository->findAllActiveSlots(),
            'choices_as_values' => true,
            'choice_label' => function($oTimeSlot, $key, $index) {
                /** @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\TimeslotEntity $category */
                if($oTimeSlot !== null) {
                    return $oTimeSlot->getSlotLength(). ' Min'; 
                }
            },
            'choice_value' => 'getTimeslotId'
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */