<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('table');

		$this->load->database();
		
		$this->load->model('ro_model');
		$this->load->model('Items_model');
		$this->load->model('Department_model');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['error'] = $this->session->flashdata('error');
		$this->data['title'] = "";
		$this->data['status'] = Array('Rejected','Requested','Released','Submitted','Approved','Collected');
		$this->data['admin'] = $this->ion_auth->is_admin();
	}

	public function index()
	{
		$this->data['title'] = "Stationery Management System";
		
		if (!$this->ion_auth->logged_in()){
			redirect('main/login', 'refresh');
		} else {
			$this->load->view('header',$this->data);
		}
		
		$user = $this->ion_auth->user()->row();
		$status = $this->data['status'];
		$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
		$this->table->set_template($tmpl);
		$this->data['hrm']		= FALSE;
		$this->data['admin']	= FALSE;
		$this->data['hod']		= FALSE;
		$this->data['members']	= FALSE;
		
		if ($this->ion_auth->in_group('approver')){
			$this->data['hrm'] = TRUE;
			$ro = $this->ro_model->ro_getHOD();
			$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'),array('data'=>'Department','class'=>'col-md-3'), array('data'=>'Request Description','class'=>'col-md-4'), array('data'=>'Date Request','class'=>'col-md-2'), array('data'=>'Status','class'=>'col-md-2'));
			if ( count($ro) > 0 ){
				$i = 1;
				foreach ( $ro as $row ){
					$department = $this->Department_model->department_get($row->departmentID);
					$this->table->add_row($i,$department->department_name,anchor('main/view_request/'.$row->roID,$row->roDesc,''),date('d/m/Y', strtotime($row->roDate)),$status[$row->status]);
					$i++;
				}
			} else {
				//$this->table->add_row( array('data'=>'No request','colspan'=>'4'));
			}
			$this->data['hrm_table'] = $this->table->generate();
			$this->table->clear();
		}
		if ($this->ion_auth->is_admin()){
			$this->data['admin'] = TRUE;
			$ro = $this->ro_model->ro_getHOD();
			$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'),array('data'=>'Department','class'=>'col-md-3'), array('data'=>'Request Description','class'=>'col-md-4'), array('data'=>'Date Request','class'=>'col-md-2'), array('data'=>'Status','class'=>'col-md-2'));
			if ( count($ro) > 0 ){
				$i = 1;
				foreach ( $ro as $row ){
					$department = $this->Department_model->department_get($row->departmentID);
					$this->table->add_row($i,$department->department_name,anchor('main/view_request/'.$row->roID,$row->roDesc,''),date('d/m/Y', strtotime($row->roDate)),$status[$row->status]);
					$i++;
				}
			} else {
				//$this->table->add_row( array('data'=>'No request','colspan'=>'4'));
			}
			$this->data['admin_table'] = $this->table->generate();
			$this->table->clear();
		}
		if($this->ion_auth->in_group('hod')){
			$this->data['hod'] = TRUE;
			$hodArr = Array('departmentID' => $user->department_id);
			$ro = $this->ro_model->ro_getHOD($hodArr);
			$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'), array('data'=>'Request Description','class'=>'col-md-7'), array('data'=>'Date Request','class'=>'col-md-2'), array('data'=>'Status','class'=>'col-md-2'));
			if ( count($ro) > 0 ){
				$i = 1;
				foreach ( $ro as $row ){
					$this->table->add_row($i,anchor('main/view_request/'.$row->roID,$row->roDesc,''),date('d/m/Y', strtotime($row->roDate)),$status[$row->status]);
					$i++;
				}
			} else {
				//$this->table->add_row( array('data'=>'No request','colspan'=>'4'));
			}
			$this->data['hod_table'] = $this->table->generate();
			$this->table->clear();
		}
		if($this->ion_auth->in_group('requestor')){
			$this->data['members'] = TRUE;
			$ro = $this->ro_model->ro_getByUser($this->ion_auth->get_user_id());
			$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'), array('data'=>'Request Description','class'=>'col-md-7'), array('data'=>'Date Request','class'=>'col-md-2'), array('data'=>'Status','class'=>'col-md-2'));
			if ( count($ro) > 0 ){
				$i = 1;
				foreach ( $ro as $row ){
					$this->table->add_row($i,anchor('main/view_request/'.$row->roID,$row->roDesc,''),date('d/m/Y', strtotime($row->roDate)),$status[$row->status]);
					$i++;
				}
			} else {
				//$this->table->add_row( array('data'=>'No request','colspan'=>'4'));
			}
			$this->data['members_table'] = $this->table->generate();
			$this->table->clear();
		}
		$this->load->view('main',$this->data);
		$this->load->view('footer',$this->data);
	}
	
	
	public function new_request(){
		if (!$this->ion_auth->logged_in()){
			redirect('main/login', 'refresh');
		} elseif(!$this->ion_auth->in_group('requestor')){
			$this->session->set_flashdata('error', 'You are not a requestor');
			redirect('main', 'refresh');
		} else {
			$this->data['title'] = 'New RO';
			$this->load->library(array('form_validation','upload'));
			$this->form_validation->set_rules('roDesc', 'RO Description', 'required');
			$this->form_validation->set_rules('roJustification', 'RO Justification', 'required');
			$this->form_validation->set_rules('remark', 'Remark', '');
			$this->form_validation->set_rules('department', 'department', '');
			$this->form_validation->set_rules('qty[]', 'Item quantity', 'required');
			$this->form_validation->set_rules('itemid[]', 'Item', 'required');
			$fileURL = '';
			if ($this->form_validation->run() == true){
				if ($_FILES && $_FILES['userFile']['name'] !== "") {
					$config =  array(
					  'upload_path'     => "./files/",
					  'allowed_types'   => "pdf|doc|docx",
					  'overwrite'       => TRUE,
					  'max_size'        => "1024*15",
					  'max_height'      => "",
					  'max_width'       => "",
					  'remove_space'	=> TRUE,
					  'encrypt_name'	=> TRUE
					);
					$this->load->library('upload');
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('userFile')) {
						$this->session->set_flashdata('error', $this->upload->display_errors('',''));
					}
					$file_info = $this->upload->data();
					$fileURL = $file_info['file_name'];
				}
				$ro = Array(
					'roDesc' => $this->input->post('roDesc'),
					'roJustification' => $this->input->post('roJustification'),
					'userID'		=> $this->ion_auth->get_user_id(),
					'departmentID' => $this->ion_auth->user()->row()->department_id,
					'fileUrl' => $fileURL,
					'remark' => $this->input->post('remark')
				);
				$roId = $this->ro_model->ro_insert($ro);
				$items = $this->input->post('itemid');
				$qty = $this->input->post('qty');
				for ( $i = 0; $i< count($items); $i++ ){
					$item = Array(
						'roID' => $roId,
						'itemID' => $items[$i],
						'qty' => $qty[$i]
 					);
					$this->ro_model->ro_item_insert($item);
				}
				$log = Array(
					'roID' => $roId,
					'user_id' => $ro['userID'],
					'status' => '1',
					'remark' => $ro['remark']
				);
				$this->ro_model->ro_log_insert($log);
				$this->session->set_flashdata('message', 'Pending Department Approval');
				$emailData = array(
					'roID' => $roId,
					'user_id' => $ro['userID'],
					'status' => $this->data['status'][1],
					'name' => $this->ion_auth->user()->row()->name
				);
				$emailList = $this->get_email($ro['userID'],TRUE);
				$this->notification_email($emailData,$emailList);
				redirect('main', 'refresh');				
			} else {
				$this->data['roDesc'] = array(
					'name' => 'roDesc',
					'id' => 'roDesc',
					'type' => 'text',
					'value' => $this->form_validation->set_value('roDesc'),
					'class' => 'form-control'
				);
				$this->data['roJustification'] = array(
					'name' => 'roJustification',
					'id' => 'roJustification',
					'type' => 'text',
					'value' => $this->form_validation->set_value('roJustification'),
					'class' => 'form-control'
				);
				$this->data['addRow'] = array(
					'name' => 'addRow',
					'id' => 'addRow',
					'value' => 'Add Row'
				);
				$this->data['remark'] = array(
					'name' => 'remark',
					'id' => 'remark',
					'class' => 'form-control',
					'value' => $this->form_validation->set_value('remark'),
					'rows' => '4'
				);
				$this->data['departmentOption'] = $this->Department_model->department_getArray();
				$this->data['departmentValue'] = $this->ion_auth->user()->row()->department_id;
				$this->table->set_heading(array('data'=>'Category','class'=>'col-md-3'), array('data'=>'Item','class'=>'col-md-5'), array('data'=>'Quantity','class'=>'col-md-1 qty'), array('data'=>'Unit','class'=>'col-md-2'),array('data'=>'','class'=>'col-md-1'));
				$categories[''] = "--Select--";
				foreach ($this->Items_model->categories() as $row ){
					$categories[$row->category_id] = $row->category_name;
				}
				$this->table->add_row(form_dropdown('categoryid[]',$categories,'0','id="category" class="form-control"'), form_dropdown('itemid[]',array(''=>'--Select--'),'0','id="item" class="form-control"'), form_input(array('name'=>'qty[]','id'=>'qty', 'class'=>'form-control','min'=>'0', 'step'=>'1', 'data-bind'=>'value:replyNumber')),form_input(array('name'=>'unit','id'=>'unit', 'class'=>'form-control','disabled'=>'disabled')));
				$tmpl = array ( 'table_open'  => '<table class="table" id="items">' );
				$this->table->set_template($tmpl);
				$this->data['rowItem'] = $this->table->generate();
				$this->load->view('header',$this->data);
				$this->load->view('new_request',$this->data);
				$this->load->view('footer',$this->data);
			}
		}
	}
	
	public function get_item(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
		$categoryId = $this->input->post('categoryid');
		$data = '<option value="">--Select--</0>';
		if ($categoryId != 0 ){
			$item = $this->Items_model->item_byCategory($categoryId);
			foreach($item as $row){
				$data .= '<option value="'.$row->item_id.'">'.$row->item_name.'</option>\n';
			}
		}
		echo $data;
	}
	
	public function get_item_unit(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
		$itemid = $this->input->post('itemid');
		if ( $itemid != 0 ){
			echo $this->Items_model->get_itemunit($itemid);
		}
	}
	
	public function view_request($roID=NULL){
		if (!$this->ion_auth->logged_in()){
			redirect('main/login', 'refresh');
		} else{
			$this->data['title'] = "RO";
			$status = $this->data['status'];
			$this->load->view('header',$this->data);
			if($roID != NULL){
				$roArr = Array('roID' => $roID);
				$this->data['ro'] = $this->ro_model->ro_get($roArr);
				$items= $this->ro_model->ro_item_get($roArr);
				$tmpl = array ( 'table_open'  => '<table class="table table-hover datatable">' );
				$this->table->set_template($tmpl);
				$chkbox_all = array(
					'name' => 'select_all',
					'id' => 'select_all',
					'value' => 'true',
					'checked' => TRUE
				);
				$this->table->set_heading(array('data'=>'No','class'=>'col-md-1'),'Item Name', array('data'=>'Quantity','class'=>'col-md-1'),array('data'=>form_checkbox($chkbox_all),'class'=>'col-md-1'));
				if ( count($items) > 0 ){
					$i = 1;
					foreach ( $items as $item ){
						$itemID = array(
							'name' => 'itemID[]',
							'type' => 'hidden',
							'value' => $item->itemID,
							'class' => 'form-control'
						);
						$itemQty = array(
							'name' => 'itemQty[]',
							'id' => 'itemQty',
							'type' => 'text',
							'value' => $item->qty,
							'class' => 'form-control'
						);
						$item_selected = array(
							'name' => 'item_selected[]',
							'class' => 'item_selected',
							'value' => '1',
							'checked' => TRUE
						);
						$hidden_item_selected = array(
							'name' => 'item_selected[]',
							'class' => 'item_selected',
							'value' => '0',
							'type' => 'hidden'
						);
						$this->table->add_row($i.form_input($itemID),$this->ro_model->item_name($item->itemID),form_input($itemQty),form_checkbox($item_selected).form_input($hidden_item_selected));
						$i++;
					}
					$this->data['table'] = $this->table->generate();
				} else {
					$this->data['table'] = "No items";
				}
				$this->table->clear();
				$this->table->set_heading('Status','User','Date', 'Remark');
				$ro_log = $this->ro_model->ro_log_get($roArr);
				if ( $ro_log !== false ){
					foreach ( $ro_log as $row ){
						$this->table->add_row($status[$row->status],$this->ion_auth->user($row->user_id)->row()->username,date('d/m/Y', strtotime($row->date)),$row->remark);
					}
				}
				$this->data['form_open'] = form_open("main/request_update");
				$this->data['form_close'] = form_close();
				$this->data['log_table'] = $this->table->generate();
				$this->data['remark'] = array(
					'name' => 'remark',
					'id' => 'vendorAddress',
					'value' => $this->form_validation->set_value('remark'),
					'rows' => '4',
					'class' => 'form-control'
				); 
				$this->data['rejectBtn'] = array(
					'name' => 'submitForm',
					'id' => 'submitForm',
					'value' => 'reject',
					'type' => 'submit',
					'content' => 'Reject',
					'class' => 'btn btn-default'
				);
				$this->load->view('view_request',$this->data);
				if ( $this->ion_auth->in_group('hod') && $this->data['ro']->status == 1){
					$this->data['updateBtn'] = array(
						'name' => 'submitForm',
						'id' => 'submitForm',
						'value' => 'release',
						'type' => 'submit',
						'content' => 'Release',
						'class' => 'btn btn-default'
					);
					$this->load->view('request_update',$this->data);
				}
				if ( $this->ion_auth->in_group('admin') && $this->data['ro']->status == 2){
					$this->data['updateBtn'] = array(
						'name' => 'submitForm',
						'id' => 'submitForm',
						'value' => 'submit',
						'type' => 'submit',
						'content' => 'Submit',
						'class' => 'btn btn-default'
					);
					$this->data['rejectBtn'] = array(
						'name' => 'submitForm',
						'id' => 'submitForm',
						'value' => 'reject',
						'type' => 'submit',
						'content' => 'Not Approve',
						'class' => 'cell-hide'
					);
					$this->load->view('request_update',$this->data);
				}
				if ( $this->ion_auth->in_group('approver') && $this->data['ro']->status == 3){
					$this->data['rejectBtn'] = array(
						'name' => 'submitForm',
						'id' => 'submitForm',
						'value' => 'reject',
						'type' => 'submit',
						'content' => 'Not Approve',
						'class' => 'btn btn-default'
					);
					$this->data['updateBtn'] = array(
						'name' => 'submitForm',
						'id' => 'submitForm',
						'value' => 'approve',
						'type' => 'submit',
						'content' => 'Approve',
						'class' => 'btn btn-default'
					);
					$this->load->view('request_update',$this->data);
				}
			} else {
				redirect('main', 'refresh');
			}
			$this->load->view('view_request2',$this->data);
			$this->load->view('footer',$this->data);
		}
	}
	
	public function request_update(){
		$this->form_validation->set_rules('roID', 'RO ID', 'required');
		if ($this->form_validation->run() == true)
		{
			$itemSelected = $this->input->post('item_selected');
			$removeArr = array();
			for ( $i = 0 ; $i < count($itemSelected); $i++ ){
				if ( $itemSelected[$i] == 1 ){
					$removeArr[] = $i+1;
				}
			}
			foreach ( $removeArr as $i ){
				unset($itemSelected[$i]);
			}
			$itemSelected = array_values($itemSelected);
			$roID = $this->input->post('roID');
			$items = $this->input->post('itemID');
			$qty = $this->input->post('itemQty');
			$this->ro_model->ro_item_delete($roID);
			for ( $i = 0; $i< count($items); $i++ ){
				$item = Array(
					'roID' => $roID,
					'itemID' => $items[$i],
					'qty' => $qty[$i]
				);
				if ( $itemSelected[$i] == 1 ){
					$this->ro_model->ro_item_insert($item);
				}
			}
			$ro = $this->ro_model->ro_get(array('roID' => $roID));
			$formSubmit = $this->input->post('submitForm');
			$remark = $this->input->post('remark');
			$emailList = array();
			if( $formSubmit == 'release' ){
				$data['status'] = 2;
				$emailList = $this->get_email($ro->userID,FALSE,TRUE);
				$this->session->set_flashdata('message', 'Released by Department');
			} elseif( $formSubmit == 'submit' ){
				$data['status'] = 3;
				$emailList = $this->get_email($ro->userID,FALSE,FALSE,TRUE);
				$this->session->set_flashdata('message', 'Pending HRMA Approval');
			} elseif( $formSubmit == 'approve' ){
				$data['status'] = 4;
				$emailList = $this->get_email($ro->userID,FALSE,TRUE);
				$this->update_item_qty($roID);
				$this->session->set_flashdata('message', 'The RO is already approved.');
			} else {
				$data['status'] = 0;
				$emailList = $this->get_email($ro->userID);
				$this->session->set_flashdata('message', 'The RO is already rejected.');				
			}
			$this->ro_model->ro_update_status($roID,$data);
			$log = Array(
				'roID' => $roID,
				'user_id' => $this->ion_auth->get_user_id(),
				'status' => $data['status'],
				'remark' => $remark
			);
			$this->ro_model->ro_log_insert($log);
			$emailData = array(
				'roID' => $roID,
				'user_id' => $ro->userID,
				'status' => $this->data['status'][$data['status']],
				'name' => $this->ion_auth->user()->row()->name
			);
			$this->notification_email($emailData,$emailList);
		}
		redirect('main', 'refresh');
	}
	
	private function update_item_qty($roID){
		$roItems = $this->ro_model->ro_item_get(array('roID' => $roID));
		foreach ( $roItems as $roItem ){
			$this->Items_model->update_item_qty($roItem->qty,$roItem->itemID);
		}
	}
	
	public function login()
	{
		$this->data['title'] = "Login";

		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember))
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('main/login', 'refresh');
			}
		}
		else
		{
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

			$this->data['username'] = array('name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$this->load->view('login', $this->data);
		}
	}
	
	function logout()
	{
		$logout = $this->ion_auth->logout();

		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('main/login', 'refresh');
	}
	
	private function get_email($userid,$hod = FALSE, $admin = FALSE, $hrm = FALSE){
		$this->load->model('user_model');
		$email = array($this->ion_auth->user($userid)->row()->email);
		$dept = $this->ion_auth->user($userid)->row()->department_id;
		if ( $hod ) {
			foreach($this->user_model->get_email(3,$dept) as $e){
				$email[] = $e->email;
			}
		}
		if ( $admin ) {
			foreach($this->user_model->get_email(1) as $e){
				$email[] = $e->email;
			}
		}
		if ( $hrm ) {
			foreach($this->user_model->get_email(4) as $e){
				$email[] = $e->email;
			}
		}
		return array_unique($email);
	}
	
	private function notification_email($data,$emailList){
		$this->load->library('email');

		$this->email->set_newline("\r\n");
		$this->email->from('stationerymanagementsystemutp@gmail.com', 'Stationery Management System');
		$this->email->to($emailList);
		$this->email->subject('[SMS] '.$data['status'].' RO '.$data['roID']);
		$this->email->message($this->load->view('request_notification_email',$data,TRUE));
		
		if(!$this->email->send()){
			$this->session->set_flashdata('error', 'Notification email do not send.');
			//show_error($this->email->print_debugger());
		}
	}
}