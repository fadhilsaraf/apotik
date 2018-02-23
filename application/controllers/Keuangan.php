<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('keuangan_model','keuangan');
	}

	public function keuangan_keluar()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else{
			redirect();
		}
		$this->load->view('keuangan/keuangan_keluar_view');
	}

	public function keuangan_masuk()
	{
		if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else{
			redirect();
		}
		$this->load->view('keuangan/keuangan_masuk_view');
	}

	public function mengambilKeuanganMasuk()
	{
		$list = $this->keuangan->mengambilDataKeuanganMasuk();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $keuangan) {
			$no++;
			$row = array();
			$row[] = $keuangan->jenis;
			$row[] = $keuangan->sumber;
		    $row[] = $keuangan->tanggal;
		    $row[] = $keuangan->waktu;
		    $row[] = $keuangan->persetujuan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_keuangan('."'".$keuangan->id_keuangan."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_keuangan('."'".$keuangan->id_keuangan."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_keuangan('."'".$keuangan->id_keuangan."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->keuangan->count_all_masuk(),
						"recordsFiltered" => $this->keuangan->count_filtered_masuk(),
						"data" => $data,
				);

		echo json_encode($output);
	}

	public function mengambilkeuanganKeluar()
	{
		$list = $this->keuangan->mengambilDatakeuanganKeluar();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $keuangan) {
			$no++;
			$row = array();
			$row[] = $keuangan->jenis;
			$row[] = $keuangan->sumber;
		    $row[] = $keuangan->tanggal;
		    $row[] = $keuangan->waktu;
		    $row[] = $keuangan->persetujuan;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="detail_keuangan('."'".$keuangan->id_keuangan."'".')"><i class="glyphicon glyphicon-eye-open"></i> Detail</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_keuangan('."'".$keuangan->id_keuangan."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_keuangan('."'".$keuangan->id_keuangan."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->keuangan->count_all_keluar(),
						"recordsFiltered" => $this->keuangan->count_filtered_keluar(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function setujuiKeuangan($id, $data)
	{
		if($this->session->jenis == 'supervisor'){
			$this->keuangan->ubahDatakeuangan(array('id_keuangan' => $id), array('persetujuan' => $data));
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => FALSE));
		}
	}

	public function detailkeuangankeluar($id){
		$data = $this->keuangan->detailDataKeuanganKeluar($id);
		echo json_encode($data);
	}

	public function detailkeuanganmasuk($id){
		$data = $this->keuangan->detailDataKeuanganMasuk($id);
		echo json_encode($data);
	}

}
?>