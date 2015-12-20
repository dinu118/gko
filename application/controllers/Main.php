<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	private $user_data = array();

	public function __construct()
	{

		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model("Model_users");
		if($this->session->userdata("email")){
			$this->user_data = $this->Model_users->user_data($this->session->userdata("email"));
		}else{
			$this->user_data['email']  = "";
		}
	}

	public function index()
	{
    if(empty($this->session->userdata('email'))===true)
		$this->load->view('home');
		else {
			$this->dashboard();
		}
	}
/*--------------------------------------------------------------------------------
         THIS FUNCTION LOADS THE DASHBOARD
--------------------------------------------------------------------------------*/
	public function dashboard(){
		$this->load->view("dashboard",$this->user_data);
	}

/*--------------------------------------------------------------------------------
				SENDS THE SMS
--------------------------------------------------------------------------------*/
	public function sendMessage(){

     $this->form_validation->set_rules("recipent_number","recipent_number","required");
		$this->form_validation->set_rules("message_subject","message_subject","required");
		$this->form_validation->set_rules("message_content","message_content","required");



		// Authorisation details.
  if($this->form_validation->run()){
		$no = $this->input->post("recipent_number");
		$msg = $this->input->post("message_content");
		$subject = $this->input->post("message_subject");

		$username = "dinu.iota@gmail.com";
		$hash = "d5dae961b00290e6b3b5086e4b1ccf817c1f8578";

		// Configuration variables. Consult http://api.textlocal.in/docs for more info.
		$test = "0";

		// Data for text message. This is the text message data.
		$sender = "TXTLCL"; // This is who the message appears to be from.
		$numbers = $no; // A single number or a comma-seperated list of numbers
		$message = $msg;
		// 612 chars or less
		// A single number or a comma-seperated list of numbers
		$message = urlencode($message);
		$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
		$ch = curl_init('http://api.textlocal.in/send/?');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch); // This is the result from the API
		curl_close($ch);


		echo $result;
	}else{
		$this->dashboard();
	}
	}
/*-----------------------------------------------------------------------------
						LOGIN VALIDATIION
------------------------------------------------------------------------------*/
	public function login_validation(){
		$this->form_validation->set_rules("login_email","Email","required|valid_email|trim");
		$this->form_validation->set_rules("login_password","Password","required|trim|md5");

		if($this->form_validation->run()){
			$this->form_validation->set_rules("login_email","Email","callback_validate_credentials");
			if($this->form_validation->run()){
				$data = array(
					"email" => $this->input->post("login_email"),
					"is_logged_in" => 1
				);

				$this->session->set_userdata($data);
				$this->dashboard();
			}else{
				$this->index();
			}
		}else{
			$this->index();
		}
	}
/*------------------------------------------------------------------------------
						VALIDATE THE CREDENTIALS GIVEN BY THE USER ON LOGIN SCREEN
------------------------------------------------------------------------------*/
	public function validate_credentials(){
		$this->load->model("model_users");
		if($this->model_users->can_log_in() === true){
			return true;
		}else{
			$this->form_validation->set_message("validate_credentials","Incorrect username/password");
			return false;
		}
	}
public function logout(){
	$this->session->sess_destroy();
	$this->load->view("home");
}

	/*----------------------------------------------------------------------------
					END OF THE MAIN controller
  ------------------------------------------------------------------------------*/
}
