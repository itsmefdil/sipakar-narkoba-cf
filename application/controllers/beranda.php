<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'date'));
		$this->load->library(array('session'));
		$this->load->model(array('gejala_cuaca_model','gejala_model','cuaca_model','kelompok_gejala_model'));
	}
	
	public function index(){
		$this->load->view(('beranda/home'));
	}
	
	public function diagnosa()
	{
		$data["title"] = "";
		$data["sub_title"] = "";
		if(!$this->input->post("gejala"))
		{
			$data["view"]="beranda/form";
			$data['listKelompok'] = $this->kelompok_gejala_model->get_list_data(); 
			$this->load->view("beranda/diagnosa", $data);
		}
		else
		{
			$data["view"]="beranda/result";
			$gejala = implode(",", $this->input->post("gejala"));
			$data["listGejala"] = $this->gejala_model->get_list_by_id($gejala);
			//hitung
			$listcuaca = $this->gejala_cuaca_model->get_by_gejala($gejala);
			$cuaca = [];
			$i=0;
			foreach($listcuaca->result() as $value){
				$listGejala = $this->gejala_cuaca_model->get_gejala_by_cuaca($value->cuaca_id,$gejala);
				$combineCF=0;
				$CFBefore=0;
				$j=0;
				foreach($listGejala->result() as $value2){
					$j++;
					if($j==1)
						$combineCF=$value2->mb;
					else
					$combineCF =$combineCF + ($value2->mb * (1 - $combineCF));
				}
				if($combineCF>=0.5)
				{
					$cuaca[$i]=array('kode'=>$value->kode,
										'nama'=>$value->nama,
										'kepercayaan'=>$combineCF*100,
										'keterangan'=>$value->keterangan);
					$i++;
				}
			}
			
			function cmp($a, $b)
			{
				return ($a["kepercayaan"] > $b["kepercayaan"]) ? -1 : 1;
			}
			usort($cuaca, "cmp");
			$data["listCuaca"] = $cuaca;
			$this->load->view("beranda/diagnosa", $data);
		}
	}
}
