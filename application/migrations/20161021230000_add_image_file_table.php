<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_image_file_table extends CI_Migration {

  function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'key' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 255
      ),
      'ref_type' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 16
      ),
      'ref_id' => array(
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
    $this->dbforge->create_table('ImageFile', TRUE);
    echo '<p>Migration Created : 20161021230000_add_image_file_table</p>';
  }

  function down() {
    $this->dbforge->drop_table('ImageFile');
    echo '<p>Migration Dropped : 20161021230000_add_image_file_table</p>';
  }

}