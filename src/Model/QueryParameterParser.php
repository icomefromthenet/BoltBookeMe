<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Exception\QueryParseException;
use Bolt\Storage\Query\QueryParameterParser as BaseQueryParameterParser;
use Bolt\Storage\Query\Filter;
use Doctrine\DBAL\Query\QueryBuilder;


/**
 * Override the original as we cust a custo method to process each key => value
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class QueryParameterParser extends BaseQueryParameterParser
{
    
    /**
     * @var Doctrine\DBAL\Query\QueryBuilder
     */ 
    protected $oQueryBuilder;
    
    

    public function setQueryBuilder(QueryBuilder $oQueryBuilder)
    {
        $this->oQueryBuilder = $oQueryBuilder;
    }

    
    public function getQueryBuilder()
    {
        return $this->oQueryBuilder;
    }

    
    public function setupDefaults()
    {
        $this->addFilterHandler([$this, 'processMeAFilter']);
    }


    /**
     * Handles some errors in key/value string formatting.
     *
     * @param string            $key
     * @param string            $value
     * @param ExpressionBuilder $expr
     */
    public function processMeAFilter($key, $value, $expr)
    {
        
        $filter = new Filter();
        $filter->setKey($key);
        $filter->setExpression(call_user_func_array($expr,[ $key, $value, $this->oQueryBuilder ]));
        $filter->setParameters($value);

        return $filter;   
    }
    
    
    
}
/* End of Class */
