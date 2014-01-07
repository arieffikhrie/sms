<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('table');
		$this->load->helper('url');

		$this->load->database();
		
		$this->load->model('Department_model');
		$this->load->model('Items_model');
		$this->load->model('vendors_model');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['error'] = $this->session->flashdata('error');
		$this->data['title'] = "";
		$this->data['admin'] = $this->ion_auth->is_admin();
		
		if (!$this->ion_auth->is_admin()){
			$this->session->set_flashdata('message','You are not an admin.');
			redirect('main', 'refresh');
		}
	}

	public function users()	{
		$this->data['title'] = 'Users';
		$users = $this->ion_auth->users()->result();
		foreach ($users as $k => $user)
		{
			$users[$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}
		$department = $this->Department_model->department_getArray();
		$department[0] = '';
		$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'), array('data'=>'Name','class'=>'col-md-2'), array('data'=>'Department','class'=>'col-md-2'),array('data'=>'Email','class'=>'col-md-2'),array('data'=>'Phone','class'=>'col-md-2'),array('data'=>'Groups','class'=>'col-md-2'),array('data'=>'','class'=>'col-md-1'),array('data'=>'','class'=>'col-md-1'));
		
		$i = 1;
		foreach ( $users as $user ){
			$grouplist = '';
			foreach ($user->groups as $group){
				$grouplist .= '<span class="label label-default">'.$group->name.'</span> ';
			}
			$editBtn = '<a href="'.base_url().'admin/edit_user/'.$user->id.'" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>';
			$deleteBtn = '<a href="'.base_url().'admin/delete_user/'.$user->id.'" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>';
			$this->table->add_row($i,$user->name,$department[$user->department_id],$user->email,$user->phone,$grouplist,$editBtn,$deleteBtn);
			$i++;
		}
		$this->data['users'] = $this->table->generate();
		$this->load->view('header',$this->data);
		$this->load->view("users",$this->data);
		$this->load->view('footer',$this->data);
	}
	
	//create a new user
	function create_user(){
		$this->data['title'] = "Create User";

		//validate form input
		$this->form_validation->set_rules('username', 'Username', 'required,min_length[6],is_unique[users.username]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password_confirm', 'required');
		$this->form_validation->set_rules('department', 'Department', 'required|xss_clean');
		$this->form_validation->set_rules('staffid', 'staffid', 'required|xss_clean');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('username'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');
			$groups = $this->input->post('groups');
			$additional_data = array(
				'name' => $this->input->post('name'),
				'department_id'=> $this->input->post('department'),
				'phone' => $this->input->post('phone'),
				'staffID' => $this->input->post('staffid')
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data,$groups))
		{
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("admin/users", 'refresh');
		}
		else
		{
			$this->data['error'] = $this->ion_auth->errors();
			$this->data['message'] =  $this->session->flashdata('message');
			$groups=$this->ion_auth->groups()->result_array();
			$this->data['groups'] = $groups;
			$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
				'class' => 'form-control'
			);
			$this->data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name'),
				'class' => 'form-control'
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'form-control'
			);
			$this->data['department'] = array(
				'name' => 'department',
				'option' => $this->Department_model->department_getArray(),
				'value' => $this->form_validation->set_value('department'),
				'attr' => 'id ="category_id" class="form-control"'
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
				'class' => 'form-control'
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control'
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control'
			);
			$this->data['staffid'] = array(
				'name'  => 'staffid',
				'id'    => 'staffid',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('staffid'),
				'class' => 'form-control'
			);
			$this->load->view("header",$this->data);
			$this->load->view('register', $this->data);
			$this->load->view("footer",$this->data);
		}
	}
	
	function delete_user($id=false){
		$this->data['title'] = 'Delete User';
		$this->form_validation->set_rules('action', 'action', '');
		if ( $id !== false ){
			$this->data['user'] = $this->ion_auth->user($id)->row();
			if ($this->form_validation->run() === TRUE)
			{
				if ( $this->ion_auth->user()->row()->id !== $id )
					if ( $this->ion_auth->delete_user($id) ) $this->session->set_flashdata('message', "User Deleted");
					else $this->session->set_flashdata('error', "User Delete failed");
				else $this->session->set_flashdata('error', "User Delete failed");
				redirect("admin/users", 'refresh');
			}
			$this->load->view("header",$this->data);
			$this->load->view('delete_user', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/users', 'refresh');
		}
	}
	
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean');
		$this->form_validation->set_rules('department', 'Department', 'required|xss_clean');
		$this->form_validation->set_rules('staffid', 'staffid', 'required|xss_clean');
		$this->form_validation->set_rules('groups', 'Groups', 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			$data = array(
				'name' => $this->input->post('name'),
				'department_id'    => $this->input->post('department'),
				'phone'      => $this->input->post('phone'),
				'staffID' => $this->input->post('staffid')
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');
			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);
				$this->session->set_flashdata('message', "User Saved");
				redirect("admin/users", 'refresh');
			}
		}
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username',$user->username),
				'disabled' => 'disabled',
				'class' => 'form-control'
			);
			
			$this->data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('name',$user->name),
				'class' => 'form-control'
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email',$user->email),
				'disabled' => 'disabled',
				'class' => 'form-control'
			);
			$this->data['department'] = array(
				'name' => 'department',
				'option' => $this->Department_model->department_getArray(),
				'value' => $this->form_validation->set_value('department',$user->department_id),
				'attr' => 'id ="category_id" class="form-control"'
			);
			
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone',$user->phone),
				'class' => 'form-control'
			);
			$this->data['staffid'] = array(
				'name'  => 'staffid',
				'id'    => 'staffid',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('staffid',$user->staffID),
				'class' => 'form-control'
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control'
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control'
			);
			
		$this->load->view("header",$this->data);
		$this->load->view('edit_user', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function items(){
		$this->data['title'] = 'Items';
		$items = $this->Items_model->item_getAll();
		$categories = $this->Items_model->categories_getArray();

		$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'), array('data'=>'Category','class'=>'col-md-2'), array('data'=>'Item','class'=>'col-md-4'),array('data'=>'Quantity','class'=>'col-md-1'),array('data'=>'Unit','class'=>'col-md-1'),array('data'=>'Min','class'=>'col-md-1'),array('data'=>'','class'=>'col-md-1'),array('data'=>'','class'=>'col-md-1'));
		
		$i = 1;
		foreach ($items as $item){
			$editBtn = '<a href="'.base_url().'admin/edit_items/'.$item->item_id.'" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>';
			$deleteBtn = '<a href="'.base_url().'admin/delete_item/'.$item->item_id.'" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>';
			$this->table->add_row($i,$categories[$item->category_id],$item->item_name,$item->item_qty,$item->item_unit,$item->item_min_qty,$editBtn,$deleteBtn);
			$i++;
		}
		$this->data['items_table'] = $this->table->generate();
		$this->load->view("header",$this->data);
		$this->load->view('items', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function new_items(){
		$this->data['title'] = 'New item';
		$items = $this->Items_model->item_getAll();
		$categories = $this->Items_model->categories_getArray();

		$this->form_validation->set_rules('item_name', 'Item name', 'required');
		$this->form_validation->set_rules('category_id', 'Category', 'required');
		$this->form_validation->set_rules('item_qty', 'Item Quantity', 'required|numeric');
		$this->form_validation->set_rules('item_min_qty', 'Item Minimum Quantity', 'required|numeric');
		$this->form_validation->set_rules('item_unit', 'Item Unit', 'required');

		if ($this->form_validation->run() == true){
			$item = array(
				'item_name' => $this->input->post('item_name'),
				'category_id' => $this->input->post('category_id'),
				'item_qty' => $this->input->post('item_qty'),
				'item_min_qty' => $this->input->post('item_min_qty'),
				'item_unit' => $this->input->post('item_unit'),
			);
			
			$this->Items_model->insert_item($item);
			$this->session->set_flashdata('message','New item added');
			redirect('admin/items', 'refresh');
		}
		$this->data['item_name'] = array(
			'name' => 'item_name',
			'id' => 'item_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('item_name'),
			'class' => 'form-control'
		);
		$this->data['category_id'] = array(
			'name' => 'category_id',
			'option' => $this->Items_model->categories_getArray(),
			'value' => $this->form_validation->set_value('category_id'),
			'attr' => 'id ="category_id" class="form-control"'
		);
		$this->data['item_qty'] = array(
			'name' => 'item_qty',
			'id' => 'item_qty',
			'type' => 'text',
			'value' => $this->form_validation->set_value('item_qty'),
			'class' => 'form-control'
		); 
		$this->data['item_min_qty'] = array(
			'name' => 'item_min_qty',
			'id' => 'item_min_qty',
			'type' => 'text',
			'value' => $this->form_validation->set_value('item_min_qty'),
			'class' => 'form-control'
		); 
		$this->data['item_unit'] = array(
			'name' => 'item_unit',
			'id' => 'item_unit',
			'option' => array('PCS' => 'PCS', 'REAM' => 'REAM', 'BOXES' => 'BOXES'),
			'value' => $this->form_validation->set_value('item_unit'),
			'attr' => 'id ="item_unit" class="form-control"'
		);
		$this->load->view("header",$this->data);
		$this->load->view('new_items', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function delete_item($id=false){
		$this->data['title'] = 'Delete item';
		$this->form_validation->set_rules('action', 'action', '');
		if ( $id !== false ){
			$this->data['item'] = $this->Items_model->item_get($id);
			if ($this->form_validation->run() === TRUE)
			{
				if ( $this->Items_model->delete_item($id) ) $this->session->set_flashdata('message', "Item Deleted");
				else $this->session->set_flashdata('error', "Item delete failed");
				redirect("admin/items", 'refresh');
			}
			$this->load->view("header",$this->data);
			$this->load->view('delete_item', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/items', 'refresh');
		}
	}
	
	function edit_items($item_id = FALSE){
		$this->data['title'] = 'Edit Item';
		$this->form_validation->set_rules('item_name', 'Item name', 'required');
		$this->form_validation->set_rules('category_id', 'Category', 'required');
		$this->form_validation->set_rules('item_qty', 'Item Quantity', 'required|numeric');
		$this->form_validation->set_rules('item_min_qty', 'Item Minimum Quantity', 'required|numeric');
		$this->form_validation->set_rules('item_unit', 'Item Unit', 'required');
		if ($item_id !== FALSE){
			$item = $this->Items_model->item_get($item_id);
			if ( !$item ) {
				$this->session->set_flashdata('error','Item not exist');
				redirect('admin/items', 'refresh');
			}
			$itemUpdate = array(
				'item_name' => $this->input->post('item_name'),
				'category_id' => $this->input->post('category_id'),
				'item_qty' => $this->input->post('item_qty'),
				'item_min_qty' => $this->input->post('item_min_qty'),
				'item_unit' => $this->input->post('item_unit'),
			);
			if ($this->form_validation->run() === TRUE)
			{
				$this->Items_model->update_item($item_id,$itemUpdate);
				$this->session->set_flashdata('message','Item updated');
				redirect('admin/items', 'refresh');
			}
			$this->data['error'] = validation_errors();
			$this->data['item_name'] = array(
				'name' => 'item_name',
				'id' => 'item_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_name',$item->item_name),
				'class' => 'form-control'
			);
			$this->data['category_id'] = array(
				'name' => 'category_id',
				'option' => $this->Items_model->categories_getArray(),
				'value' => $this->form_validation->set_value('category_id',$item->category_id),
				'attr' => 'id ="category_id" class="form-control"'
			);
			$this->data['item_qty'] = array(
				'name' => 'item_qty',
				'id' => 'item_qty',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_qty',$item->item_qty),
				'class' => 'form-control'
			); 
			$this->data['item_min_qty'] = array(
				'name' => 'item_min_qty',
				'id' => 'item_min_qty',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_min_qty',$item->item_min_qty),
				'class' => 'form-control'
			); 
			$this->data['item_unit'] = array(
				'name' => 'item_unit',
				'id' => 'item_unit',
				'option' => array('PCS' => 'PCS', 'REAM' => 'REAM', 'BOXES' => 'BOXES'),
				'value' => $this->form_validation->set_value('item_unit',$item->item_unit),
				'attr' => 'id ="item_unit" class="form-control"'
			);
			$this->load->view("header",$this->data);
			$this->load->view('edit_items', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/items', 'refresh');
		}
	}
	
	function vendors(){
		$this->data['title'] = 'Vendors';
		$vendors = $this->vendors_model->vendors_get();
		$categories = $this->Items_model->categories_getArray();

		$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'),array('data'=>'Category','class'=>'col-md-2'),array('data'=>'Name','class'=>'col-md-2'),array('data'=>'Description','class'=>'col-md-2'),'Address','Telephone','Fax','Email','','');
		if ( $vendors !== FALSE){
			$i = 1;
			foreach ($vendors as $vendor){
				$categorylist = '';
				$vendorCategory = explode(',',$vendor->vendorCategory);
				foreach ($vendorCategory as $category){
					$categorylist .= '<span class="label label-default">'.$categories[$category].'</span> ';
				}
				$editBtn = '<a href="'.base_url().'admin/edit_vendor/'.$vendor->vendorID.'" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>';
				$deleteBtn = '<a href="'.base_url().'admin/delete_vendor/'.$vendor->vendorID.'" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>';
				$this->table->add_row($i,$categorylist,$vendor->vendorName,$vendor->vendorDescription,$vendor->vendorAddress,$vendor->vendorTelephone,$vendor->vendorFax,$vendor->vendorEmail,$editBtn,$deleteBtn);
				$i++;
			}
		} else {
			//$this->table->add_row(array('data'=>'No vendors','colspan'=>'10'));
		}
		
		$this->data['vendors_table'] = $this->table->generate();
		$this->load->view("header",$this->data);
		$this->load->view('vendors', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function new_vendor(){
		$categories = $this->Items_model->categories_getArray();

		$this->form_validation->set_rules('category[]', 'Category', '');
		$this->form_validation->set_rules('vendorName', 'Vendor Name', 'required');
		$this->form_validation->set_rules('vendorDescription', 'Vendor Description', '');
		$this->form_validation->set_rules('vendorAddress', 'Vendor Address', '');
		$this->form_validation->set_rules('vendorTelephone', 'Vendor Telephone', '');
		$this->form_validation->set_rules('vendorFax', 'Vendor Fax', '');
		$this->form_validation->set_rules('vendorEmail', 'Vendor Email', 'valid_email');
		if ($this->form_validation->run() == true){
			$vendor = array(
				'vendorCategory' => implode(',',$this->input->post('category')),
				'vendorName' => $this->input->post('vendorName'),
				'vendorDescription' => $this->input->post('vendorDescription'),
				'vendorAddress' => $this->input->post('vendorAddress'),
				'vendorTelephone' => $this->input->post('vendorTelephone'),
				'vendorFax' => $this->input->post('vendorFax'),
				'vendorEmail' => $this->input->post('vendorEmail'),
			);
			$this->vendors_model->insert_vendor($vendor);
			$this->session->set_flashdata('message','New vendor added');
			redirect('admin/vendors', 'refresh');
		}
		$this->data['category'] = array(
			'name' => 'category[]',
			'option' => $this->Items_model->categories_getArray(),
			'value' => $this->form_validation->set_value('category'),
			'attr' => 'id ="category" class="form-control multiselect"'
		);
		$this->data['vendorName'] = array(
			'name' => 'vendorName',
			'id' => 'vendorName',
			'type' => 'text',
			'value' => $this->form_validation->set_value('vendorName'),
			'class' => 'form-control'
		); 
		$this->data['vendorDescription'] = array(
			'name' => 'vendorDescription',
			'id' => 'vendorDescription',
			'value' => $this->form_validation->set_value('vendorDescription'),
			'rows' => '4',
			'class' => 'form-control'
		); 
		$this->data['vendorAddress'] = array(
			'name' => 'vendorAddress',
			'id' => 'vendorAddress',
			'value' => $this->form_validation->set_value('vendorAddress'),
			'rows' => '4',
			'class' => 'form-control'
		); 
		$this->data['vendorTelephone'] = array(
			'name' => 'vendorTelephone',
			'id' => 'vendorTelephone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('vendorTelephone'),
			'class' => 'form-control'
		); 
		$this->data['vendorFax'] = array(
			'name' => 'vendorFax',
			'id' => 'vendorFax',
			'type' => 'text',
			'value' => $this->form_validation->set_value('vendorFax'),
			'class' => 'form-control'
		); 
		$this->data['vendorEmail'] = array(
			'name' => 'vendorEmail',
			'id' => 'vendorEmail',
			'type' => 'text',
			'value' => $this->form_validation->set_value('vendorEmail'),
			'class' => 'form-control'
		); 
		$this->load->view("header",$this->data);
		$this->load->view('new_vendor', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function delete_vendor($id=false){
		$this->form_validation->set_rules('action', 'action', '');
		if ( $id !== false ){
			$this->data['vendor'] = $this->vendors_model->vendors_get($id);
			if ($this->form_validation->run() === TRUE)
			{
				if ( $this->vendors_model->delete_vendor($id) ) $this->session->set_flashdata('message', "Vendor Deleted");
				else $this->session->set_flashdata('error', "Vendor delete failed");
				redirect("admin/vendors", 'refresh');
			}
			$this->load->view("header",$this->data);
			$this->load->view('delete_vendor', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/vendors', 'refresh');
		}
	}
	
	function edit_vendor($id = false){
		$categories = $this->Items_model->categories_getArray();

		$this->form_validation->set_rules('category[]', 'Category', '');
		$this->form_validation->set_rules('vendorName', 'Vendor Name', 'required');
		$this->form_validation->set_rules('vendorDescription', 'Vendor Description', '');
		$this->form_validation->set_rules('vendorAddress', 'Vendor Address', '');
		$this->form_validation->set_rules('vendorTelephone', 'Vendor Telephone', '');
		$this->form_validation->set_rules('vendorFax', 'Vendor Fax', '');
		$this->form_validation->set_rules('vendorEmail', 'Vendor Email', 'valid_email');
		if ($id !== FALSE){
			$vendor = $this->vendors_model->vendors_get($id);
			if ( !$vendor ) {
				$this->session->set_flashdata('error','Vendor not exist');
				redirect('admin/vendors', 'refresh');
			}
			if ($this->form_validation->run() === TRUE)
			{
				$vendorUpdate = array(
					'vendorCategory' => implode(',',$this->input->post('category')),
					'vendorName' => $this->input->post('vendorName'),
					'vendorDescription' => $this->input->post('vendorDescription'),
					'vendorAddress' => $this->input->post('vendorAddress'),
					'vendorTelephone' => $this->input->post('vendorTelephone'),
					'vendorFax' => $this->input->post('vendorFax'),
					'vendorEmail' => $this->input->post('vendorEmail'),
				);
				$this->vendors_model->update_vendor($id,$vendorUpdate);
				$this->session->set_flashdata('message','Vendor updated');
				redirect('admin/vendors', 'refresh');
			}
			$this->data['category'] = array(
				'name' => 'category[]',
				'option' => $this->Items_model->categories_getArray(),
				'value' => $this->form_validation->set_value('category',explode(',',$vendor->vendorCategory)),
				'attr' => 'id ="category" class="form-control multiselect"'
			);
			$this->data['vendorName'] = array(
				'name' => 'vendorName',
				'id' => 'vendorName',
				'type' => 'text',
				'value' => $this->form_validation->set_value('vendorName',$vendor->vendorName),
				'class' => 'form-control'
			); 
			$this->data['vendorDescription'] = array(
				'name' => 'vendorDescription',
				'id' => 'vendorDescription',
				'value' => $this->form_validation->set_value('vendorDescription',$vendor->vendorDescription),
				'rows' => '4',
				'class' => 'form-control'
			); 
			$this->data['vendorAddress'] = array(
				'name' => 'vendorAddress',
				'id' => 'vendorAddress',
				'value' => $this->form_validation->set_value('vendorAddress',$vendor->vendorAddress),
				'rows' => '4',
				'class' => 'form-control'
			); 
			$this->data['vendorTelephone'] = array(
				'name' => 'vendorTelephone',
				'id' => 'vendorTelephone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('vendorTelephone',$vendor->vendorTelephone),
				'class' => 'form-control'
			); 
			$this->data['vendorFax'] = array(
				'name' => 'vendorFax',
				'id' => 'vendorFax',
				'type' => 'text',
				'value' => $this->form_validation->set_value('vendorFax',$vendor->vendorFax),
				'class' => 'form-control'
			); 
			$this->data['vendorEmail'] = array(
				'name' => 'vendorEmail',
				'id' => 'vendorEmail',
				'type' => 'text',
				'value' => $this->form_validation->set_value('vendorEmail',$vendor->vendorEmail),
				'class' => 'form-control'
			); 
			$this->load->view("header",$this->data);
			$this->load->view('edit_vendor', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/vendors', 'refresh');
		}
	}
	
	
	
	function departments(){
		$this->data['title'] = 'Departments';
		$departments = $this->Department_model->department_get();
		$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'),array('data'=>'Department','class'=>'col-md-5'),array('data'=>'Description','class'=>'col-md-6'),'','');
		if ( $departments !== FALSE){
			$i = 1;
			foreach ($departments as $department){
				$editBtn = '<a href="'.base_url().'admin/edit_department/'.$department->department_id.'" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>';
				$deleteBtn = '<a href="'.base_url().'admin/delete_department/'.$department->department_id.'" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>';
				$this->table->add_row($i,$department->department_name,$department->department_desc,$editBtn,$deleteBtn);
				$i++;
			}
		}
		
		$this->data['departments_table'] = $this->table->generate();
		$this->load->view("header",$this->data);
		$this->load->view('departments', $this->data);
		$this->load->view("footer",$this->data);
	}
	
	function new_department(){
		$this->data['title'] = 'Add Department';
		$this->form_validation->set_rules('department_name', 'Department Name', 'required');
		$this->form_validation->set_rules('department_desc', 'Department Description', '');
		if ($this->form_validation->run() == true){
			$vendor = array(
				'department_name' => $this->input->post('department_name'),
				'department_desc' => $this->input->post('department_desc'),
			);
			$this->Department_model->insert_department($vendor);
			$this->session->set_flashdata('message','New department added');
			redirect('admin/departments', 'refresh');
		}
		$this->data['department_name'] = array(
			'name' => 'department_name',
			'id' => 'department_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('department_name'),
			'class' => 'form-control'
		); 
		$this->data['department_desc'] = array(
			'name' => 'department_desc',
			'id' => 'department_desc',
			'value' => $this->form_validation->set_value('department_desc'),
			'rows' => '4',
			'class' => 'form-control'
		); 
		$this->load->view("header",$this->data);
		$this->load->view('new_department', $this->data);
		$this->load->view("footer",$this->data);
	}

	function delete_department($id=false){
		$this->data['title'] = 'Delete Department';
		$this->form_validation->set_rules('action', 'action', '');
		if ( $id !== false ){
			$this->data['department'] = $this->Department_model->department_get($id);
			if ( $this->data['department'] === FALSE ) redirect("admin/departments", 'refresh');
			if ($this->form_validation->run() === TRUE)
			{
				if ( $this->Department_model->delete_department($id) ) $this->session->set_flashdata('message', "Department Deleted");
				else $this->session->set_flashdata('error', "Department delete failed");
				redirect("admin/departments", 'refresh');
			}
			$this->load->view("header",$this->data);
			$this->load->view('delete_department', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/departments', 'refresh');
		}
	}
	
	function edit_department($id = false){
		$this->data['title'] = 'Edit Department';
		$this->form_validation->set_rules('department_name', 'Department Name', 'required');
		$this->form_validation->set_rules('department_desc', 'Department Description', '');
		if ($id !== FALSE){
			$department = $this->Department_model->department_get($id);
			if ( !$department ) {
				$this->session->set_flashdata('error','Department not exist');
				redirect('admin/departments', 'refresh');
			}
			if ($this->form_validation->run() === TRUE)
			{
				$departmentUpdate = array(
					'department_name' => $this->input->post('department_name'),
					'department_desc' => $this->input->post('department_desc'),
				);
				$this->Department_model->update_department($id,$departmentUpdate);
				$this->session->set_flashdata('message','Department updated');
				redirect('admin/departments', 'refresh');
			}
			$this->data['department_name'] = array(
				'name' => 'department_name',
				'id' => 'department_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('department_name',$department->department_name),
				'class' => 'form-control'
			); 
			$this->data['department_desc'] = array(
				'name' => 'department_desc',
				'id' => 'department_desc',
				'value' => $this->form_validation->set_value('department_desc',$department->department_desc),
				'rows' => '4',
				'class' => 'form-control'
			); 
			$this->load->view("header",$this->data);
			$this->load->view('edit_department', $this->data);
			$this->load->view("footer",$this->data);
		} else {
			redirect('admin/departments', 'refresh');
		}
	}
}