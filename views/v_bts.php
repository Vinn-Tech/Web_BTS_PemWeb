<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
 integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

<div class="row">

 <div class="col-md-12">
  <h1>Data BTS</h1>
  <hr>
  <div class="container-lg ">
   <div class="card  p-5 m-5">
    <label for="kelurahan">Filter Kelurahan</label>
    <select class="form-control mb-5" id="kelurahan" style="width:20%">
     <option value="">-Pilih-</option>
    </select>
    <script>
    $(function() {
     $('#kelurahan').select2({
      ajax: {
       url: 'controller/ComboBox.php',
       type: 'post',
       dataType: 'json',
       delay: 250,
       data: function(params) {
        return {
         act: 'getKelurahan',
         search: (params.term || ' ')
        };
       },
       processResults: function(data, params) {

        var results = [];
        $.each(data, function(index, item) {
         results.push({
          id: item.id,
          text: item.text
         });
        });
        return {
         results: results
        };
       },
       cache: true
      },
      placeholder: '-Pilih-',
     });
    });
    </script>
   </div>
   <div class="card p-5 m-5">
    <div class="table-responsive" style="width:100%">

     <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
     <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
     <table id="datatable" class="table table-striped table-bordered" style="width:100%">
      <thead>
       <tr>
        <th>ID BTS</th>
        <th>Keluarahan Desa</th>
        <th>Kabupaten Kota</th>
        <th>Provinsi</th>
        <th>Luas Desa</th>
        <th>Total Network Element</th>
        <th>Rasio Network Element</th>
        <th>Total Network Element 4G</th>
        <th>Rasio Network Element 4G</th>
        <th>Kecamatan</th>
       </tr>
      </thead>
      <tbody>
      </tbody>
     </table>
    </div>
   </div>
  </div>


 </div>
</div>

<script>
$(document).ready(function() {

 $('#datatable').DataTable({
  processing: true,
  serverSide: true,
  ajax: {
   url: 'controller/BtsController.php',
   type: 'POST',
  },
  columns: [{
    data: 'id_bts'
   },
   {
    data: 'kel_des'
   },
   {
    data: 'kab_kota'
   },
   {
    data: 'prov'
   },
   {
    data: 'luas_desa',
    render: function(data, type, row, meta) {
     return parseFloat(data).toFixed(2) + ' KM';
    }
   },
   {
    data: 'total_ne'
   },
   {
    data: 'rasio_ne'
   },
   {
    data: 'total_ne_4g'
   },
   {
    data: 'rasio_ne_4g'
   },
   {
    data: 'kec'
   }
  ]
 });
})
</script>