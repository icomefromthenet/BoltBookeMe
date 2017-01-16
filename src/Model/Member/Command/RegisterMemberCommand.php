<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command;

use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\HasEventInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Listener\CommandEvent;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeEvents;


/**
 * This command is used to add a new member so they can create schedules
 * 
 * This would be stored against a user who needs schedules.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RegisterMemberCommand implements  HasEventInterface, ValidationInterface
{

 
  /**
   * @var integer the database id once the registration is complete
   */ 
  protected $iMemberDatabaseId;
  
  /**
   * @var string the members name
   */ 
  protected $sMemberName;
    
  /**
   * @var integer the bolt members database id
   */ 
  protected $iBoltMemberId;
  
    
  public function __construct($sMemberName, $iBoltMemberId = null)
  {
      $this->sMemberName   = $sMemberName;
      $this->iBoltMemberId = $iBoltMemberId;
  
  }
  
  
  
  /**
   * Set the database id of this new member
   * 
   * @param integer     $iMemberDatabaseId    The database id
   */ 
  public function setMemberId($iMemberDatabaseId)
  {
      $this->iMemberDatabaseId = $iMemberDatabaseId;
  }
  
  /**
   * Fetch the database id of the new member
   * 
   * @access public
   */ 
  public function getMemberId()
  {
      return $this->iMemberDatabaseId;
  }
  
  /**
   * Return the bolt member database id
   * 
   * @access public
   * @return integer the id if assigned
   */ 
  public function getBoltMemberId()
  {
    return $this->iBoltMemberId;
  }
  
  /**
   * Will fetch the members name
   * 
   * @access public
   * @return string 
   */ 
  public function getMemberName()
  {
    return $this->sMemberName;
  }
  
  //---------------------------------------------------------
  # validation interface
  
  
  public function getRules()
  {
      return [
        'required' => [
          ['member_name']
        ]
        ,'lengthBetween' => [
          ['member_name',1,100]
        ]
        ,'alphaNumAndSpace' => [
          ['member_name']
        ]
      ];
  }
  
  
  public function getData()
  {
      return [
        'member_name'    => $this->sMemberName,
        'bolt_member_id' => $this->iBoltMemberId,
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
    return BookMeEvents::MEMBER_REGISTER;  
  }
  
  
}
/* End of Clas */