<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Valitron\Validator;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Listener\StorageEventListener;
use Bolt\Events\StorageEvent;
use Bolt\Storage\Entity\Users;


class SotageEventTest extends ExtensionTest
{
    
    
    
   protected function handleEventPostFixtureRun()
   {

   }
   
   
   public function testUserCreatedCreatesScheduleMember()
   {
       
       $oUser       = new Users();
       $aMockConfig = [];
       
       $oUser->id       = 1;
       $oUser->username = 'BobSmith';
       
       $oStorageEvent = new StorageEvent($oUser, ['create' => true]);
       
       $oMockBus  = $this->getMockBuilder('League\\Tactician\\CommandBus')
                         ->setMethods(['handle'])
                         ->disableOriginalConstructor()
                         ->getMock();
       
       $oMockBus->expects($this->once())
                ->method('handle')
                ->with($this->isInstanceOf('Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand'));
       
       $oListener = new StorageEventListener($oMockBus,$aMockConfig);
       
       $oListener->onPostSave($oStorageEvent);
       
   }
   
   
   public function testUserNotCreatedWhenUserUpdated()
   {
       
       $oUser       = new Users();
       $aMockConfig = [];
       
       $oUser->id       = 1;
       $oUser->username = 'BobSmith';
       
       $oStorageEvent = new StorageEvent($oUser, ['create' => false]);
       
       $oMockBus  = $this->getMockBuilder('League\\Tactician\\CommandBus')
                         ->setMethods(['handle'])
                         ->disableOriginalConstructor()
                         ->getMock();
       
       $oMockBus->expects($this->exactly(0))
                ->method('handle');
                
       
       $oListener = new StorageEventListener($oMockBus,$aMockConfig);
       
       $oListener->onPostSave($oStorageEvent);
       
   }
    
}
/* End of File */