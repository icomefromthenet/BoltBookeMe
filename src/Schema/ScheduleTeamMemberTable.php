<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class ScheduleTeamMemberTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        $this->table->addOption('Used to group members into teams');

        $this->table->addColumn('team_id',           'integer',  ['notnull' => true,'comment' =>'Table Primary key and Fk to team table', 'unsigned' => true ]);
        $this->table->addColumn('membership_id',     'integer',  ['notnull' => true,'comment' =>'Table Primary key and Fk to membership table', 'unsigned' => true ]);
        $this->table->addColumn('registered_date',   'datetime',   ['notnull' => true, 'comment' =>'Date membership was created' ]);
        
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
        $this->table->setPrimaryKey(['membership_id','team_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sMemberTableName = $this->tablePrefix .'bm_schedule_membership';
        $sTeamTableName   = $this->tablePrefix .'bm_schedule_team';
       
        $this->table->addForeignKeyConstraint($sMemberTableName, ['membership_id'], ['membership_id'], [], null);
        $this->table->addForeignKeyConstraint($sTeamTableName, ['team_id'], ['team_id'], [], null);
       
    }
}
/* End of Table */