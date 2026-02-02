<?php

namespace Fuel\Migrations;

class Add_project_id_to_tasks
{
	public function up()
	{
		\DBUtil::add_fields('tasks', array(
			'project_id' => array('constraint' => '11', 'null' => true, 'type' => 'int'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tasks', array(
			'project_id'
		));
	}
}