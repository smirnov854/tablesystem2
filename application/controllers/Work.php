<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Work extends CI_Controller
{
    public $menu_values = [];

    public function __construct() {
        parent::__construct();
        $user_data = $this->session->userdata("user_data");
        $this->load->model("user_model");
        $this->load->model("work_model");
        $this->load->model("object_model");
    }

    public function index() {
        $this->show_work_table();
    }


    public function add_new_job() {
        $params = json_decode(file_get_contents('php://input'));
        $user_data = $this->session->userdata();
        $common_info = array(
            "type_id"=>$params->type,   
            "description"=>$params->description,
            "object_id"=>$params->object_id,
            "date_add"=>time(),
            "id_user_add"=>$user_data['id'],
        );        
        try {
            if (empty($common_info['type_id'])){
                throw new Exception("Ошибка заполнения формы!", 300);
            }           
            $res = $this->work_model->add_new_request($common_info);
            if (!$res) {
                throw new Exception("Ошибка обращения к базе данных!", 2);
            }
            $result = [
                "status" => 200,
                "message" => "Заявка добавлена!"
            ];
        } catch (Exception $ex) {
            $result = array("message" => $ex->getMessage(),
                "status" => $ex->getCode());
        }
        echo json_encode($result);
    }

    public function show_work_table() {        
        $user_data = $this->session->userdata();
        $type_list = $this->work_model->get_type_list();
        $search_params = [
            "role_id"=>$user_data['role_id'],
            "user_id"=>$user_data['id'],
        ];
        $object_list = $this->object_model->get_list($search_params);
        $request_list = $this->work_model->get_list($search_params);
        
        $this->load->view('includes/header');
        $this->load->view("includes/menu");        
        $this->load->view("work/work_list",[
            "req_list"=>$request_list,
            "type"=>$type_list,
            "objects"=>$object_list,
            "role_id"=>$user_data['role_id']]);        
        $this->load->view('includes/footer');
    }
        
    public function search_req(){
        $params = json_decode(file_get_contents('php://input'));
        try {             
            $search_params = [
                "objects"=>$params->objects_id,
                "date_from"=>$params->date_from,
                "date_to"=>$params->date_to,
            ];
            $requests = $this->work_model->get_list($search_params);
            if($requests === FALSE){
                throw new Exception("Ошибка обращения к БД!",300);
            }
            $result = [
                "status" => 200,
                "content" => $requests,
            ];
        }catch (Exception $ex) {
                $result = array("message" => $ex->getMessage(),
                    "status" => $ex->getCode());
            }
        echo json_encode($result);
    }

    public function generate_data(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        for($i = 0; $i<10000;$i++){
            $user_id = rand(1,50);
            $query = $this->db->select("object_id") 
                              ->where('user_id',$user_id) 
                              ->get("user_object");
            $res = $query->result();
            $length = count($res);
            $insert_array = [
                "type_id"=>rand(1,3),
                "id_user_add"=>$user_id,
                "object_id"=>$res[rand(1,$length-1)]->object_id,
                "description"=>substr(str_shuffle($permitted_chars), 0, 10)." ".substr(str_shuffle($permitted_chars), 0, 15),
                "date_add"=>strtotime(date("d.m.Y H:i:s",rand(strtotime("01.01.2021"),strtotime("31.04.2021")))),                
            ];
            $this->db->insert("requests",$insert_array);
        }
    }
    
    
}
