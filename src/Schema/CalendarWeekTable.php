<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class CalendarWeekTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addOption('comment','Calender table that store the next x years in week aggerates');
     
        
        $this->table->addColumn('y',             'smallint',    ['notnull' => true,'comment' =>'year where date occurs' ]);
        $this->table->addColumn('m',             'smallint',    ['notnull' => true,'comment' => 'month of the year']);
        $this->table->addColumn('w',             'smallint',    ['notnull' => true,'comment' => 'week number in the year']);
        
        $this->table->addColumn('open_date', 'date',        ['notnull' => true, 'comment' =>'first date of week']);
        $this->table->addColumn('close_date', 'date',        ['notnull' => true, 'comment' =>'last date of week']);
   
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
        $this->table->setPrimaryKey(['y','w']);
    }
}
/* End of Table */