<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_add_gantt_chart_table extends CI_Migration {

  public function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'rows' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'default'        => 0
      ),
      'project_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
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
    $this->dbforge->create_table('GanttChart', TRUE);
    echo '<p>Migration Created : 20161014172500_add_gantt_chart_table</p>';
  }

  public function down() {
    $this->dbforge->drop_table('GanttChart');
    echo '<p>Migration Dropped : 20161014172500_add_gantt_chart_table</p>';
  }

}