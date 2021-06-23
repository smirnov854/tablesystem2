<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Objects extends CI_Controller
{

    public $menu_values = [];

    public function __construct() {
        parent::__construct();
        $user_data = $this->session->userdata();
        $this->load->model("object_model");
    }

    public function index() {
        $this->show_object_list();
    }

    public function show_object_list() {
        $user_data = $this->session->userdata();

        $search_params = [
            "role_id"=>$user_data['role_id'],
            "user_id"=>$user_data['id'],
        ];
        
        $objects = $this->object_model->get_list($search_params);
        $this->load->view('includes/header');
        $this->load->view('includes/menu');
        $this->load->view("objects/object_list",["objects"=>$objects]);
        $this->load->view("includes/footer");
    }

    public function add_new_object() {
        $params = json_decode(file_get_contents('php://input'));        
        $common_info = array(
            "name" => $params->name,
            "address" => $params->address
        );
        try {
            if (empty($common_info['name']) || empty($common_info['address'])){
                throw new Exception("Ошибка заполнения формы!", 300);
            }
            $res = $this->object_model->add_new_object($common_info);
            if (!$res) {
                throw new Exception("Ошибка обращения к базе данных!", 2);
            }
            $result = [
                "status" => 200,
                "message" => "Объект добавлен!"
            ];
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                "status" => $ex->getCode());
        }
        echo json_encode($result);
    }

    
    
    public function generate_data(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';        
        for($i = 0; $i<100;$i++){
            $insert_array = [
                "name"=>substr(str_shuffle($permitted_chars), 0, 10),
                "address"=>substr(str_shuffle($permitted_chars), 0, 10),
            ];
            $this->db->insert("objects",$insert_array);    
        }
    }

}
