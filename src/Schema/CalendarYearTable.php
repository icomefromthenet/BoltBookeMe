<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;

class CalendarYearTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Calender table that store the next x years');
        
        $this->table->addColumn('y',             'smallint',    ['notnull' => true,'comment' =>'year where date occurs' ]);
        $this->table->addColumn('y_start',       'smallint',    ['notnull' => true,'comment' => '']);
        $this->table->addColumn('y_end',         'smallint',    ['notnull' => true,'comment' => '']);
        
        
    }
    
    
    protected function addVirtualColumns()
    {
        $this->table->addVirtualColumn('current_year','boolean', ['notnull' => true, 'comment' =>'If this calendar year is current' ,'default' => false ]);

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
        $this->table->setPrimaryKey(['y']);
    }
    
}
/* End of Table */