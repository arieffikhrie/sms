<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Department_model extends CI_Model
{
	 function __construct()
    {
        parent::__construct();
    }
	
	function department_getAll(){
		$query = $this->db->get('departments');
		return $query->result();
	}
	
	function department_get($id = false){
		$where = !$id? array() : array('department_id' => $id);
		$query = $this->db->get_where('departments',$where);
		return $query->num_rows > 0 ? !$id ? $query->result() : $query->result()[0] : false;
	}
	
	function department_getArray(){
		$query = $this->db->get('departments');
		$array = Array();
		foreach ($query->result() as $row ){
			$array[$row->department_id] = $row->department_name;
		}

		return $array;
	}
	
	function insert_department($data){
		$insert = $this->db->insert('departments',$data);
		if ( $insert == true ){
			return true;
		} else {
			return $this->db->last_query();
		}
	}
	
	function update_department($id,$data){
		$this->db->where('department_id',$id);
		$this->db->update('departments',$data);
	}
	
	function delete_department($id){
		$this->db->where('department_id',$id);
		if ($this->db->delete('departments')) return true;
		else return $this->db->last_query();
	}
}