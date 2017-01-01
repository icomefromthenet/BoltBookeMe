<?php

namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Query\QueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Directive that used by SelectQuery.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class AbstractDirective implements QueryInterface
{
 
    protected $qb;
    protected $params;
    protected $sAlias;


    /**
     * Constructor.
     *
     * @param QueryBuilder  $qb
     * @param string        $sAlias     The Alias used on the entity table
     */
    public function __construct(QueryBuilder $qb, $sAlias)
    {
        $this->qb     = $qb;
        $this->sAlias = $sAlias;
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
    }

    /**
     * Sets the parameters that will filter / alter the query
     *
     * @param array $params
     */
    public function setParameters(array $params)
    {
        $this->params = $params;
    }
    
    /**
     * Return the alias used on the entity table
     * 
     * @return string
     */ 
    public function getAlias()
    {
        return $this->sAlias;
    }
    
    
    /**
     * Part of the QueryInterface this turns all the input into a query
     * using filters which themselves are QueryInterfaces
     * 
     * @return QueryBuilder
     */
    public function build()
    {
        
    }


    public function getField($sAlias = '', $sField, $sAs = '') 
    {
        if(true === empty($sAlias)) {
            $sAlias = $this->getAlias();
        }
        
        if(false === empty($sAlias)) {
            $sField = $sAlias.'.'.$sField;
        }
        
        if(false === empty($sAs)) {
            $sField = $sField.' AS '.$sAs;
        }
        
        return $sField;
    }
    
}
/* End of Class */
