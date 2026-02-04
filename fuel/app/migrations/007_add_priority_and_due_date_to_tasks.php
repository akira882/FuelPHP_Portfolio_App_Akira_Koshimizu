<?php

namespace Fuel\Migrations;

class Add_priority_and_due_date_to_tasks
{
	public function up()
	{
		\DBUtil::add_fields('tasks', array(
			'priority' => array(
				'constraint' => '1',
				'type' => 'int',
				'null' => false,
				'default' => 1,
				'after' => 'done'
			),
			'due_date' => array(
				'constraint' => '11',
				'type' => 'int',
				'null' => true,
				'unsigned' => true,
				'after' => 'priority'
			),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tasks', array(
			'priority',
			'due_date'
		));
	}
}
