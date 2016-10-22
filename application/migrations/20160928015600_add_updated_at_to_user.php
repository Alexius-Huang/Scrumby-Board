<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_updated_at_to_user extends CI_Migration {

  function up() {
    $fields = array(
      'updated_at' => array(
        'after'      => 'last_signin',
        'type'       => 'INT',
        'constraint' => 10,
        'unsigned'   => TRUE
      )
    );
    $this->dbforge->add_column('User', $fields);
    echo '<p>Migration Created : 20160928015600_add_updated_at_to_user</p>';
  }

  function down() {
    $this->dbforge->drop_column('User', 'updated_at');
    echo '<p>Migration Dropped : 20160928015600_add_updated_at_to_user</p>';
  }

}