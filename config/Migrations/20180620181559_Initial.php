<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table('dailytasks')
            ->addColumn('department', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('date_time', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('caption', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('detail', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('work_time_h', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => true,
            ])
            ->addColumn('work_time_m', 'integer', [
                'default' => null,
                'limit' => 3,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();

        $this->table('departments')
            ->addColumn('name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('dep_index', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('is_sub', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('parent', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $this->table('messages')
            ->addColumn('department', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('detail', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('date_time', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();

        $this->table('stores')
            ->addColumn('password', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('area', 'integer', [
                'default' => '1',
                'limit' => 11,
                'null' => false,
            ])
            ->create();

        $this->table('tasks')
            ->addColumn('caption', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('detail', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('situation', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('update_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('update_date_time', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('update_user', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('department', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created_date_time', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('due_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('author', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('worker', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('completed', 'tinyinteger', [
                'default' => '0',
                'limit' => 1,
                'null' => true,
            ])
            ->addColumn('completed_user', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('priority', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('user_id', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('store_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();

        $this->table('users')
            ->addColumn('role', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('require_password', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('department', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('password', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('memo', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('store', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();
    }

    public function down()
    {
        $this->dropTable('dailytasks');
        $this->dropTable('departments');
        $this->dropTable('messages');
        $this->dropTable('stores');
        $this->dropTable('tasks');
        $this->dropTable('users');
    }
}
