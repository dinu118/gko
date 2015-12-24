<?php
class Model_users extends CI_Model{

  public function can_log_in(){
    $login_email = $this->input->post("login_email");
    $login_password = $this->input->post("login_password");

    $result = $this->db->query("SELECT * FROM users WHERE email = '$login_email' AND password='$login_password'");
    if($result->num_rows() === 1){
      $result=$result->result_array()[0];
      if($result['active']==='1'){
          return "true";
      }else{
        return "Your account has not been activated yet.Please check your email to activate the account.";
      }
   }else 
        return "Incorrect username/ password";  
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

  public function valid_email(){
    $email=$this->input->post("register_email");
    $result=$this->db->query("SELECT * FROM users WHERE email='$email'");
    if($result->num_rows()===0)
      return true;
    else
      return false;
  }
  public function register_user($key){
    $data=array(  'email'=>$this->input->post('register_email'),
                  'password'=>md5($this->input->post('register_password')),
                  'first_name'=>$this->input->post('first_name'),
                  'last_name'=>$this->input->post('last_name'),
                  'contact'=>$this->input->post('contact'),
                  'email_code'=>$key
                    );
    $query=$this->db->insert('users',$data);
    if($query)
      return true;
    else
      return false;
  }

  public function activate($key){
    $result=$this->db->query("SELECT id FROM users WHERE `email_code`='".$key."'");
    if($result->num_rows()===0)
       return false;
    else{
      $result=$result->result_array()[0];
      $data=array('active'=>1,
                  'email_code'=>md5($key+time()));
      $this->db->update('users',$data,array('id'=>$result['id']));
      return true;
      }
  }

  public function delete($data){
    $res=$this->db->update('users',array('active'=>0),array('id'=>$data['id']));
    if($res)
      return true;
    else
      return false;
  }

}
 ?>
