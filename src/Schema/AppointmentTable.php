<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class AppointmentTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Container for customer and booking');
        
        $this->table->addColumn('appointment_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        
        $this->table->addColumn('customer_id',   'integer',   ['notnull' => false,'comment' =>'Fk to the customer table', 'unsigned' => true ]);
        
        $this->table->addColumn('booking_id',   'integer',   ['notnull' => false,'comment' =>'Fk to the current booking', 'unsigned' => true ]);
        
        $this->table->addColumn('instructions',   'text',   ['notnull' => false,'comment' =>'Fk to the current booking' ]);
        
        $this->table->addColumn('status_code',    'string', ['notnull' => true, 'comment' => 'Status Code from status table', 'length' => 2]);
        
        $this->table->addColumn('appointment_no',    'string', ['notnull' => false, 'comment' => 'Appointment Products', 'length' => 25]);
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
         $this->table->addUniqueIndex(['appointment_no']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['appointment_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sBookingTableName = $this->tablePrefix .'bm_booking';
       
       $this->table->addForeignKeyConstraint($sBookingTableName, ['booking_id'], ['booking_id'], [], null);
       
       $sStatusTableName = $this->tablePrefix .'bm_appointment_status';
       
       $this->table->addForeignKeyConstraint($sStatusTableName, ['status_code'], ['status_code'], [], null);
       
       $sCustomerTableName = $this->tablePrefix .'bm_customer';
       
       $this->table->addForeignKeyConstraint($sCustomerTableName, ['customer_id'], ['customer_id'], [], null);
    }
}
/* End of Table */