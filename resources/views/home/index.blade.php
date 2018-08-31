<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
    <title>Todolist</title>
</head>
<body>

    <main class="container" style="margin-top:100px;">
        <button class="btn btn-primary btn-add" style="margin-bottom:30px;">Add new</button>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
      
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
      
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none;"></div>
              <form id="form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" required class="form-control">
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary"></button>
                    </div>
              </form>
            </div>
      
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
      
          </div>
        </div>
      </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="{{asset('js/datatables.min.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.table').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{url('get-data')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'actions', name: 'actions',
                        searchable: false,
                        orderable: false,
                    }
                ]
            });

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.btn-add', function(e) {
                e.preventDefault();
                $('.modal-title').text('Simpan Data');
                $('.modal-body').find('.btn-primary').addClass('btn-store');
                $('.modal-body').find('.btn-primary').text('Simpan');
                $('#myModal').modal('show');
            });

            $(document).on('click', '.btn-store', function(e) {
                e.preventDefault();
                let name = $('#name').val();
                let email = $('#email').val();
                $.ajax({
                    url: "{{url('todolist')}}",
                    type: "POST",
                    dataType: "JSON",
                    data: {name, email}
                }).done(function(data) {
                    if(data.errors){
                        $('.alert').html('');
                        $('.alert').show();
                        $.each(data.errors, function(key, value) {
                            $('.alert').html(value);
                        });
                    }else{
                        document.getElementById('form').reset();
                        $('#myModal').modal('hide');
                        $('.alert').hide();
                        swal("Poof! data berhasil ditambah!", {
                            icon: "success",
                        });
                        $('.table').DataTable().ajax.reload();
                    }
                })
            });

            $(document).on('click', '.btn-edit', function(e) {
                e.preventDefault();
                id = $(this).attr('data-id');
                $('.modal-title').text('Edit Data');
                $('.modal-body').find('.btn-primary').removeClass('btn-store');
                $('.modal-body').find('.btn-primary').addClass('btn-update');
                $('.modal-body').find('.btn-primary').text('Update');
                $('#name').val($(this).attr('data-name'));
                $('#email').val($(this).attr('data-email'));
                $('#myModal').modal('show');
            });

            $(document).on('click', '.btn-update', function(e) {
                e.preventDefault();
                let name = $('#name').val();
                let email = $('#email').val();
                $.ajax({
                    url: "{{url('todolist')}}" + '/' + id,
                    type: "PUT",
                    dataType: "JSON",
                    data: {name, email}
                }).done(function(data) {
                    if(data.errors){
                        $('.alert').html('');
                        $('.alert').show();
                        $.each(data.errors, function(key, value) {
                            $('.alert').html(value);
                        });
                    }else{
                        document.getElementById('form').reset();
                        $('#myModal').modal('hide');
                        $('.alert').hide();
                        swal("Poof! data berhasil diedit!", {
                            icon: "success",
                        });
                        $('.table').DataTable().ajax.reload();
                    }
                })
            })

            $(document).on('click', '.btn-destroy', function(e){
                e.preventDefault();
                let id = $(this).attr('data-id');

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{url('todolist')}}" + '/' + id,
                            type: "POST",
                            data: {"_method" : "DELETE"}
                        }).done(function() {
                            swal("Poof, data berhasil dihapus", {
                                icon: "success"
                            });
                            $('.table').DataTable().ajax.reload();
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });
            })
        });
    </script>
</body>
</html>