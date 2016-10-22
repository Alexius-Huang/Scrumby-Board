<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_template_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'name' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 32,
      ),
      'order' => array(
        'type'           => 'INT',
        'constraint'     => 2,
        'unsigned'       => TRUE
      ),
      'project_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'created_at' => array(
        'type'           => 'INT',
        'constraint'     => 10,
        'unsigned'       => TRUE
      ),
      'updated_at' => array(
        'type'           => 'INT',
        'constraint'     => 10,
        'unsigned'       => TRUE
      )
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('Template', TRUE);
    echo '<p>Migration Created : 20160928000800_add_template_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('Template');
    echo '<p>Migration Dropped : 20160928000800_add_template_table</p>';
  }

}