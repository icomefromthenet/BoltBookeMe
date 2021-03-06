<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Member;

use Bolt\Extension\IComeFromTheNet\BookMe\BookMeException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RegisterTeamCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\AssignTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\WithdrawlTeamMemberCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\Command\RolloverTeamsCommand;

use League\Tactician\Exception\Exception as BusException;
use Doctrine\DBAL\DBALException;


/**
 * Custom Exception for Membership errors.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
class MembershipException extends BookMeException implements BusException
{
    /**
     * @var mixed
     */
    public $oCommand;
    
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedRegisterMember(RegisterMemberCommand $oCommand, DBALException $oDatabaseException)
    {
        $exception = new static(
            'Unable to create new schedule member with name '.$oCommand->getMemberName(), 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedRegisterTeam(RegisterTeamCommand $oCommand, DBALException $oDatabaseException)
    {
        $exception = new static(
            'Unable to create new schedule team with name '.$oCommand->getTeamName(), 0 , $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedAssignTeamMember(AssignTeamMemberCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to assign member at id '.$oCommand->getMemberId() .' to team at id '.$oCommand->getTeamId(), 0, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    
    /**
     * @param mixed $invalidCommand
     *
     * @return static
     */
    public static function hasFailedWithdrawlTeamMember(WithdrawlTeamMemberCommand $oCommand, DBALException $oDatabaseException = null)
    {
        $exception = new static(
            'Unable to withdrawl member at id '.$oCommand->getMemberId() .' to team at id '.$oCommand->getTeamId(), 0, $oDatabaseException
        );
        
        $exception->oCommand = $oCommand;
        
        return $exception;
    }
    
    
    /**
     * Return the command that has failed validation
     * 
     * @return mixed
     */
    public function getCommand()
    {
        return $this->oCommand;
    }
    
    
}
/* End of File */