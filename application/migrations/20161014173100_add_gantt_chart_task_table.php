<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Migration_Add_gantt_chart_task_table extends CI_Migration {

  public function up() {
    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
        'auto_increment' => TRUE
      ),
      'card_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'gantt_ch_id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE
      ),
      'start_date' => array(
        'type'           => 'DATE'
      ),
      'end_date' => array(
        'type'           => 'DATE'
      ),
      'percentage' => array(
        'type'           => 'INT',
        'constraint'     => 3,
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
    $this->dbforge->create_table('GanttChartTask', TRUE);
    echo '<p>Migration Created : 20161014173100_add_gantt_chart_task_table</p>';
  }

  public function down() {
    $this->dbforge->drop_table('GanttChartTask');
    echo '<p>Migration Dropped : 20161014173100_add_gantt_chart_task_table</p>';
  }

}