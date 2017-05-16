<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;


class RuleTypeField extends AbstractType
{
    
    protected $oRuleTypeRepository;
    
    
    public function __construct(ReadOnlyRepository $oRuleTypeRepository)
    {
        $this->oRuleTypeRepository = $oRuleTypeRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        
        $resolver->setDefaults([
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Rule Type',
            'choices'     => $this->oRuleTypeRepository->findRuleTypes(),
            'choices_as_values' => true,
            'choice_label' => function($oRuleType, $key, $index) {
                /** @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Rule\RuleTypeEntity $category */
                if($oRuleType !== null) {
                    return ucfirst($oRuleType->getRuleTypeCode());
                }
            },
            'choice_value' => 'getRuleTypeId'
        ]);
        
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */