<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\ActionRoute;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\ActionRoute;

class EditMember extends ActionRoute
{
    
    
    public function getUrl(UrlGeneratorInterface $oGenerator, array $aRow)
    {
        return $oGenerator->generate($this->getRouteName(),['member' => $aRow['membershipId']]);     
    }

}
/* End of class */