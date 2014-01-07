<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vendors_model extends CI_Model
{
	function __construct() {
        parent::__construct();
    }
	
	function vendors_get($id = false){
		$where = !$id? array() : array('vendorID' => $id);
		$query = $this->db->get_where('vendor',$where);
		return $query->num_rows > 0 ? !$id ? $query->result() : $query->result()[0] : false;
	}
	
	function insert_vendor($data){
		$insert = $this->db->insert('vendor',$data);
		if ( $insert == true ){
			return true;
		} else {
			return $this->db->last_query();
		}
	}
	
	function update_vendor($id,$data){
		$this->db->where('vendorID',$id);
		$this->db->update('vendor',$data);
	}
	
	function delete_vendor($id){
		$this->db->where('vendorID',$id);
		if ($this->db->delete('vendor')) return true;
		else return $this->db->last_query();
	}
}