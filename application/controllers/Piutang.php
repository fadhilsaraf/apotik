<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('piutang_model','piutang');
	}

	public function index()
	{
		if($this->session->jenis == 'kasir'){
			$this->load->view('header/navigation_kasir');
		}else if($this->session->jenis == 'supervisor'){
			$this->load->view('header/navigation_supervisor');
		}else if ($this->session->jenis == 'logistik'){
			redirect('/stok');
		}else{
			redirect();
		}
		$this->load->view('piutang/piutang_view');
}

	public function mengambilPiutang()
	{
		$list = $this->piutang->mengambilDataPiutang();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $piutang) {
			$no++;
			$row = array();
			$row[] = $piutang->kontak;
			$row[] = $piutang->jumlah_piutang;
			$row[] = $piutang->jumlah_terbayar;
			$row[] = $piutang->status;
			$row[] = $piutang->tanggal;
			$row[] = $piutang->waktu;
			$row[] = $piutang->persetujuan;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ubah_piutang('."'".$piutang->id_piutang."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="bayar_piutang('."'".$piutang->id_piutang."'".')"><i class="glyphicon glyphicon-book"></i> Bayar</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Setujui" onclick="setujui_piutang('."'".$piutang->id_piutang."',"."'setuju'".')"><i class="glyphicon glyphicon-ok"></i> Stuju</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Tidak_setujui" onclick="setujui_piutang('."'".$piutang->id_piutang."',"."'tidak'".')"><i class="glyphicon glyphicon-remove"></i> Tidak Setuju</a>';

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->piutang->count_all(),
						"recordsFiltered" => $this->piutang->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->piutang->get_by_id($id);
		echo json_encode($data);
	}

	public function tambahPiutang()
	{
		$data = array(
				'kontak' => $this->input->post('kontak'),
				'jumlah_piutang' => $this->input->post('jumlah_piutang'),
				'jumlah_terbayar' => '0',
				'status' => 'belum',
				'tanggal' => date('Y-m-d'),
				'waktu' => date('h:i:s'),
				'persetujuan' => "belum",
			);
		$insert = $this->piutang->tambahDataPiutang($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ubahPiutang()
	{
			$data = array(
					'kontak' => $this->input->post('kontak'),
					'jumlah_piutang' => $this->input->post('jumlah_piutang'),
					'jumlah_terbayar' => $this->input->post('jumlah_terbayar'),
					'status' => 'belum',
					'tanggal' => date('Y-m-d'),
					'waktu' => date('h:i:s'),
					'persetujuan' => 'belum',
			);
			$this->piutang->ubahDataPiutang(array('id_piutang' => $this->input->post('id_piutang')), $data);
			echo json_encode(array("status" => TRUE));
	}

	public function bayarPiutang()
	{
			$status = 'belum';
			$jumlah_bayar = (int) $this->input->post('jumlah_bayar');
			$jumlah_terbayar = ((int) $this->input->post('jumlah_terbayar')) + $jumlah_bayar;
			$jumlah_piutang = ((int) $this->input->post('jumlah_piutang'));

			if($jumlah_terbayar >= $jumlah_piutang){
				$status = 'sudah';
			}
			$data = array(
					'jumlah_piutang' => $this->input->post('jumlah_piutang'),
					'jumlah_terbayar' => (string) $jumlah_terbayar,
					'status' => $status,
					'tanggal' => date('Y-m-d'),
					'waktu' => date('h:i:s'),
					'persetujuan' => 'belum',
			);
			$this->piutang->ubahDataPiutang(array('id_piutang' => $this->input->post('id_piutang')), $data);

			$data = array(
				'jenis' => 'masuk',
				'sumber' => 'piutang',
				'tanggal' => date('Y-m-d'),
				'waktu' => date('h:i:s'),
				'persetujuan' => 'belum'
			);

			$insert = $this->keuangan->tambahDataKeuangan($data);
			$id_keuangan = $this->keuangan->get_last_id()->id_keuangan;

			if($jumlah_bayar != ''){
				$data = array(
					'id_keuangan' => $id_keuangan,
					'alat_transaksi' => 'tunai',
					'jumlah_uang' => $jumlah_bayar,
					'kartu' => '',
					'nomor_transaksi' => '',
					'nomor_kartu' => '',
				);
				$insert = $this->keuangan->tambahDataKeuanganMasuk($data);
			}

			echo json_encode(array("status" => TRUE));
	}

	public function setujuiPiutang($id, $data)
	{
		if($this->session->jenis == 'supervisor'){
			$this->piutang->ubahDataPiutang(array('id_piutang' => $id), array('persetujuan' => $data));
			echo json_encode(array("status" => TRUE));
		}else{
			echo json_encode(array("status" => FALSE));
		}
	}


}
