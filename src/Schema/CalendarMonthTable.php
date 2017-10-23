<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class CalendarMonthTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Calender table that store the next x years in month aggerates');
        
        $this->table->addColumn('y',             'smallint',    ['notnull' => true,'comment' =>'year where date occurs' ]);
        $this->table->addColumn('m',             'smallint',    ['notnull' => true,'comment' => 'month of the year']);
        $this->table->addColumn('month_name',    'string',      ['notnull' => true,'comment' => 'text name of the month',"length" => 10]);
        $this->table->addColumn('m_sweek',       'smallint',    ['notnull' => true,'comment' => 'open week number in the year']);
        $this->table->addColumn('m_eweek',       'smallint',    ['notnull' => true,'comment' => 'closing week number in the year']);
        
        
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
        $this->table->setPrimaryKey(['y','m']);
    }
}
/* End of Table */