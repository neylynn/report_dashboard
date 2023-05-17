@extends("superadmin.layouts.master")
@section("body")
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Content Row -->
    <div class="row">


    </div>

    <div id="container"></div>

    <button id="large" style="display: none;">Large</button>
    <button id="small" style="display: none;">Small</button>

    <!-- Page Heading -->
    <br><h1 class="h3 mb-2 text-gray-800">User Management</h1><br>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-primary">Selected Campaign User Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="user_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
    $(document).ready(function() {

        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            type: "json",
            ajax: {
                url: "{{ route('getadmins') }}",
                type: "get"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

    });
</script>

@endsection

