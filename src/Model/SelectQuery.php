<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Query\QueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

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


    protected function setupDefaults()
    {
        
        
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
    
    
    /**
     * Part of the QueryInterface this turns all the input into a query
     * using filters which themselves are QueryInterfaces
     * 
     * @return QueryBuilder
     */
    public function build()
    {
        $query = $this->qb;
       
        foreach($this->filters as $oFilter) {
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
    
    
   
    
}
/* End of Class */
