<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

/**
 * A Query Builder used in search queries
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class AbstractSearchQuery extends QueryBuilder
{
    /**
     * @var array Table Alias used
     */ 
    protected $aAliasBag;
    
    /**
     * @var internal database table map
     */ 
    protected $aTables;
    
    
    /**
     *  Class Constructor
     * 
     * @param Connection        $oConnection    The Database connection
     * @param array             $aTables        The Database table map
     * 
     */ 
    public function __construct(Connection $oConnection, array $aTables)
    {
        $this->aAliasBag = array();
        $this->aTables   = $aTables;
        
        parent::__construct($oConnection);
        
    }
    
    //-------------------------------------------------------------------------
    # Alias Helpers
      
    /**
     * Check if the alias is in use
     * 
     * @return boolean true if in use
     */ 
    public function hasAlias($sAlias)
    {
        return isset($this->aAliasBag[$sAlias]);
    }
    
    /**
     * Bind Alias into the bag
     * 
     * @param string    $sAlias     The Table Alias
     * @param string    $sTable     The Datbase Table
     */ 
    public function useAlias($sAlias, $sTable)
    {
        if(false === $this->hasAlias($sAlias)) {
            $this->aAliasBag[$sAlias] = $sTable;
        }
        
    }
    
    //--------------------------------------------------------------------------
    # Table Map
    
    /**
     * Fetch the Assigned Repository
     * 
     * @return ObjectRepository
     */ 
    public function getTableName($sInternalName)
    {
        return $this->aTables[$sInternalName];
    }
    
    /**
     * Return the database table name map
     * 
     * @return array[internel => external]
     */ 
    public function getTableMap()
    {
        return $this->aTables;
    }
    
   //---------------------------------------------------------------------------
   
   
    
}
/* End of File */