<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_opname extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('stok_opname_model','stok_opname');
		$this->load->model('stok_model','stok');
		$this->load->model('produk_model','produk');
	}

	public function index()
	{
		if($this->session->jenis == 'logistik'){
			$this->load->view('header/navigation_logistik');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if($this->session->jenis == 'kasir'){
			redirect('/penjualan');
		}else{
			redirect();
		}
		$this->load->view('stok_opname/stok_opname_view');
}

	public function mengambilStokOpname()
	{
		$list = $this->stok_opname->mengambilDataStokOpname();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $stok_opname) {
			$no++;
			$row = array();
			$row[] = $stok_opname->nama_produk;
			$row[] = $stok_opname->kode_produksi;
			$row[] = $stok_opname->jumlah;
			$row[] = $stok_opname->tanggal;
			$row[] = $stok_opname->waktu;
			$row[] = $stok_opname->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_stok_opname('."'".$stok_opname->id_stok_opname."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_stok_opname('."'".$stok_opname->id_stok_opname."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_stok_opname('."'".$stok_opname->id_stok_opname."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->stok_opname->count_all(),
						"recordsFiltered" => $this->stok_opname->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->stok_opname->get_by_id($id);
		echo json_encode($data);
	}
        
        public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->produk->get_by_nama($nama);
		echo json_encode($data);
	}

	public function tambahStokOpname()
	{
		$id_produk = explode("|", $this->input->post('id_produk'));
		$kode_produksi = $this->input->post('kode_produksi');

		$list = $this->stok->get_by_kode_produksi_id_produk($id_produk[0], $kode_produksi);

		$id_stok = $list->id_stok;

		$data = array(
				'id_stok' => $id_stok,
				'jumlah' => $this->input->post('jumlah'),
				'tanggal' => date("Y-m-d"),
				'waktu' => date("h:i:s"),
				'persetujuan' => 'belum'
			);

		$insert = $this->stok_opname->tambahDataStokOpname($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahStokOpname()
	{
		$id_produk = explode("|", $this->input->post('id_produk'));
		$kode_produksi = $this->input->post('kode_produksi');

		$list = $this->stok->get_by_kode_produksi_id_produk($id_produk[0], $kode_produksi);

		$id_stok = $list->id_stok;

		$data = array(
				'id_stok' => $id_stok,
				'jumlah' => $this->input->post('jumlah'),
				'tanggal' => date("Y-m-d"),
				'waktu' => date("h:i:s"),
				'persetujuan' => 'belum'
			);
		$this->stok_opname->ubahDataStokOpname(array('id_stok_opname' => $this->input->post('id_stok_opname')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function setujuiStokOpname($id, $data)
	{
		if($this->session->jenis == 'supervisor'){
			$this->stok_opname->ubahDataStokOpname(array('id_stok_opname' => $id), array('persetujuan' => $data));
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => TRUE));
		}
	}


}
