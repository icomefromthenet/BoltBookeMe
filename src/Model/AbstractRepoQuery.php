<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

/**
 * Used to allow a query builder to execute the built query with the repo which
 * created it.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class AbstractRepoQuery extends QueryBuilder
{
    /**
     * @var array Table Alias used
     */ 
    protected $aAliasBag;
    
    /**
     * @var rule repositry
     */ 
    protected $oRepo;
    
    
    /**
     * Check if the alias is in use
     * 
     * @return boolean true if in use
     */ 
    protected function hasAlias($sAlias)
    {
        return isset($this->aAliasBag[$sAlias]);
    }
    
    /**
     * Bind Alias into the bag
     * 
     * @param string    $sAlias     The Table Alias
     * @param string    $sTable     The Datbase Table
     */ 
    protected function useAlias($sAlias, $sTable)
    {
        if(false === $this->hasAlias($sAlias)) {
            $this->aAliasBag[$sAlias] = $sTable;
        }
        
    }
    
    
    /**
     *  Class Constructor
     * 
     * @param Connection        $oConnection    The Database connection
     * @param ObjectRepository  $oRepo          The Entity Repository
     * 
     */ 
    public function __construct(Connection $oConnection, ObjectRepository $oRepo)
    {
        $this->aAliasBag = array();
        $this->oRepo     = $oRepo;
        
        parent::__construct($oConnection);
        
    }
    
    /**
     * Fetch the Assigned Repository
     * 
     * @return ObjectRepository
     */ 
    protected function getRepository()
    {
        return $this->oRepo;
    }
    
    /**
     * Sets the object repository
     * 
     * @param ObjectRepository $oRepo
     */ 
    public function setRepository(ObjectRepository $oRepo)
    {
        $this->oRepo = $oRepo;
    }
    
    /**
     * Execute the query build here
     * 
     * @return array of results
     */ 
    public function findWith()
    {
        return $this->getRepository()->findWith($this);
    }
    
    /**
     * Execute the query build here
     * 
     * @return object a single entity
     */
    public function findOneWith()
    {
        return $this->getRepository()->findOneWith($this);
    }
    
    
    //------------------------------------------------------------------
    # Scopes helper 
    
    public function ifThen($bBool, $fn)
    {
        if(false === is_callable($fn)) {
            throw new RuntimeException ('Expected a callable');
        }
        
        // call the lambda with the query builder as argument
        if(true === $bBool) {
            $fn($this);
        }
        
        return $this;
    }

    
    public function ifThenElse($bBool, $fn1, $fn2)
    {
        if(false === is_callable($fn1) || false === is_callable($fn2)) {
            throw new RuntimeException ('Expected a callable');
        }
        
        // call the lambda with the query builder as argument
        if(true === $bBool) {
            $fn1($this);
        } else {
            $fn2($this);
        }
        
        return $this;
    }
    
    
    
    public function getField($sAlias, $sField, $sASField = null) 
    {
        $sFQF = null;
        
        if(empty($sAlias)) {
            $sFQF  = $sField;
        }else {
            $sFQF = $sAlias.'.'.$sField;
        }   
        
        if(!empty($sASField)) {
            $sFQF = $sFQF .' AS '. $sASField; 
        }
        
        return $sFQF;
    }
    
    
}
/* End of File */