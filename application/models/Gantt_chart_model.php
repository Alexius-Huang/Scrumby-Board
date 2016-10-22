<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Gantt_chart_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_gantt_charts($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('GanttChart');
    return $query->result_array();
  }

  function get_gantt_chart($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('GanttChart');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_gantt_chart_tasks($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('GanttChartTask');
    return $query->result_array();
  }

  function get_gantt_chart_task($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('GanttChartTask');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_gantt_chart_tasks_by_gantt_ch_id($gantt_ch_id = '0') {
    if ($gantt_ch_id === '0') {
      return FALSE;
    } else {
      $this->db->where('gantt_ch_id', $gantt_ch_id);
      $this->db->order_by('id', 'asc');
      $query = $this->db->get('GanttChartTask');
      return $query->result_array();
    }
  }

  function get_gantt_chart_task_by_card_id($card_id = '0') {
    if ($card_id === '0') {
      return FALSE;
    } else {
      $this->db->where('card_id', $card_id);
      $this->db->limit(1);
      $query = $this->db->get('GanttChartTask');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_gantt_chart_by_project_id($project_id = '0') {
    if ($project_id === '0') {
      return FALSE;
    } else {
      $this->db->where('project_id', $project_id);
      $this->db->limit(1);
      $query = $this->db->get('GanttChart');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  /* CREATE GANTT CHART, GANTT CHART TASK METHOD */
  function create_gantt_chart($data) {
    $insert = array(
      'rows'       => $data['rows'],
      'project_id' => $data['project_id'],
      'created_at' => time(),
      'updated_at' => time()
    );
    $this->db->insert('GanttChart', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  function create_gantt_chart_task($data) {
    $insert = array(
      'card_id'     => $data['card_id'],
      'gantt_ch_id' => $data['gantt_ch_id'],
      'start_date'  => $data['start_date'],
      'end_date'    => $data['end_date'],
      'percentage'  => $data['percentage'],
      'created_at'  => time(),
      'updated_at'  => time()
    );
    $this->db->insert('GanttChartTask', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE GANTT CHART & GANTT CHART TASK METHOD */
  function update_gantt_chart($data, $id) {
    $update = array();
    if (isset($data['rows']))       { $update['rows']       = $data['rows'];       }
    if (isset($data['project_id'])) { $update['project_id'] = $data['project_id']; }

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('GanttChart', $update);
    }
  }

  function update_gantt_chart_task($data, $id) {
    $update = array();
    if (isset($data['card_id']))     { $update['card_id']     = $data['card_id'];     }
    if (isset($data['gantt_ch_id'])) { $update['gantt_ch_id'] = $data['gantt_ch_id']; }
    if (isset($data['start_date']))  { $update['start_date']  = $data['start_date'];  }
    if (isset($data['end_date']))    { $update['end_date']    = $data['end_date'];    }
    if (isset($data['percentage']))  { $update['percentage']  = $data['percentage'];  }
    
    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('GanttChartTask', $update);
    }
  }

  /* DELETE GANTT CHART, GANTT CHART TASK METHOD */
  function delete_gantt_chart($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('GanttChart');
  }

  function delete_gantt_chart_task($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('GanttChartTask');
  }

}