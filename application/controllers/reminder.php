<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reminder extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('table');
		$this->load->library('email');

		$this->load->database();
		
		$this->load->model('ro_model');
		$this->load->model('Items_model');
		$this->load->model('Department_model');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['error'] = $this->session->flashdata('error');
		$this->data['title'] = "";
		$this->data['status'] = Array('Rejected','Requested','Released','Submitted','Approved');
		$this->data['admin'] = $this->ion_auth->is_admin();
	}

	public function index()
	{
		$ros = $this->ro_model->ro_getAll();
		foreach ( $ros as $ro ){
			$ro_log = $this->ro_model->ro_log_get(array('roID' => $ro->roID));
			$log = $ro_log[0];
			if  ( $log->status > 0 && $log->status < 4 ){
				$statusDate = date('Y-m-d', strtotime($log->date));
				$todayDate = date('Y-m-d');
				$diff = $this->getWorkingDays($statusDate,$todayDate);
				if ( $log->reminder_attempt == 4 ){
					$this->ro_model->ro_update_status($ro->roID,array('status'=>0));
					$this->ro_model->ro_log_update(array('reminder_attempt' => $log->reminder_attempt + 1),$log->ro_log_id);
					$emailList = $this->get_email($ro->userID,TRUE);
					$emailData = array(
						'roID' => $ro->roID,
						'user_id' => $ro->userID,
						'status' => $this->data['status'][$log->status],
						'name' => 'System',
						'attempt' => 'Auto Rejected. '
					);
					$log = Array(
						'roID' => $ro->roID,
						'user_id' => 1,
						'status' => 0,
						'remark' => 'rejected due to no response from (HOD/Admin/HRMA)'
					);
					$this->ro_model->ro_log_insert($log);
				} else if( $log->reminder_attempt > 4 ){
				} else if ( $diff > 0 ){
					if ( $diff%2 == 0 && $diff/2 != $log->reminder_attempt){
						if ( $log->status == 1 ){
							$emailList = $this->get_email($ro->userID,FALSE,TRUE);
						} else if ( $log->status == 2 ){
							$emailList = $this->get_email($ro->userID,FALSE,FALSE,TRUE);
						} else if ( $log->status == 3 ){
							$emailList = $this->get_email($ro->userID,FALSE,FALSE,FALSE,TRUE);
						}
						$emailData = array(
							'roID' => $ro->roID,
							'user_id' => $ro->userID,
							'status' => $this->data['status'][$log->status],
							'name' => $this->ion_auth->user($log->user_id)->row()->name,
							'attempt' => '#'.($log->reminder_attempt + 1).' Reminder '
						);
						$this->notification_email($emailData,$emailList);
						$this->ro_model->ro_log_update(array('reminder_attempt' => $log->reminder_attempt + 1),$log->ro_log_id);
					}
				}
			}
		}
	}
	
	private function get_email($userid,$user = FALSE, $hod = FALSE, $admin = FALSE, $hrm = FALSE){
		$this->load->model('user_model');
		$email = array();
		$dept = $this->ion_auth->user($userid)->row()->department_id;
		if ( $user ){
			$email[] = array($this->ion_auth->user($userid)->row()->email);
		}
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
		$this->email->set_newline("\r\n");
		$this->email->from('stationerymanagementsystemutp@gmail.com', 'Stationery Management System');
		$this->email->to($emailList);
		$this->email->subject('[SMS] '.$data['attempt'].$data['status'].' RO '.$data['roID']);
		$this->email->message($this->load->view('request_notification_email',$data,TRUE));
		
		if(!$this->email->send()){
			$this->session->set_flashdata('error', 'Notification email do not send.');
			//show_error($this->email->print_debugger());
		}
	}
	
	private function getWorkingDays($startDate,$endDate,$holidays = array()){
		// do strtotime calculations just once
		$endDate = strtotime($endDate);
		$startDate = strtotime($startDate);


		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = ($endDate - $startDate) / 86400 + 1;

		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week) {
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else {
			// (edit by Tokes to fix an edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)

			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7) {
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;

				if ($the_last_day_of_week == 6) {
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else {
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
		  $workingDays += $no_remaining_days;
		}

		//We subtract the holidays
		foreach($holidays as $holiday){
			$time_stamp=strtotime($holiday);
			//If the holiday doesn't fall in weekend
			if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
				$workingDays--;
		}

		return $workingDays;
	}
}