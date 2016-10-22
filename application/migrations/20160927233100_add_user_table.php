<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_user_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'email' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 255
      ),
      'password' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 255
      ),
      'username' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 32,
        'null'           => TRUE
      ),
      'status' => array(
        'type'           => 'INT',
        'constraint'     => 1,
        'unsigned'       => TRUE,
        'default'        => 1
      ),
      'last_signin' => array(
        'type'           => 'INT',
        'constraint'     => 10,
        'unsigned'       => TRUE,
      )
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('User', TRUE);
    echo '<p>Migration Created : 20160927233100_add_user_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('User');
    echo '<p>Migration Dropped : 20160927233100_add_user_table</p>';
  }

}