<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ReadOnlyRepository;

class ScheduleTeamField extends AbstractType
{
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamRepository
     */ 
    protected $oTeamRepo;
    
    
    
    public function __construct(ReadOnlyRepository $oTeamRepo)
    {
        $this->oTeamRepo = $oTeamRepo;
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Team',
            'choices'     =>  $this->oTeamRepo->findAllTeams(),
            'choices_as_values' => true,
            'choice_label' => function($oTeam, $key, $index) {
                /** @var Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\TeamEntity $category */
                if($oTeam !== null) {
                    return ucfirst($oTeam->getTeamName());
                }
            },
            'choice_value' => 'getTeamId'
        ]);
    }

    
    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */