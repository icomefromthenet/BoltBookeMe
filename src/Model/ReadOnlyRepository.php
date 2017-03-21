<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Doctrine\Common\Persistence\ObjectRepository;
use Bolt\Storage\Repository;


/**
 * This ensure that Repository can be used for read only operations as where doing
 * all other insert and update using command bus.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ReadOnlyRepository extends Repository implements ObjectRepository
{
   
   protected $aTableMap;
   
   
    /**
     * Return this entity table name or if given internal name will return it instead
     * 
     * @return string
     * @param  string|null  $sInternalName  Optional name
     */
    public function getTableName($sInternalName = null)
    {
        $sTableName = null;
        
        if($sInternalName === null) {
            $sTableName = $this->getClassMetadata()->getTableName();
        }  
        else {
            $sTableName = $this->aTableMap[$sInternalName];    
        }
        
        return $sTableName;
         
    }

    /**
     * Set the internal table map
     *  
     * @param array [internal=>actual]
     */ 
    public function setTableMap(array $aTableMap)
    {
        $this->aTableMap = $aTableMap;
    }
   
   
  

   
   
   /**
     * Creates a new QueryBuilder instance that is prepopulated for this rule.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias = null)
    {
         throw new \RuntimeException('Must be implemented by child');
    }
   
   
    public function delete($entity)
    {
       throw new \RuntimeException('Not supported');
    }

  
    public function save($entity, $silent = null)
    {
        throw new \RuntimeException('Not supported');
    }

   
    public function insert($entity)
    {
         throw new \RuntimeException('Not supported');
    }

    
    public function update($entity, $exclusions = [])
    {
         throw new \RuntimeException('Not supported');
    }

}
/* End of Class */