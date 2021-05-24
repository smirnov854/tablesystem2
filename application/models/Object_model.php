<?php

class Object_model extends CI_Model
{

    public function get_list($search_params  =[])
    {
        $user_id = $search_params['user_id'];
        $role_id = $search_params['role_id'];;
        //$role_id=1;
        if($role_id == 1){
            $sql = "
                SELECT o.* 
                FROM objects o
                ";
        }else{
            $sql = "SELECT o.* 
                FROM objects o
                LEFT JOIN user_object uo ON uo.object_id=o.id
                WHERE uo.user_id=$user_id";
        }
        
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        return $query->result();
    }


    function add_new_object($common_info)
    {

        if (empty($common_info)) {
            return FALSE;
        }
        $query = $this->db->insert("objects", $common_info);
        if (!$query) {
            return FALSE;
        }
        return $this->db->insert_id();
    }

    function get_role_list()
    {
        $query = $this->db->get("role");
        return $query->result();
    }


    
    public function add_connection($user_id, $object_list){       
        if(empty($user_id) || empty($object_list)){
            return false;
        }      
        $this->db->where("user_id",$user_id)->delete("user_object");
        foreach($object_list as $row){
            $insert_array = [
                "user_id"=>$user_id,
                "object_id"=>$row,
            ];
            $res = $this->db->insert("user_object",$insert_array);
            
            if(!$res){
                return FALSE;
            }
        }
        return TRUE;        
    }
    
    
    
    
    function get_by_id($id)
    {
        if (!$id) {
            return FALSE;
        }
        $query = $this->db->where("id", $id)
            ->get("users");
        if (!$query) {
            return FALSE;
        }
        return $query->result();
    }
}