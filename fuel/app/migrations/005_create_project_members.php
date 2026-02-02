<?php

namespace Fuel\Migrations;

class Create_project_members
{
	public function up()
	{
		\DBUtil::create_table('project_members', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => '11'),
			'project_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'user_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'role' => array('constraint' => '50', 'null' => false, 'type' => 'varchar', 'default' => 'member'),
			'created_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));

		\DBUtil::create_index('project_members', array('project_id', 'user_id'), 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('project_members');
	}
}