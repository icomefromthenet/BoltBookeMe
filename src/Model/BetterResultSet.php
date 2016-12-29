<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Query\QueryResultset;

class BetterResultSet extends QueryResultset
{
    
    
    /**
     * Allows retrieval of a whole result set
     *
     * @param string $label
     *
     * @return ArrayIterator
     */
    public function getAll()
    {
        $results = [];
        foreach ($this->results as $v) {
                $results[] = $v;
        }

        return $results;
    }
    
    
}
/* End of Class */