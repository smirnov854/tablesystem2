<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
     
    private $error = "";
  
    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_model"); 
    }

    public function index()
    {
        $this->show_login();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect("/login/show_login");
    } 

    public function show_login()
    {
        $this->load->view("user/login", ["error_messages" => $this->error]);

    }

    public function check_login(){
        try {
            $data = [
                "login" => $this->input->post("user_name"),
                "password" => $this->input->post("user_password"),
            ];
            if (empty($data['login']) || empty($data['password'])) {
                throw new Exception("Необходимо заполнить поля логин и пароль!", 300);
            }
            //$password = password_hash("admin", PASSWORD_BCRYPT);
            //$query = $this->db->insert("users",['email'=>"admin@admin.com","password"=>$password,"role_id"=>1]);
           // $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
            $user = $this->user_model->login($data);
            if (empty($user)) {
                throw new Exception("Пользователя с таким данными не существует!", 300);
            }
            $user = reset($user);
            $session_array = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role_id" => $user->role_id,
                "role_name"=>$user->role_name
            ];
            
            $this->session->set_userdata($session_array);
           
            $result = array(
                "status" => 200,
                "message" => "Добро пожаловать"
            );

        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                            "status" => $ex->getCode());
        }
        switch($result['status']){
            case "200":                
                redirect("/work");
                break;
            case "300":
                redirect("/login/show_login");
                $this->error = $result['message'];
                break;
        }
    }

    public function start_page(){
        $this->load->view("includes/header");
        $this->load->view("includes/menu");
        $this->load->view("includes/footer");
    }
}
