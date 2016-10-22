<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_title_to_card_table extends CI_Migration {

  function up() {
    $fields = array(
      'title' => array(
        'after' => 'type_id',
        'type' => 'VARCHAR',
        'constraint' => 32,
      )
    );
    $this->dbforge->add_column('Card', $fields);
    echo '<p>Migration Created : 20161003191300_add_title_to_card_table</p>';
  }

  function down() {
    $this->dbforge->drop_column('Card', 'title');
    echo '<p>Migration Dropped : 20161003191300_add_title_to_card_table</p>';
  }

}