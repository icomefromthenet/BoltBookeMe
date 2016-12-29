<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Query\QueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Type;

/**
 * Override the original as we want to pass the query builder into each filter so
 * need to pass it into QueryParameterParser
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class SelectQuery implements QueryInterface
{
    
    protected $qb;
    protected $params;
    protected $filters = [];
    protected $contenttype;
    protected $aTypeMap = [];
    protected $aDirective = [];
    protected $sIdField = '';     

    protected function setupDefaults()
    {
        
        
    }

    /**
     * Allows the data to be that been read from the database 
     * to have post process operations run.
     * 
     * This run after the doctrine dbal mapping been applied.
     * 
     * @return array of data given in first argument
     * @param array the data read from the databases
     */ 
    public function onRowMappingComplete(array $aData)
    {
        return $aData;
    }


    /**
     * Return type from a factory
     * 
     * @return Doctrine\DBAL\Types\Type
     */ 
    protected function getTypeFromFactory($sTypeName)
    {
        return Type::getType($sTypeName);
    }
   

    /**
     * Constructor.
     *
     * @param QueryBuilder  $qb
     * @param string        $sAlias     The Table Alias that was used on entity table          
     */
    public function __construct(QueryBuilder $qb, $sAlias)
    {
        $this->qb          = $qb;
        $this->contenttype = $sAlias;
        
        
        $this->setupDefaults();
    
    }

    
    /**
     * Sets the contenttype that this query will run against.
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contenttype = $contentType;
        
        return $this;
    }
    
    /**
     * Return the name of the content type ie the alias 
     * used on the table
     * 
     * @return string
     */ 
    public function getContentType()
    {
        return $this->contenttype;
    }
    
     /**
     * Allows public access to the QueryBuilder object
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }
    
     /**
     * Allows replacing the default querybuilder
     *
     * @return QueryBuilder
     */
    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->qb = $qb;
        
        return $this;
    }

    //--------------------------------------------------------------------------
    # Query Parameters


    /**
     * Sets the parameters that will filter / alter the query
     *
     * @param array $params
     */
    public function setParameters(array $params)
    {
        $this->params = $params;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------
    # Query Filters
    
    /**
     * @param QueryInterface $filter
     */
    public function addFilter(QueryInterface $filter)
    {
        $filter->setQueryBuilder($this->qb);
        $this->filters[] = $filter;
        
        return $this;
    }

    /**
     * Returns all the filters attached to the query
     *
     * @return QueryInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    
    //--------------------------------------------------------------------------
    # Result Type Map
    
    /**
     * Sets the a doctrine Type Map to use to convert database valeus to php
     * 
     * @return void
     * @param string                    $sColumnName the exact column name in the result set
     * @param Doctrine\DBAL\Types\Type  $oType the doctrine DBAL type
     */ 
    public function addMap($sColumnName, Type $oType)
    {
        $this->aTypeMap[$sColumnName] = $oType;
    }

    
    /**
     * Check if a mapping has been set
     * 
     * @return true if map at column been set
     * @param string    $sColumnName    The map to look for
     */ 
    public function hasMap($sColumnName) 
    {
        return isset($this->aTypeMap[$sColumnName]);
    }
    
    /**
     * Return the type map for this select query
     *  
     * @return array[$sColumnName => Type]
     */ 
    public function getMapping()
    {
        return $this->aTypeMap;
    }
    
    /**
     * Return the column mapping
     * 
     * @return string   $sColumnName    The database column name
     * @return Doctrine\DBAL\Types\Type
     */ 
    public function getMap($sColumnName)
    {
        return $this->aTypeMap[$sColumnName];
    }
    
    //--------------------------------------------------------------------------
    # Query Directive
    
    /**
     * Adds a new Query Directive which define the joins and select list
     * 
     * @return void
     * @param QueryInterface    $oDirective     The instance of directive
     */ 
    public function addDirective(QueryInterface $oDirective)
    {
        $this->aDirective[] = $oDirective;
    }
    
    /**
     * Return the all query Directive
     * 
     * @return array[QueryInterface]
     */ 
    public function getDirectivies()
    {
        return $this->aDirective;
    }
 
    
    /**
     * Part of the QueryInterface this turns all the input into a query
     * using filters which themselves are QueryInterfaces
     * 
     * @return QueryBuilder
     */
    public function build()
    {
        $query = $this->qb;
       
        // run directives
        foreach($this->getDirectivies() as $oDirective) {
            $oDirective->setParameters($this->params);
            $oDirective->build();
        }
        
        
        // run filters
        foreach($this->getFilters() as $oFilter) {
            $oFilter->setParameters($this->params);
            $oFilter->build();
        }
       
        return $query;
    }
    

    /**
     * @return string String representation of query
     */
    public function __toString()
    {
        $query = $this->build();

        return $query->getSQL();
    }
    
    /**
     * Return the row id field specified
     * 
     * @return string
     */ 
    public function getRowIdColumnName()
    {
        return $this->sIdField;
    }
    
    /**
     * Sets the row id field
     * 
     * @param string $sidField  the row database field that unique
     * @return void
     */ 
    public function setRowIdColumnName($sIdField)
    {
        $this->sIdField = $sIdField;
    }
}
/* End of Class */
