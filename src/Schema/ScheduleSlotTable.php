<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class ScheduleSlotTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
      
        $this->table->addOption('comment','A Members schedule details');
        
        $this->table->addColumn('schedule_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        
        $this->table->addColumn('booking_id',    'integer',   ['notnull' => false,'comment' =>'FK to Booking Table', 'unsigned' => true ]);
        
        $this->table->addColumn('is_available',  'boolean',   ['default' => true,'comment' =>'Is this available']);
        $this->table->addColumn('is_excluded',   'boolean',   ['default' => true,'comment' =>'Is this slot not available']);
        $this->table->addColumn('is_override',   'boolean',   ['default' => true,'comment' =>'Is this available otherwise']);
        $this->table->addColumn('is_closed',     'boolean',   ['default' => false,'comment' =>'Is this slot closed']);
        
        $this->table->addColumn('slot_open',    'datetime',   ['notnull' => true, 'comment' =>'Start time of slot' ]);
        $this->table->addColumn('slot_close',   'datetime',   ['notnull' => true,'comment' =>'End time of slot' ]);
        
        
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
        $this->table->setPrimaryKey(['schedule_id','slot_close']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sScheduleTableName = $this->tablePrefix . 'bm_schedule';
       
       $this->table->addForeignKeyConstraint($sScheduleTableName, ['schedule_id'], ['schedule_id'], ['onDelete' => 'CASCADE'], null);
    
       $sBookingTableName = $this->tablePrefix . 'bm_booking';
       
       $this->table->addForeignKeyConstraint($sBookingTableName, ['booking_id'], ['booking_id'], ['onDelete' => 'SET NULL'], null);
   
        
    }
}
/* End of Table */