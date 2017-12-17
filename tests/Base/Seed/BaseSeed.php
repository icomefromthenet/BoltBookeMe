<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use Doctrine\DBAL\Connection;
use Exception;

abstract class BaseSeed 
{
    
    protected $oDatabase;
    
    protected $aTableNames;
    
    
    
    
    abstract protected function doExecuteSeed();
    
    
    
    public function __construct(Connection $oDatabase, array $aTableNames)
    {
        $this->oDatabase = $oDatabase;
        $this->aTableNames = $aTableNames;
    }
    
    public function executeSeed()
    {
        try {
        
           return $this->doExecuteSeed();
          
        }
        catch (Exception $e) {
            throw $e;    
        }
    }
    
    /**
     * Return the database adapter
     * 
     * @return Doctrine\DBAL\Connection
     */ 
    public function getDatabase()
    {
        return $this->oDatabase;
    }
    
    /**
     *  Return a list of table names that mapp internal => actual 
     * 
     *  @return []
     */ 
    public function getTableNames()
    {
        return $this->aTableNames;
    }
    
}
/* End Class */