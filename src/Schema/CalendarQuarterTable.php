<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class CalendarQuarterTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Calender table that store the next x years in month quarter aggerates');
        
        $this->table->addColumn('y',             'smallint',    ['notnull' => true,'comment' =>'year where date occurs' ]);
        $this->table->addColumn('q',             'smallint',    ['notnull' => true,'comment' => 'quarter of the year date belongs']);
        $this->table->addColumn('m_start',       'date',    ['notnull' => true,'comment' => 'starting month']);
        $this->table->addColumn('m_end',         'date',    ['notnull' => true,'comment' => 'ending month']);
      
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['y','q']);
    }
}
/* End of Table */