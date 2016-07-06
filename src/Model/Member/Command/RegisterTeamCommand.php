<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command;

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to add a new team
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RegisterTeamCommand implements  HasEventInterface, ValidationInterface
{

 
  /**
   * @var integer the database id once the registration is complete
   */ 
  protected $iTeamDatabaseId;
  
  /**
   * @var string a name for the team
   */ 
  protected $sTeamName;  
    
    
  public function __construct($sTeamName)
  {
    $this->sTeamName = $sTeamName;    
  }
  
  
  /**
   * Load the assigned team name
   * 
   * @return string
   * 
   */ 
  public function getTeamName()
  {
      return $this->sTeamName;
  }
  
  
  /**
   * Set the database id of this new team
   * 
   * @param integer     $iTeamDatabaseId    The database id
   */ 
  public function setTeamId($iTeamDatabaseId)
  {
      $this->iTeamDatabaseId = $iTeamDatabaseId;
  }
  
  /**
   * Fetch the database id of the new team
   * 
   * @access public
   */ 
  public function getTeamId()
  {
      return $this->iTeamDatabaseId;
  }
  
  //---------------------------------------------------------
  # validation interface
  
  
  public function getRules()
  {
      return [
       'required' => [
          ['team_name']
        ]
        ,'lengthBetween' => [
          ['team_name',1,100]
        ]
        ,'alphaNumAndSpace' => [
          ['team_name']
        ]
      ];
  }
  
  
  public function getData()
  {
      return [
        'team_name' => $this->sTeamName
      ];
  }
  
  
  //----------------------------------------------------------------
  # Has Event Interface
  
  public function getEvent()
  {
      return new CommandEvent($this);
  }
  
    
  public function getEventName()
  {
    return BookMeEvents::TEAM_REGISTER;  
  }
  
  
}
/* End of Clas */