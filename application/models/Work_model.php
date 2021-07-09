<?php

class Work_model extends CI_Model
{

    
    public function get_type_list(){
        $res  = $this->db->get("type_of_work");
        return $res->result();
    }
        
    
    public function get_list($search_params = "")
    {
        extract($search_params);        
        $sql = "SELECT SQL_CALC_FOUND_ROWS req.*, 
                       GROUP_CONCAT(rf.file_path SEPARATOR '||') as file_path,
                       FROM_UNIXTIME(req.date_add) as date_add,
                       o.name as object_name, 
                       usr.name as add_user_name, 
                       usr1.name as done_user,
                       usr2.name as common_check_user,
                       usr3.name as check_user
                FROM requests req
                LEFT JOIN request_files rf ON rf.request_id=req.id
                LEFT JOIN objects o ON o.id = req.object_id                
                LEFT JOIN type_of_work tof ON tof.id=req.type_id
                LEFT JOIN users usr ON usr.id = req.id_user_add
                LEFT JOIN users usr1 ON usr1.id = req.id_user_done
                LEFT JOIN users usr2 ON usr2.id = req.id_user_common_check
                LEFT JOIN users usr3 ON usr3.id = req.id_user_check                
                ";        
        $where = [];
        if(!empty($search_params)){
            if(!empty($objects)){
                $object_string = implode(",",$objects);
                $where[] =" o.id IN ($object_string)";
            }
            if(!empty($date_from)){
                $where[] = " date_add> ".strtotime($date_from);
            }

            if(!empty($date_to)){
                $where[] = " date_add< ".strtotime($date_to);
            }            
        }
        
        if($role_id != 1){
            $where[] = " req.object_id IN (SELECT object_id FROM user_object WHERE user_id = $user_id)";
        }
        
        if(!empty($where)){
            $where_str =" WHERE ". implode(" AND ",$where);
            $sql.=$where_str;
        }
        $sql.= "GROUP BY req.id ";
        $sql.= " ORDER BY req.id DESC ";
        $sql.= " LIMIT $offset,$limit ";
        //echo $sql;
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        return $query->result();
    }


    function add_new_request($common_info)
    {

        if (empty($common_info)) {
            return FALSE;
        }
        $query = $this->db->insert("requests", $common_info);
        if (!$query) {
            return FALSE;
        }
        return $this->db->insert_id();
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
        $query = $this->db->where("id", $user_id)->update("users", $data);
        if (!$query) {
            return FALSE;
        }
        return TRUE;
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
    
    public function add_connection($req_id,$path){        
        return $this->db->insert("request_files",["request_id"=>$req_id,"file_path"=>$path]);
    }
    
    public function update_by_id($id,$update_arr){
        if(empty($id) || !is_numeric($id)){
            return FALSE;
        }        
        return $this->db->where("id",$id)->update("requests",$update_arr);
    }

}