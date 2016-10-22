<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_card_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
      ),
      'type_id' => array(
        'type' => 'INT',
        'constraint' => 1,
        'unsigned' => TRUE
      ),
      'content' => array(
        'type' => 'TEXT',
      ),
      'order' => array(
        'type' => 'INT',
        'constraint' => 11,
        'null' => TRUE,
        'default' => 0
      ),
      'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE
      ),
      'template_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE
      ),
      'created_at' => array(
        'type' => 'INT',
        'constraint' => 10,
        'unsigned' => TRUE
      ),
      'updated_at' => array(
        'type' => 'INT',
        'constraint' => 10,
        'unsigned' => TRUE
      )
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('Card', TRUE);
    echo '<p>Migration Created : 20160928001900_add_card_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('Card');
    echo '<p>Migration Dropped : 20160928001900_add_card_table</p>';
  }

}