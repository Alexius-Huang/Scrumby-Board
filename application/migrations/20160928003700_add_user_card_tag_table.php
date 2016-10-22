<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_user_card_tag_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE
      ),
      'card_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
      ),
      'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE
      )
    ));
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('UserCardTag', TRUE);
    echo '<p>Migration Created : 20160928003700_add_user_card_tag_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('UserCardTag');
    echo '<p>Migration Dropped : 20160928003700_add_user_card_tag_table</p>';
  }

}