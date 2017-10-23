<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class CustomerTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain details on appointment customers');
        
        $this->table->addColumn('customer_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        $this->table->addColumn('created_on',    'datetime',   ['notnull' => true,'comment' =>'Date customer was created' ]);
        $this->table->addColumn('updated_on',    'datetime',   ['notnull' => true,'comment' =>'Date last customer update' ]);
        
        $this->table->addColumn('first_name',    'string',   ['notnull' => true,  'comment' =>'Member first Name', 'length' => 100 ]);
        $this->table->addColumn('last_name',     'string',   ['notnull' => false, 'comment' =>'Member surname Name', 'length' => 100 ]);        
        $this->table->addColumn('email',         'string',   ['notnull' => false, 'comment' =>'Member email', 'length' => 100 ]);
        $this->table->addColumn('mobile',        'string',   ['notnull' => false, 'comment' =>'Member mobile', 'length' => 20 ]);
        $this->table->addColumn('landline',      'string',   ['notnull' => false, 'comment' =>'Member landline', 'length' => 20 ]);
        $this->table->addColumn('address_one',   'string',   ['notnull' => false, 'comment' =>'Member address ', 'length' => 100 ]);
        $this->table->addColumn('address_two',   'string',   ['notnull' => false, 'comment' =>'Member address', 'length' => 100 ]);
        $this->table->addColumn('company_name',  'string',   ['notnull' => false, 'comment' =>'A Company name', 'length' => 100 ]);
        
        
        
        
        
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
        $this->table->setPrimaryKey(['customer_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       
    }
}
/* End of Table */