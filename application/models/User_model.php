<?php

class User_model extends CI_Model
{

    public function get_user_list()
    {

        $sql = "SELECT u.*, 
                       r.id as role_id,
                       r.name as role_name, 
                       COUNT(o.id) as object_cnt, 
                       GROUP_CONCAT(o.id SEPARATOR ',') as object_ids  
                FROM users u
                LEFT JOIN role r ON r.id=u.role_id
                LEFT JOIN  user_object uo ON uo.user_id=u.id
                LEFT JOIN objects o ON o.id=uo.object_id  
                WHERE u.is_delete IS NULL      
                GROUP BY u.id        
                ";
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        return $query->result();
    }


    function add_new_user($common_info)
    {

        if (empty($common_info)) {
            return FALSE;
        }
        $query = $this->db->insert("users", $common_info);
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


    function login($data)
    {
        if (empty($data)) {
            return FALSE;
        }
        $query = $this->db->where("email", $data['login'])
            ->get("users");
        
        if ($query->num_rows() != 1) {
            return FALSE;
        }
        $query_result = $query->result();        
        if (!password_verify($data['password'], $query_result[0]->password)) {
            return FALSE;
        }
        return $query_result;
    }


    public function edit_user($user_id, $data)
    {
        if (!$user_id) {
            return FALSE;
        }
        $query = $this->db->where("id", $user_id)
            ->update("users", $data);
        if (!$query) {
            return FALSE;
        }
        return TRUE;
    }


    public function get_by_filters($filters = [])
    {
        extract($filters);
        if (!empty($role_id)) {
            $this->db->where("u.role_id", $role_id);
            $this->db->order_by("u.league ASC,lower(u.user_name)");
        }
        $query = $this->db->get("users u");
        return $query->result();
    }

    public function get_by_login($login)
    {
        if (!$login) {
            return FALSE;
        }
        $query = $this->db->select("users.*,rank.name as rank_name")
            ->where("login", $login)
            ->join("rank", "rank.id=users.rank_id", "left")
            ->get("users");
        if (!$query || $query->num_rows() == 0) {
            return FALSE;
        }
        return $query->result()[0];
    }
    
    public function set_delete($id){        
        if(empty($id) || !is_numeric($id)){
            $res = FALSE;
        }else{
            $res = $this->db->where("id",$id)->update("users",["is_delete"=>1]);    
        }
        return $res;
    }

}