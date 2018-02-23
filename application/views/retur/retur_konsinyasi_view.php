

        <div class="w3-container">
            <center>
              <h1 style="font-size:20pt">Retur Konsinyasi</h1>
            </center>
            <button class="btn btn-success" onclick="tambah_retur()"><i class="glyphicon glyphicon-plus"></i> Tambah Retur Konsinyasi</button>
            <br />
            <br />
            <br />
            <center>
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Distributor</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Persetujuan</th>
                        <th style="width:125px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th>Produk</th>
                    <th>Distributor</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
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

$(document).ready(

    function() {

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
            "url": "<?php echo site_url('retur/mengambilReturKonsinyasi')?>",
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

    $.ajaxSetup({ cache: false });

    $('#search').keyup(
      function(){
        $('#result').html('');
        var searchField = $('#search').val();
        var expression = new RegExp(searchField, "i");

        $.getJSON( "<?php echo site_url('retur/ajax_get_by_nama')?>/"+searchField,
        function(data) {
          $.each(data, function(key, value){
            if (value.id_produk.search(expression) != -1 || value.nama.search(expression) != -1)
            {
               $('#result').append('<li class="list-group-item link-class">'+value.id_produk+' | <span class="text-muted">'+value.nama+'</span></li>');
            }
          });
        });
      });

      $('#result').on('click', 'li', function() {
        var click_text = $(this).text();
        $('#search').val(click_text);
        $("#result").html('');
      });
});

function tambah_retur()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Retur Konsinyasi'); // Set Title to Bootstrap modal title

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
        url = "<?php echo site_url('retur/tambahReturKonsinyasi')?>";
    } else {
        url = "<?php echo site_url('retur/ubahRetur')?>";
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
            alert('Error adding / update data ');
            $('#btnSave').text('simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}


function setujui_retur(id, setujui){

  if(confirm('Apakah anda yakin untuk '+setujui+' ?'))
  {
      // ajax delete data to database
      $.ajax({
          url : "<?php echo site_url('retur/setujuiRetur')?>/"+id+"/"+setujui,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
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
                <h3 class="modal-title">Retur Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_retur"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Produk</label>
                            <div class="col-md-9">
                                <input type="text" name="id_produk" id="search" class="form-control"/>
                                <ul class="list-group" id="result">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Produksi</label>
                            <div class="col-md-9">
                                <input name="kode_produksi" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">jumlah</label>
                            <div class="col-md-9">
                                <input name="jumlah" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                              <textarea name="keterangan" class="form-control" rows="8" cols="80"></textarea>
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
