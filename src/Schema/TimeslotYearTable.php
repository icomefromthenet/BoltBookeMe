<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class TimeslotYearTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        $this->table->addOption('comment','the timeslots for a given year');
        
        $this->table->addColumn('timeslot_year_id',   'integer',  ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('timeslot_id',       'integer',   ['notnull' => true, 'comment' =>'FK to slot table', 'unsigned' => true ]);
        
        $this->table->addColumn('y',       'smallint',   ['notnull' => false, 'comment' =>'year where date occurs', 'unsigned' => true ]);
        $this->table->addColumn('m',       'smallint',   ['notnull' => false, 'comment' =>'month of the year', 'unsigned' => true ]);
        $this->table->addColumn('d',       'smallint',   ['notnull' => false, 'comment' =>'numeric date part', 'unsigned' => true ]);
        $this->table->addColumn('dw',      'smallint',   ['notnull' => false, 'comment' =>'day number of the date in a week', 'unsigned' => true ]);
        $this->table->addColumn('w',       'smallint',   ['notnull' => false, 'comment' =>'week number in the year', 'unsigned' => true ]);
            
    
        $this->table->addColumn('open_minute',       'integer',   ['notnull' => true, 'comment' =>'Closing Minute component', 'unsigned' => true ]);
        $this->table->addColumn('close_minute',      'integer',   ['notnull' => true, 'comment' =>'Closing Minute component', 'unsigned' => true ]);
        
        $this->table->addColumn('closing_slot',      'datetime',  ['notnull' => true, 'comment' =>'The closing slot time']);
        $this->table->addColumn('opening_slot',      'datetime',  ['notnull' => true, 'comment' =>'The opening slot time']);
        
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(['timeslot_id','closing_slot']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['timeslot_year_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sTimeslotTableName = $this->tablePrefix .'bm_timeslot';
       
       $this->table->addForeignKeyConstraint($sTimeslotTableName, ['timeslot_id'], ['timeslot_id'], [], null);
       
    }
}
/* End of Table */