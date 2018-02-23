

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Piutang</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_piutang()"><i class="glyphicon glyphicon-plus"></i> Tambah Piutang</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Kontak</th>
                        <th>Jumlah Piutang</th>
                        <th>Jumlah Terbayar</th>
                        <th>Status Lunas</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Persetujuan</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Kontak</th>
                    <th>Jumlah Piutang</th>
                    <th>Jumlah Terbayar</th>
                    <th>Status Lunas</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Persetujuan</th>
                    <th style="width:125px;">Action</th>
                </tr>
                </tfoot>
            </table>
            </center>

            <br />
            <br />
            <br />
        </div>
      </div>
  </body>



<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script src="<?php echo base_url('assets/datatables/js/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/jszip.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/vfs_fonts.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/ajax/buttons.print.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": '<"show"l><"search"f><"container">rt<"tombol"B><"container"><"info"i><"container"><"halaman"p>',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('piutang/mengambilPiutang')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });

});

function tambah_piutang()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Piutang'); // Set Title to Bootstrap modal title


    $('[name="kontak"]').attr('disabled', false);
    $('[name="jumlah_bayar"]').attr('disabled', true);
    $('[name="jumlah_terbayar"]').attr('disabled', true);
}

function bayar_piutang(id)
{
    save_method = 'bayar';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    $('[name="jumlah_bayar"]').attr('disabled', false);

    $.ajax({
        url : "<?php echo site_url('piutang/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_piutang"]').val(data.id_piutang);
            $('[name="kontak"]').val(data.kontak);
            $('[name="kontak"]').attr('disabled', true);
            $('[name="jumlah_piutang"]').val(data.jumlah_piutang);
            $('[name="jumlah_piutang"]').attr('disabled', true);
            $('[name="jumlah_terbayar"]').val(data.jumlah_terbayar);
            $('[name="jumlah_terbayar"]').attr('disabled', true);
            $('[name="jumlah_bayar"]').val('0');
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Bayar Piutang'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });

}

function ubah_piutang(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    $('[name="jumlah_piutang"]').attr('disabled', false);
    $('[name="kontak"]').attr('disabled', false);

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('piutang/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_piutang"]').val(data.id_piutang);
            $('[name="kontak"]').val(data.kontak);
            $('[name="jumlah_piutang"]').val(data.jumlah_piutang);
            $('[name="jumlah_terbayar"]').val(data.jumlah_terbayar);
            $('[name="jumlah_terbayar"]').attr('disabled', true);
            $('[name="jumlah_bayar"]').attr('disabled', true);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Ubah Piutang'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function simpan()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('piutang/tambahPiutang')?>";
    } else if (save_method == 'update'){
      $('[name="jumlah_terbayar"]').attr('disabled', false);
      $('[name="jumlah_bayar"]').attr('disabled', false);
      url = "<?php echo site_url('piutang/ubahPiutang')?>";
    } else if(save_method == 'bayar'){
      $('[name="jumlah_piutang"]').attr('disabled', false);
      $('[name="jumlah_terbayar"]').attr('disabled', false);
      url = "<?php echo site_url('piutang/bayarPiutang')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }

            $('#btnSave').text('simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}


function setujui_piutang(id, setujui){

  if(confirm('Apakah anda yakin untuk '+setujui+' ?'))
  {
      // ajax delete data to database
      $.ajax({
          url : "<?php echo site_url('piutang/setujuiPiutang')?>/"+id+"/"+setujui,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
              //if success reload ajax table
              if(data.status){
                $('#modal_form').modal('hide');
                reload_table();
              }else{
                alert('harus supervisor');
              }
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error menyetujui data');
          }
      });

  }

}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_piutang"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Kontak</label>
                            <div class="col-md-9">
                                <textarea name="kontak" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah Piutang</label>
                            <div class="col-md-9">
                                <input name="jumlah_piutang" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah Terbayar</label>
                            <div class="col-md-9">
                                <input name="jumlah_terbayar" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah Dibayar</label>
                            <div class="col-md-9">
                                <input name="jumlah_bayar" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="simpan()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- End Bootstrap modal -->
</body>
</html>
