<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Change_content_from_type_text_to_varchar_in_card_table extends CI_Migration {
  
  function up() {
    $fields = array(
      'content' => array(
        'name' => 'content',
        'type' => 'VARCHAR',
        'constraint' => 255
      )
    );
    $this->dbforge->modify_column('Card', $fields);
    echo '<p>Migration Created : 20161003190800_change_content_from_type_text_to_varchar_in_card_table</p>';
  }

  function down() {
    $fields = array(
      'content' => array(
        'name' => 'content',
        'type' => 'TEXT'
      )
    );
    $this->dbforge->modify_column('Card', $fields);
    echo '<p>Migration Dropped : 20161003190800_change_content_from_type_text_to_varchar_in_card_table</p>';
  }

}