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
        $aChoices       = [];
        $aTeams         = $this->oTeamRepo->findAllTeams();
        
        foreach($aTeams as $oTeam) {
            $aChoices[$oTeam->getTeamId()] = ucfirst($oTeam->getTeamName()); 
        }
        
        $resolver->setDefaults([
            'choice_list' => new ChoiceList(array_keys($aChoices),array_values($aChoices)),
            'required'    => false,
            'empty_data'  => null,
            'placeholder' => 'Select A Team'
            
        ]);
    }

    
    public function getParent()
    {
        return ChoiceType::class;
    }
    
}
/* End of Class */