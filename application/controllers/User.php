<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

    public $menu_values = [];

    public function __construct() {
        parent::__construct();
        $user_data = $this->session->userdata("user_data");
        $this->load->model("user_model");
    }

    public function index() {
        $this->show_login();
    }


    public function login() {
        try {
            $data = [
                "login" => $this->input->post("user_name"),
                "password" => $this->input->post("user_password"),
            ];            
            //$password = password_hash("admin", PASSWORD_BCRYPT);
            //$query = $this->db->insert("users",['email'=>"admin@admin.com","user_password"=>$password]);
            if (empty($data['login']) || empty($data['password'])) {
                throw new Exception("Необходимо заполнить поля логин и пароль!", 1);
            }
            //$data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);            
            $user = $this->user_model->login($data);
            if (empty($user)) {
                throw new Exception("Пользователя с таким данными не существует!", 1);
            }
            $user = reset($user);

            $session_array = [
                "id" => $user->id,
                "user_name" => $user->user_name,
                "user_email" => $user->email,
                "role_id" => $user->role_id,
            ];
            $this->session->set_userdata($session_array);

            $result = array("status" => 0,
                "message" => "Добро пожаловать"
            );
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                "status" => $ex->getCode());
        }
        echo json_encode($result);
    }


    public function add_new_user() {
        $user_data = $this->session->userdata();
        $this->load->model("object_model");
        $this->load->model("mail_model");
        $params = json_decode(file_get_contents('php://input'));        
        $common_info = array(
            "email"=>$params->email,
            "name" => $params->user_name,
            "role_id" => $params->role_id,
            'password'=>$params->password,
        );
        $objects = $params->objects;
        try {
            if (empty($common_info['email']) || empty($common_info['name']) || empty($common_info['role_id']) ){
                throw new Exception("Ошибка заполнения формы!", 300);
            }
            if(empty($objects)){
                throw new Exception("Необходимо выбрать объекты!", 300);
            }
            $password_to_send = $common_info['password'];
            $common_info['password'] = password_hash($common_info['password'], PASSWORD_BCRYPT);
            $res = $this->user_model->add_new_user($common_info);
            if (!$res) {
                throw new Exception("Ошибка обращения к базе данных!", 2);
            }            
            $res_add = $this->object_model->add_connection($res,$objects);
            if(!$res_add){
                throw new Exception("Ошибка добавления привязки объект-пользователь!", 3);
            }
            
            $body = "Вы были зарегистрированы в системе.<br/>
                     Для входа перейдите по ссылке <a href='".$_SERVER['SERVER_NAME']."'>ссылке</a> используйте следующую информацию:<br/>                     
                     Имя пользователя: ".$common_info['email']."<br/>
                     пароль:".$password_to_send;
            $send_res = $this->mail_model->send($common_info['email'],"Регистрация в системе",$body);
            if(!$send_res){
                throw new Exception("Ошибка отпраки письма на почту!", 3);
            }
            $result = [
                "status" => 200,
                "message" => "Пользователь добавлен!",
                "content"=>[]
            ];
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                "status" => $ex->getCode());
        }
        echo json_encode($result);
    }

    public function show_users() {
        $this->load->model("object_model");        
        $user_data = $this->session->userdata();
        $users = $this->user_model->get_user_list();
        $roles = $this->user_model->get_role_list();
        $search_param = [
            "user_id"=>$user_data['id'],
            "role_id"=>$user_data['role_id'],
        ];
        $total_rows = $this->db->query("SELECT FOUND_ROWS() as cnt")->result();
        $objects = $this->object_model->get_list($search_param);
        $this->load->view('includes/header');
        $this->load->view("includes/menu");
        $this->load->view("user/user_list", [
            "users"=>$users,
            "roles"=>$roles,
            "objects"=>$objects,
            "total_rows"=>$total_rows[0]->cnt
        ]);
        $this->load->view('includes/footer');
    }
    
    public function search($page = 0){
        

        $params = json_decode(file_get_contents('php://input'));
        $search_params = array(            
            "fio" => $params->fio,
            "role_id" => $params->role,
            'object_id'=>$params->object_id,
            "limit"=>25,
            "offset"=>(!empty($page) ? ($page-1)*25:0)
        );
        $users = $this->user_model->get_user_list($search_params);
        $total_rows = $this->db->query("SELECT FOUND_ROWS() as cnt")->result();

        $result = [
            "status"=>200,
            "content"=>$users,
            "total_rows"=>$total_rows[0]->cnt
        ];
        echo json_encode($result);
    }
 

    public function edit_user($user_id) {
        try {
            $user_data = $this->session->userdata();
            
            $this->load->model("object_model");
            $params = json_decode(file_get_contents('php://input'));
            $common_info = array(
                "email"=>$params->email,
                "name" => $params->user_name,
                "role_id" => $params->role_id,
                'password'=>$params->password,
            );
            $objects = $params->objects;            
            if(empty($user_id)){
                throw new Exception("Ошибка получения номера пользователя!",300);
            }           
            if(empty($params->password)){
                unset($common_info['password']);
            }else{
                $common_info['password'] = password_hash($common_info["password"], PASSWORD_BCRYPT);
            }
            if (empty($common_info['email']) || empty($common_info['name'])) {
                throw new Exception("Ошибка заполнения формы!", 300);
            }            
                        
            /*$res = $this->user_model->check_email($common_info['email'],$user_id);
            if (!$res) {
                throw new Exception("Выбранный email занят! Выберите другой", 2);
            }*/
            $res = $this->user_model->edit_user($user_id, $common_info);
            if (!$res) {
                throw new Exception("Ошибка обращения к базе данных!", 300);
            }            
            $res_add = $this->object_model->add_connection($user_id,$objects);
            if(!$res_add){
                throw new Exception("Ошибка добавления привязки объект-пользователь!", 300);
            }
            $result = array("status" => 200,
                            "message" => "Изменения сохранены");
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                             "status" => $ex->getCode());
        }
        echo json_encode($result);
    }


    public function set_delete($id){
        try {
            if(empty($id) || !is_numeric($id)){
                throw new Exception("Ошибка получения id пользователя!",301);
            }            
            if(!$this->user_model->set_delete($id)){
                throw new Exception('Ошибка простановки статуса Удален',302);
            }
            $result = [
                "status" => 200,                
            ];
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                "status" => $ex->getCode());
        }
        echo json_encode($result);
    }
    

    public function generate_data(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        for($i = 1; $i<=50;$i++){
            $insert_array = [
                "role_id"=>2,
                "email"=>"test_client$i@test.com",
                "name"=>"TEST client $i",
                "password"=>password_hash("123", PASSWORD_BCRYPT),
                //"password"=>password_hash("test_admin$i", PASSWORD_BCRYPT),                                
            ];
            $this->db->insert("users",$insert_array);
        }

        for($i = 1; $i<=50;$i++){
            $insert_array = [
                "role_id"=>3,
                "email"=>"test_worker$i@test.com",
                "name"=>"TEST worker $i",
                "password"=>password_hash("123", PASSWORD_BCRYPT),          
            ];
            $this->db->insert("users",$insert_array);
        }
    }

    public function generate_obj_data(){
        for($i = 0; $i<1000;$i++){
            $insert_array = [                
                "user_id"=>rand(1,100),
                "object_id"=>rand(1,100)
            ];
            $this->db->insert("user_object",$insert_array);
        }
    }

}
