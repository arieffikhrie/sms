<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model
{
	function __construct(){
        parent::__construct();
    }
	function get_email($group_id,$department_id = null){
		if ( $department_id == null ) $department = '';
		else $department = ' AND department_id = '.$department_id;
		$sql = "SELECT * FROM users_groups JOIN users ON users.id = users_groups.user_id WHERE group_id = ?".$department;
		$query = $this->db->query($sql,array($group_id));
		return $query->result();
	}
}