<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('retur_model','retur');
		$this->load->model('stok_model','stok');
                $this->load->model('produk_model', 'produk');
	}

	public function retur_pembelian()
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
		$this->load->view('retur/retur_pembelian_view');
	}

	public function retur_konsinyasi()
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
		$this->load->view('retur/retur_konsinyasi_view');
	}

	public function retur_penjualan()
	{
		if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/sisik-ikan/stok');
		}else{
			redirect();
		}
		$this->load->view('retur/retur_penjualan_view');
	}
        
        public function ajax_get_by_nama($nama)
	{
		$nama = str_replace('%20', ' ', $nama);
		$data = $this->produk->get_by_nama($nama);
		echo json_encode($data);
	}

	public function mengambilReturPembelian()
	{
		$list = $this->retur->mengambilDataReturPembelian();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $retur) {
			$no++;
			$row = array();
			$row[] = $retur->nama_produk;
			$row[] = $retur->nama_distributor;
			$row[] = $retur->jumlah;
			$row[] = $retur->keterangan;
			$row[] = $retur->tanggal;
			$row[] = $retur->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->retur->count_all_retur_pembelian(),
						"recordsFiltered" => $this->retur->count_filtered_retur_pembelian(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function mengambilReturPenjualan()
	{
		$list = $this->retur->mengambilDataReturPenjualan();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $retur) {
			$no++;
			$row = array();
			$row[] = $retur->nama_produk;
			$row[] = $retur->nama_distributor;
			$row[] = $retur->jumlah;
			$row[] = $retur->keterangan;
			$row[] = $retur->tanggal;
			$row[] = $retur->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->retur->count_all_retur_penjualan(),
						"recordsFiltered" => $this->retur->count_filtered_retur_penjualan(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function mengambilReturKonsinyasi()
	{
		$list = $this->retur->mengambilDataReturKonsinyasi();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $retur) {
			$no++;
			$row = array();
			$row[] = $retur->nama_produk;
			$row[] = $retur->nama_distributor;
			$row[] = $retur->jumlah;
			$row[] = $retur->keterangan;
			$row[] = $retur->tanggal;
			$row[] = $retur->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_retur('."'".$retur->id_retur."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->retur->count_all_retur_konsinyasi(),
						"recordsFiltered" => $this->retur->count_filtered_retur_konsinyasi(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->retur_pembelian->get_by_id($id);
		echo json_encode($data);
	}

	public function tambahReturPembelian()
	{
			$id_stok = '';
			$id_produk = explode("|", $this->input->post('id_produk'));
			$kode_produksi = $this->input->post('kode_produksi');

			$list = $this->stok->get_by_kode_produksi_id_produk($id_produk[0], $kode_produksi);

			$id_stok = $list->id_stok;

			if($id_stok != ''){
				$data = array(
						'jenis' => 'pembelian',
						'id_stok' => $id_stok,
						'jumlah' => $this->input->post('jumlah'),
						'keterangan' => $this->input->post('keterangan'),
						'tanggal' => date("Y-m-d"),
						'persetujuan' => 'belum',
				);

				$insert = $this->retur->tambahDataRetur($data);


				$data =	'jumlah - '.$this->input->post('jumlah');

				$insert = $this->stok->ubahDataStokTerRetur($id_stok, $data);

				echo json_encode(array("status" => TRUE));
			}else{
				echo json_encode(array("status" => FALSE));
			}

	}

	public function tambahReturPenjualan()
	{
		$id_stok = '';
		$id_produk = explode("|", $this->input->post('id_produk'));
		$kode_produksi = $this->input->post('kode_produksi');

		$list = $this->stok->get_by_kode_produksi_id_produk($id_produk[0], $kode_produksi);

		$id_stok = $list->id_stok;

		if($id_stok != ''){
			$data = array(
					'jenis' => 'penjualan',
					'id_stok' => $id_stok,
					'jumlah' => $this->input->post('jumlah'),
					'keterangan' => $this->input->post('keterangan'),
					'tanggal' => date("Y-m-d"),
					'persetujuan' => 'belum',
			);
			$insert = $this->retur->tambahDataRetur($data);

			$data =	'jumlah + '.$this->input->post('jumlah');

			$insert = $this->stok->ubahDataStokTerRetur($id_stok, $data);
			
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => FALSE));
		}

	}

	public function setujuiRetur($id, $data)
	{
		if($this->session->jenis == 'supervisor'){
			$this->retur->ubahDataRetur(array('id_retur' => $id), array('persetujuan' => $data));
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => TRUE));
		}
	}

}
