<?php

class Work_model extends CI_Model
{

    
    public function get_type_list(){
        $res  = $this->db->where("is_delete",0)->get("type_of_work");
        return $res->result();
    }
    
    public function add_new_work_type($common_info){
        if (empty($common_info)) {
            return FALSE;
        }        
        $query = $this->db->insert("type_of_work", $common_info);
        if (!$query) {
            return FALSE;
        }
        return $this->db->insert_id();
    }

    public function edit_work_type($id,$common_info){
        if(empty($id) || !is_numeric($id)){
            return false;
        }
        return $this->db->where("id",$id)->update("type_of_work",$common_info);
    }
        
    
    public function get_list($search_params = "")
    {
        extract($search_params);        
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                       req.done_work,
                       req.id,
                       req.type_of_work,
                       tof.name as type_name, 
                       req.description,
                       IF(user_done_date IS NOT NULL AND user_done_date!=0,FROM_UNIXTIME(user_done_date),'') as user_done_date,
                       IF(date_add IS NOT NULL AND date_add!=0,FROM_UNIXTIME(date_add),'') as date_add,
                       IF(common_date IS NOT NULL AND common_date!=0,FROM_UNIXTIME(common_date),'') as common_date,
                       IF(user_check_date IS NOT NULL AND user_check_date!=0,FROM_UNIXTIME(user_check_date),'') as user_check_date, 
                       GROUP_CONCAT(rf.file_path SEPARATOR '||') as file_path,                      
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
            if(!empty($id)){
                $where[] = " req.id=$id ";                
            }
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
            
            if(!empty($status)){
                switch($status){
                    case "all":
                        break;
                    case "new":
                        $where[] = " (user_done_date IS NULL OR user_done_date=0) ";
                        break;
                    case "done":
                        $where[] = " (user_done_date>0 AND (user_check_date IS NULL OR user_check_date=0)) ";
                        break;
                    case "checked":
                        $where[] = " (user_check_date>0 AND (common_date IS NULL OR common_date=0)) ";
                        break;
                    case "closed":
                        $where[] = " (common_date IS NOT NULL AND common_date<>0) ";
                        break;
                }
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
    
    public function get_user($id, $type = 'worker'){
        if(empty($id) || !is_numeric($id)){
            return FALSE;
        }
        
        $sql = "
        SELECT rq.id, rq.object_id, 
                               uo.user_id, 
                               u_eng.id, u_worker.id, u_client.id,
                               u_eng.email as eng_email, 
                               u_worker.email as worker_email,
                               u_client.email as client_email
                        FROM requests rq
                        LEFT JOIN objects o ON o.id=rq.object_id
                        LEFT JOIN user_object uo ON uo.object_id=o.id
                        LEFT JOIN users u_client ON u_client.id=uo.user_id AND u_client.role_id = 2
                        LEFT JOIN users u_eng ON u_eng.id=uo.user_id AND u_eng.role_id = 3
                        LEFT JOIN users u_worker ON u_worker.id=uo.user_id AND u_worker.role_id = 4
                        WHERE rq.id=$id";
        /*
        switch($type){
            case 'client':
                $sql.= " AND  (u_eng.email IS NOT NULL OR u_worker.email IS NOT NULL) ";
                $req = $this->db->query($sql);
                break;
            case 'engineer':
                $sql.=" AND  (u_client.email IS NOT NULL OR u_worker.email IS NOT NULL) ";
                $req = $this->db->query($sql);
                break;
            case 'worker':
                $sql.= " AND  (u_client.email IS NOT NULL OR u_eng.email IS NOT NULL) ";
                
                break;
        }*/
        $req = $this->db->query($sql);
        $req = $req->result();   
        $only_emails = [];
        foreach($req as $row ){
            $only_emails[] = $row->eng_email;
            $only_emails[] = $row->worker_email;
            $only_emails[] = $row->client_email;
        }        
        $res = array_filter($only_emails);
        return $res;
    }
}