<?php

class User_model extends CI_Model
{

    public function get_user_list($search_params = [])
    {
        $limit = 25;
        $offset = 0;
        extract($search_params);        
        $where = [];
        $where[] = " u.is_delete IS NULL  ";
        
        
        if(!empty($object_id)){
            $where[] = " uo.object_id = $object_id";
        }

        if(!empty($role_id)){
            $where[] = " u.role_id = $role_id";
        }
        
        if(!empty($fio)){
            $where[] = " u.name LIKE '%$fio%'";
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                       u.*, 
                       r.id as role_id,
                       r.name as role_name, 
                       COUNT(o.id) as object_cnt, 
                       GROUP_CONCAT(o.id SEPARATOR ',') as object_ids,
                       IF(CHAR_LENGTH(GROUP_CONCAT(o.name SEPARATOR ', ')) > 75,SUBSTRING(GROUP_CONCAT(o.name SEPARATOR ', '),1,75) ,GROUP_CONCAT(o.name SEPARATOR ', ') ) as object_names,
                       GROUP_CONCAT(o.name SEPARATOR ', ')   as object_names_title
                FROM users u
                LEFT JOIN role r ON r.id=u.role_id
                LEFT JOIN  user_object uo ON uo.user_id=u.id
                LEFT JOIN objects o ON o.id=uo.object_id                               
                ";
        $where_res = " WHERE " .implode(" AND ",$where);
        $sql.= $where_res;
        $sql.= " GROUP BY u.id ";
        $sql.= " LIMIT $offset,$limit ";        
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
        $query = $this->db->select("u.*, r.name as role_name",FALSE)->where("email", $data['login'])
            ->join("role r","r.id=u.role_id","LEFT")
            ->get("users u");
        
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
  
    public function set_delete($id){        
        if(empty($id) || !is_numeric($id)){
            $res = FALSE;
        }else{
            $res = $this->db->where("id",$id)->update("users",["is_delete"=>1]);    
        }
        return $res;
    }

}