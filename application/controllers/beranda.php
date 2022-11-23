<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'date'));
		$this->load->library(array('session'));
		$this->load->model(array('gejala_narkoba_model','gejala_model','narkoba_model','kelompok_gejala_model'));
	}
	
	public function index(){
		$this->load->view(('frontend/index'));
	}
	
	public function diagnosa()
	{
		$data["title"] = "";
		$data["sub_title"] = "";
		if(!$this->input->post("gejala"))
		{
			$data["view"]="beranda/form";
			$data['listKelompok'] = $this->kelompok_gejala_model->get_list_data(); 
			$this->load->view("frontend/diagnosa", $data);
		}
		else
		{
			$data["view"]="beranda/result";
			$gejala = implode(",", $this->input->post("gejala"));
			$data["listGejala"] = $this->gejala_model->get_list_by_id($gejala);
			//hitung
			$listnarkoba = $this->gejala_narkoba_model->get_by_gejala($gejala);
			$narkoba = [];
			$i=0;
			foreach($listnarkoba->result() as $value){
				$listGejala = $this->gejala_narkoba_model->get_gejala_by_narkoba($value->narkoba_id,$gejala);
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
					$narkoba[$i]=array('kode'=>$value->kode,
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
			usort($narkoba, "cmp");
			$data["listnarkoba"] = $narkoba;
			$this->load->view("frontend/diagnosa", $data);7
		}
	}
}
