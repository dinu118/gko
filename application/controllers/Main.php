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
				redirect(base_url()."index.php/Main/dashboard");
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
		$str=$this->model_users->can_log_in();
		if($str === "true"){
			return true;
		}else{
			$this->form_validation->set_message("validate_credentials",$str);
			return false;
		}
	}
/*-----------------------------------------------------------------------------
						REGISTER VALIDATIION
------------------------------------------------------------------------------*/
	public function register_validation(){
		$this->form_validation->set_rules("register_email","Email","required|valid_email|trim|is_unique[users.email]");
		$this->form_validation->set_rules("register_password","Password","required|trim|min_length[8]|matches[confirm_register_password]");
		$this->form_validation->set_rules("confirm_register_password","Confirm Password","required|trim");
		$this->form_validation->set_rules("first_name","First name","required|trim");
		$this->form_validation->set_rules("last_name","Last name","required|trim");
		$this->form_validation->set_rules("contact","Contact Number","required|trim|regex_match[/^[0-9().+\\s]+$/]");
		
		$this->form_validation->set_message("is_unique","Email already registered.You can continue to login.");
		$this->form_validation->set_message("matches","Passwords don't match.");
		$this->form_validation->set_message("min_length","Password must contain atleast 8 characters.");
		$this->form_validation->set_message("regex_match","Please enter a valid contact number.");

		if($this->form_validation->run()){
			$this->form_validation->set_rules("last_name","Last name","callback_check_name");
			if($this->form_validation->run()){
				$key=md5(''.uniqid().time().$this->input->post('register_email'));
				if($this->send_mail($key)===true)
				{	$this->load->model("model_users");
					if($this->model_users->register_user($key))
						redirect(base_url()."index.php/Main/index?success");
					else{
						$this->form_validation->set_message("unsuccessful","Registration can't be completed right now.<br/>
															Please try again later.");
						}
					}
				}
			
			$this->index();
			}else{
				$this->index();
			}
		}
/*-----------------------------------------------------------------------------
			VALIDATE REGISTRATION DATA PROVIDED BY THE USER
------------------------------------------------------------------------------*/
	
public function check_name(){	
	$name=$this->input->post("last_name");
	if(preg_match("/\\s/",$name)==true)
	{	$this->form_validation->set_message("check_name","Last name must not contain any blank spaces.");
		return false;
		}else{
			return true;
		}
	}

public function send_mail($key){
	$this->load->library('email',array('mailtype'=>'html'));

	$this->email->from("testing@webmail.com");
	$this->email->to($this->input->post('register_email'));
	$this->email->subject("Confirm your account .");
	$adr=base_url()."index.php/Main/activate?key=".$key;
	$message="<br/>Hi ".$this->input->post("first_name")." ".$this->input->post("last_name")." ,<br/>".
				"Your account has been registered successfully.<br/>To continue with login, <br/>Kindly activate your account". 
				" by clicking the link below or pasting it in your url bar.<br/><br/>".
				"<a href='".$adr."'>".$adr."</a><br/><br/>";
	
	$this->email->message($message);
	if($this->email->send()===true)
		return true;
	else
	{	$this->form_validation->set_message("sent_fail","Confirmation email can't be sent to your account right now.
											<br/>Please try again later.");
		return false;
		}
}

public function logout(){
	$this->session->sess_destroy();
	redirect(base_url());
}

public function activate()
{	if(!isset($_GET['key'])||empty($_GET['key']))
		redirect(base_url());
	$key=$_GET['key'];
	$this->load->model("model_users");
	if($this->model_users->activate($key))
		redirect(base_url()."?active");
	else
		redirect(base_url()."?error");
	}
  /*----------------------------------------------------------------------------
					END OF THE MAIN controller
  ------------------------------------------------------------------------------*/
}
?>