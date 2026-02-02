<?php

namespace Fuel\Migrations;

class Create_project_files
{
	public function up()
	{
		\DBUtil::create_table('project_files', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => '11'),
			'project_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'user_id' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'filename' => array('constraint' => '255', 'null' => false, 'type' => 'varchar'),
			'filepath' => array('constraint' => '500', 'null' => false, 'type' => 'varchar'),
			'filesize' => array('constraint' => '11', 'null' => false, 'type' => 'int'),
			'mimetype' => array('constraint' => '100', 'null' => true, 'type' => 'varchar'),
			'created_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => '11', 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('project_files');
	}
}