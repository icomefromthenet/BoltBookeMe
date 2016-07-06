<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class ScheduleTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
      
        $this->table->addOption('comment','A Members schedule details');
        
        $this->table->addColumn('schedule_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('timeslot_id',    'integer',  ['notnull' => true,'comment' =>'FK to Timeslot table', 'unsigned' => true ]);
        $this->table->addColumn('membership_id',  'integer',  ['notnull' => true,'comment' =>'FK to Members table', 'unsigned' => true ]);
     
     
        $this->table->addColumn('calendar_year',   'integer',   ['notnull' => true,'comment' =>'Schedule Calendar year', 'unsigned' => true ]);
        $this->table->addColumn('is_carryover',     'boolean',   ['default' => true,'comment' =>'If schedule copied into new year']);
        
        $this->table->addColumn('registered_date',  'datetime',   ['notnull' => true,'comment' =>'When the schedule was created' ]);
        $this->table->addColumn('close_date',       'datetime',   ['notnull' => false,'comment' =>'When the schedule stopped' ]);
        
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(['membership_id','calendar_year']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['schedule_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sTimeslotTableName = $this->tablePrefix .'bm_timeslot';
       $sMembersTableName  = $this->tablePrefix. 'bm_schedule_membership';
       
       $this->table->addForeignKeyConstraint($sTimeslotTableName, ['timeslot_id'], ['timeslot_id'], [], null);
       $this->table->addForeignKeyConstraint($sMembersTableName, ['membership_id'], ['membership_id'], [], null);
       
    }
}
/* End of Table */