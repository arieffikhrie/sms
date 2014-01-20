<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ro_model extends CI_Model
{
	 function __construct()
    {
        parent::__construct();
    }
	
	function ro_getAll(){
		$query = $this->db->get('ro');
		return $query->result();
	}
	
	function ro_get($roID){
		$this->db->order_by("roDate", "desc");
		$query = $this->db->get_where('ro',$roID);
		if ( $query->num_rows() > 0 ){
			return $query->result()[0];
		}
		return false;
	}
	
	function ro_getByUser($userID){
		$this->db->order_by("roDate", "desc");
		$query = $this->db->get_where('ro',array('userID' => $userID));
		return $query->result();
	}
	
	function ro_getHOD($data = array()){
		$this->db->order_by("roDate", "desc");
		$query = $this->db->get_where('ro',$data);
		return $query->result();
	}
	
	function ro_insert($data){
		$insert = $this->db->insert('ro',$data);
		if ( $insert == true ){
			return $this->db->insert_id();
		} else {
			return $this->db->last_query();
		}
	}
	
	function ro_item_insert($data){
		$insert = $this->db->insert('roitems',$data);
		if ( $insert == true ){
			return true;
		} else {
			return $this->db->last_query();
		}
	}
	
	function ro_item_delete($id){
		$this->db->where('roID',$id);
		$this->db->delete('roItems');
	}
	
	function ro_item_get($roID){
		$query = $this->db->get_where('roitems',$roID);
		return $query->result();
	}
	function item_name($itemID){
		$itemID = array('item_id'=> $itemID);
		$query = $this->db->get_where('items',$itemID);
		if ( $query->num_rows() > 0 ){
			return $query->result()[0]->item_name;
		} else {
			return false;
		}		
	}
	function ro_update_status($roID,$data){
		$this->db->where('roID', $roID);
		$this->db->update('ro', $data); 
	}
	
	function ro_log_get($data){
		$this->db->order_by("status", "desc"); 
		$query = $this->db->get_where('ro_log', $data);
		return $query->num_rows() > 0 ? $query->result() : false;
	}
	
	function ro_log_insert($data){
		$insert = $this->db->insert('ro_log',$data);
		if ( $insert == true ){
			return true;
		} else {
			return $this->db->last_query();
		}
	}
	
	function ro_log_update($data,$id){
		$this->db->where('ro_log_id',$id);
		$this->db->update('ro_log',$data);
	}
}