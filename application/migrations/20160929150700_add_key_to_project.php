<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_key_to_project extends CI_Migration {

  public function up() {
    $fields = array(
      'key' => array(
        'after' => 'id',
        'type' => 'VARCHAR',
        'constraint' => 255
      )
    );
    $this->dbforge->add_column('Project', $fields);
    echo '<p>Migration Created : 20160929150700_add_key_to_project</p>';
  }

  public function down() {
    $this->dbforge->drop_column('Project', 'key');
    echo '<p>Migration Dropped : 20160929150700_add_key_to_project</p>';
  }

}