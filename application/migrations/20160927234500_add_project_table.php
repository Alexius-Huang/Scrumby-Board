<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_project_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'title' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 32
      ),
      'description' => array(
        'type'           => 'TEXT',
      ),
      'templates' => array(
        'type'           => 'INT',
        'constraint'     => 2,
        'unsigned'       => TRUE,
      ),
      'manager_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'created_at' => array(
        'type'           => 'INT',
        'constraint'     => 10,
        'unsigned'       => TRUE,
      ),
      'updated_at' => array(
        'type'           => 'INT',
        'constraint'     => 10,
        'unsigned'       => TRUE
      )
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('Project', TRUE);
    echo '<p>Migration Created : 20160927234500_add_project_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('Project');
    echo '<p>Migration Dropped : 20160927234500_add_project_table</p>';
  }

}