<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items_model extends CI_Model
{
	 function __construct()
    {
        parent::__construct();
    }
	
	function item_getAll(){
		$query = $this->db->get('items');
		return $query->result();
	}
	
	function item_get($data){
		$query = $this->db->get_where('items',array('item_id'=>$data));
		return ( $query->num_rows() > 0 ) ? $query->result()[0] : false;
	}
	
	function item_byCategory($categoryId){
		$query = $this->db->get_where('items',array('category_id'=>$categoryId));
		return $query->result();
	}
	
	function item_getArray(){
		$query = $this->db->get('items');
		$array = Array();
		foreach ($query->result() as $row ){
			$array[$row->item_id] = $row->item_name;
		}

		return $array;
	}
	
	function insert_item($data){
		$insert = $this->db->insert('items',$data);
		if ( $insert == true ){
			return true;
		} else {
			return $this->db->last_query();
		}
	}
	
	function update_item($id,$data){
		$this->db->where('item_id',$id);
		$this->db->update('items',$data);
	}
	
	function update_item_qty($qty,$id){
		$this->db->set('item_qty', 'item_qty-'.$qty,FALSE);
		$this->db->where('item_id',$id);
		$this->db->update('items');
	}
	
	function delete_item($id){
		$this->db->where('item_id',$id);
		if ($this->db->delete('items')) return true;
		else return $this->db->last_query();
	}
	
	function categories(){
		$query = $this->db->get('categories');
		return $query->result();
	}
	
	function categories_getArray(){
		$query = $this->db->get('categories');
		$array = Array();
		foreach ($query->result() as $row ){
			$array[$row->category_id] = $row->category_name;
		}
		return $array;
	}
	
	function get_itemunit($itemid){
		$query = $this->db->get_where('items',array('item_id'=>$itemid));
		return $query->result()[0]->item_unit;
	}
}