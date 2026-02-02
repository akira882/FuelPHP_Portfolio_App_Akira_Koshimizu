<?php

namespace Fuel\Migrations;

class Create_tasks
{
	public function up()
	{
		\DBUtil::create_table('tasks', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => '11'),
			'title' => array('constraint' => '255', 'null' => false, 'type' => 'varchar'),
			'content' => array('null' => true, 'type' => 'text'),
			'user_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'done' => array('null' => false, 'type' => 'boolean', 'default' => 0),
			'created_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('tasks');
	}
}