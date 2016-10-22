<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_project_member_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'project_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'user_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'status' => array(
        'type'           => 'INT',
        'constraint'     => 1,
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
    $this->dbforge->create_table('ProjectMember', TRUE);
    echo '<p>Migration Created : 20160927235300_add_project_member_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('ProjectMember');
    echo '<p>Migration Dropped : 20160927235300_add_project_member_table</p>';
  }

}