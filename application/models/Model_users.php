<?php
class Model_users extends CI_Model{

  public function can_log_in(){
    $login_email = $this->input->post("login_email");
    $login_password = $this->input->post("login_password");

    $result = $this->db->query("SELECT * FROM users WHERE email = '$login_email' AND password='$login_password'");
    if($result->num_rows() === 1)
    return true;
    else return false;
  }


  public function user_data($email){
    $result   = $this->db->query("SELECT * FROM users WHERE email='$email'");
    $user_data = array();
    foreach($result->result_array() as $x){
      $user_data = $x;
      break;
    }

    return $user_data;
  }

}
 ?>
