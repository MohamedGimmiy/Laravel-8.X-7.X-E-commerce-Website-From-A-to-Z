@extends('admin_layout\admin')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Products</h3>
              </div>

              @if ($message = Session::get('success'))
              <div class="alert alert-success">{{ $message }}</div>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Num.</th>
                    <th>Picture</th>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Product Price</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($products as $key => $product)
                        
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>
                          <img src="{{asset('storage/product_images/'.$product->product_image)}}" style="height : 50px; width : 50px" class="img-circle elevation-2" alt="User Image">
                      </td>
                      <td>{{$product->product_name}}</td>
                      <td> {{$product->product_category}}</td>
                      <td>${{$product->product_price}}</td>
                      <td>
                        @if ($product->status == 1)
                            <a href="{{url('/unactivate_product/'.$product->id)}}" class="btn btn-success">Unactivate</a>
                        @else
                            <a href="{{url('/activate_product/'.$product->id)}}" class="btn btn-warning">Activate</a>
                        @endif
                        <a href="{{url('/editproduct/'.$product->id)}}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a>
                        <a href="{{url('/deleteproduct/'.$product->id)}}" id="delete" class="btn btn-danger" ><i class="nav-icon fas fa-trash"></i></a>
                      </td>
                    </tr>
                    @endforeach

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Num.</th>
                    <th>Picture</th>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Product Price</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


@section('style')
    <link rel="stylesheet" href="back-end/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
@endsection

@section('scripts')
<script src="back-end/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="back-end/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="back-end/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="back-end/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- AdminLTE App -->
<script src="back-end/dist/js/adminlte.min.js"></script>

<script src="back-end/dist/js/bootbox.min.js"></script>

<script>
    $(document).on("click", "#delete", function(e){
    e.preventDefault();
    var link = $(this).attr("href");
    bootbox.confirm("Do you really want to delete this element ?", function(confirmed){
      if (confirmed){
          window.location.href = link;
        };
      });
    });
  </script>
  <script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
@endsection
