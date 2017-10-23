<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class CalendarTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Calender table that stores each day as a row');
        
        $this->table->addColumn('calendar_date', 'date',        ['notnull' => true, 'comment' =>'date and table key']);
        $this->table->addColumn('y',             'smallint',    ['notnull' => false,'comment' =>'year where date occurs' ]);
        $this->table->addColumn('q',             'smallint',    ['notnull' => false,'comment' => 'quarter of the year date belongs']);
        $this->table->addColumn('m',             'smallint',    ['notnull' => false,'comment' => 'month of the year']);
        $this->table->addColumn('d',             'smallint',    ['notnull' => false,'comment' => 'numeric date part']);
        $this->table->addColumn('dw',            'smallint',    ['notnull' => false,'comment' => 'day number of the date in a week']);
        $this->table->addColumn('month_name',    'string',      ['notnull' => false,'comment' => 'text name of the month',"length" => 10]);
        $this->table->addColumn('day_name',      'string',      ['notnull' => false,'comment' => 'text name of the day',"length" => 10]);
        $this->table->addColumn('w',             'smallint',    ['notnull' => false,'comment' => 'week number in the year']);
        $this->table->addColumn('is_week_day',   'boolean',     ['notnull' => false,'comment' => 'true value if current date falls between monday-friday']);
        
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
        $this->table->setPrimaryKey(['calendar_date']);
    }
}
/* End of Table */