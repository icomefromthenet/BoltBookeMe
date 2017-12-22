<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\MemberSeed;

class MemberFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $oMemberSeed = new MemberSeed($oDatabase, $aTableNames,[
          'iMemberOne'   => ['MEMBER_NAME' => 'Bob Builder', 'BOLT_USER_ID' => 1],
          'iMemberTwo'   => ['MEMBER_NAME' => 'Bobs Assisstant', 'BOLT_USER_ID' => 1],
          'iMemberThree' => ['MEMBER_NAME' => 'Bill Builder', 'BOLT_USER_ID' => 1],
          'iMemberFour'  => ['MEMBER_NAME' => 'Bill Assistant', 'BOLT_USER_ID' => 1],
          'iMemberFive'  => ['MEMBER_NAME' => 'New Assistant', 'BOLT_USER_ID' => 1],
          
        ]);
      
        return $oMemberSeed->executeSeed();
        
    }
    
}
/* End of Class */
