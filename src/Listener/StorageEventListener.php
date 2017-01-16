<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe\Listener;

use Bolt\Events\StorageEvent;
use Bolt\Storage\Entity\Users;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use League\Tactician\CommandBus;

/**
 * Event class to handle storage related events.
 *
 * @author Your Name <you@example.com>
 */
class StorageEventListener implements EventSubscriberInterface
{
    
    /**
     * @var League\Tactician\CommandBus
     */ 
    protected $oCommandBus;
    
    /** 
     * @var array The extension's configuration parameters 
    */
    protected $config;

    /**
     * Initiate the listener with Bolt Application instance and extension config.
     *
     * @param Application $app
     * @param $config
     */
    public function __construct(CommandBus $oCommandBus, array $config)
    {
        $this->oCommandBus = $oCommandBus;
        $this->config      = $config;
    }

    /**
     * Handles POST_SAVE storage event
     *
     * @param StorageEvent $event
     */
    public function onPostSave(StorageEvent $event)
    {
        $id     = $event->getId(); // record id
        $record = $event->getContent(); // record itself
        
        
        if($record instanceof Users && $event->isCreate()) {
            $sUserName      = $record->getUsername();
            $oCreateCommand = new RegisterMemberCommand($sUserName, $id);
            
            $this->oCommandBus->handle($oCreateCommand);
        }
        
        return true;
    }

    

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            \Bolt\Events\StorageEvents::POST_SAVE =>'onPostSave',
        ];
    }
    
    
}
/* End of Class */