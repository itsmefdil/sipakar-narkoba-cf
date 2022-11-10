<?php

Class gejala_cuaca_model extends CI_Model {

     function __construct()
     {
         parent::__construct();
     }


     function mapp_field($field=""){
         if (is_object($field)==false){
             $_result["id"]="";
             $_result["gejala_id"]="";
             $_result["cuaca_id"]="";
             $_result["md"]="";
             $_result["mb"]="";
         }
         else{
            $_result["id"]=$field->id;
            $_result["gejala_id"]=$field->gejala_id;
            $_result["cuaca_id"]=$field->cuaca_id;
            $_result["md"]=$field->md;
            $_result["mb"]=$field->mb;
         }
         return $_result;

     }

     function get_all($status=''){
         $this->db->select('gp.*, g.kode as kode_gejala, g.keterangan as gejala, p.kode as kode_cuaca, p.nama as cuaca')->from('gejala_cuaca as gp');
		 $this->db->join('gejala as g', 'g.id = gp.gejala_id');
		 $this->db->join('cuaca as p', 'p.id = gp.cuaca_id');
         if ($status=='y'){
             $this->db->where('status','y');	
         }
         $this->db->order_by('id','desc');

         $record["data"] = $this->db->get();
         $record["data_arr"] = $record["data"]->result();
         $record["count"] = count($record["data"]->result());

         return $record;
     }

     function get_by_id($id){
         $this->db->select('*')->from('gejala_cuaca');
         $this->db->where("id",$id);
         $query = $this->db->get();
         $result = $query->result();

         if (count($result) > 0){
         	$records = $this->mapp_field($result[0]);
         }
         else{
         	$records = $this->mapp_field($result);
         }

         return $records;
     }

     function get_last_record(){
         $this->db->select('gp.*, g.kode as kode_gejala, g.keterangan as gejala, p.kode as kode_cuaca, p.nama as cuaca')->from('gejala_cuaca as gp');
		 $this->db->join('gejala as g', 'g.id = gp.gejala_id');
		 $this->db->join('cuaca as p', 'p.id = gp.cuaca_id');
         $this->db->order_by("id", "desc");
         $this->db->limit(1);
         $query = $this->db->get();
         $result = $query->result();

         if (count($result) > 0){
         	$records = $this->mapp_field($result[0]);
         }
         else{
         	$records = $this->mapp_field($result);
         }

         return $records;
     }

     function get_by_param($param_name,$value){
         $this->db->select('gp.*, g.kode as kode_gejala, g.keterangan as gejala, p.kode as kode_cuaca, p.nama as cuaca')->from('gejala_cuaca as gp');
		 $this->db->join('gejala as g', 'g.id = gp.gejala_id');
		 $this->db->join('cuaca as p', 'p.id = gp.cuaca_id');
         $this->db->where($param_name,$value);
         $query = $this->db->get();

         $record["data"] = $this->db->get();
         $record["data_arr"] = $record["data"]->result();
         $record["count"] = count($record["data"]->result());
         return $record;
     }

     function get_search($start, $rows, $search){
         $sql = "SELECT `gp`.*, `g`.`kode` as kode_gejala, `g`.`keterangan` as gejala, `p`.`kode` as kode_cuaca, `p`.`nama` as cuaca 
					FROM (`gejala_cuaca` as gp) JOIN `gejala` as g ON `g`.`id` = `gp`.`gejala_id` JOIN `cuaca` as p ON `p`.`id` = `gp`.`cuaca_id` 
					WHERE gp.id like '%".$search."%' order by gp.id asc limit ".$start.",".$rows;
         return $this->db->query($sql);
     }
     function get_search_count($search){
         $sql = "select count(*) as hasil from gejala_cuaca where id like '%".$search."%' order by id asc";
         return $this->db->query($sql);
     }

     function save($data){
         $this->db->insert('gejala_cuaca',$data);
     }

     function update($data,$id){
         $this->db->where('id',$id);
         $this->db->update('gejala_cuaca',$data);
     }

     function delete($id){
         $this->db->where('id',$id);
         $this->db->delete('gejala_cuaca');
     }
     
     function get_by_gejala($gejala){
         $sql = "select distinct cuaca_id,p.kode,p.nama,p.keterangan from gejala_cuaca gp inner join cuaca p on gp.cuaca_id=p.id where gejala_id in (".$gejala.") order by cuaca_id,gejala_id";
         return $this->db->query($sql);
     }
     function get_gejala_by_cuaca($id,$gejala=null){
         $sql = "select distinct gejala_id,mb,md from gejala_cuaca where cuaca_id=".$id; 
         if($gejala!=null)
            $sql=$sql." and gejala_id in (".$gejala.")"; 
        $sql=$sql." order by gejala_id";
         return $this->db->query($sql);
     }
}
?>
