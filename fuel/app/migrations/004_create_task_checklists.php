<?php

namespace Fuel\Migrations;

class Create_task_checklists
{
	public function up()
	{
		\DBUtil::create_table('task_checklists', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => '11'),
			'task_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'title' => array('constraint' => '255', 'null' => false, 'type' => 'varchar'),
			'is_completed' => array('null' => false, 'type' => 'boolean', 'default' => 0),
			'sort_order' => array('constraint' => '11', 'null' => false, 'type' => 'int', 'default' => 0),
			'created_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('task_checklists');
	}
}