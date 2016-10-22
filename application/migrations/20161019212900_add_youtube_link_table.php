<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_youtube_link_table extends CI_Migration {

  public function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'key' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 32
      ),
      'ref_type' => array(
        'type'           => 'VARCHAR',
        'constraint'     => 16
      ),
      'ref_id' => array(
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
    $this->dbforge->create_table('YoutubeLink', TRUE);
    echo '<p>Migration Created : 20161019212900_add_youtube_link_table</p>';
  }

  public function down() {
    $this->dbforge->drop_table('GanttChart');
    echo '<p>Migration Dropped : 20161019212900_add_youtube_link_table</p>';
  }
}