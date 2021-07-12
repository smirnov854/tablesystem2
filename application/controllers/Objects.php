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
            "limit"=>25,
            "offset"=>0
        ];
        
        $objects = $this->object_model->get_all($search_params);
        $total_rows = $this->db->query("SELECT FOUND_ROWS() as cnt")->result();
        $this->load->view('includes/header');
        $this->load->view('includes/menu');
        $this->load->view("objects/object_list",[
            "objects"=>$objects,
            "total_rows"=>$total_rows[0]->cnt
        ]);
        $this->load->view("includes/footer");
    }
    
    public function search($page){
        $user_data = $this->session->userdata();
        
        $params = json_decode(file_get_contents('php://input'));
        $search_params = [
            "role_id"=>$user_data['role_id'],
            "user_id"=>$user_data['id'],
            "limit"=>25,
            "offset"=>(!empty($page) ? ($page-1)*25:0)
        ];
        $users = $this->object_model->get_all($search_params);
        $total_rows = $this->db->query("SELECT FOUND_ROWS() as cnt")->result();

        $result = [
            "status"=>200,
            "content"=>$users,
            "total_rows"=>$total_rows[0]->cnt
        ];
        echo json_encode($result);
    }    

    public function add_new_object($id=0) {
        $params = json_decode(file_get_contents('php://input'));        
        $common_info = array(
            "name" => $params->name,
            "address" => $params->address,
            "description"=> $params->description,
        );
        try {
            if (empty($common_info['name']) || empty($common_info['address'])){
                throw new Exception("Ошибка заполнения формы!", 300);
            }
            if(!empty($id)){
                if(!is_numeric($id)){
                    throw new Exception("Ошибка получени id!", 300);    
                }
                $res = $this->object_model->edit_object($id,$common_info);               
                if (!$res) {
                    throw new Exception("Ошибка обращения к базе данных!", 2);
                }
            }else{
                $res = $this->object_model->add_new_object($common_info);
                if (!$res) {
                    throw new Exception("Ошибка обращения к базе данных!", 2);
                }
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

    public function set_delete($id){
        try {
            if(empty($id) || !is_numeric($id)){
                throw new Exception("Ошибка получения id!",301);
            }
            if(!$this->object_model->edit_object($id,['is_delete'=>1])){
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
        for($i = 0; $i<10;$i++){
            $insert_array = [
                "name"=>'Объект '.$i,
                "address"=>'Адрес '.$i,
                "description"=>'Описание '.$i,
            ];
            $this->db->insert("objects",$insert_array);    
        }
    }

}
