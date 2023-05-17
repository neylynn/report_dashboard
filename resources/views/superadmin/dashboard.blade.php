@extends("superadmin.layouts.master")
<style>
    #container {
    height: 300px;
    min-width: 310px;
    max-width: 800px;
}

.small-chart .highcharts-subtitle {
    display: none;
}

.small-chart .highcharts-legend {
    display: none;
}
</style>
@section("body")
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card shadow h-100 py-4">
                <div class="card-body">
                    <!-- <div class="row no-gutters align-items-center"> -->
                    <!-- <select class="form-select form-select-lg" aria-label=".form-select-lg example" style="width: 500px; height:50px;" aria-label="Default select example">
                        <option selected>Select Viber Portal</option>
                        <option value="1">Portal One</option>
                        <option value="2">Portal Two</option>
                        <option value="3">Roche</option>
                        <option value="3">Yoma</option>
                    </select> -->

                    <div class="row" style="margin-bottom: 2rem">
        <div class="col-lg-12 col-md-12 col-sm-12">

            <form  method="post" class="form-inline">
                <div class="form-group" >
                    <label for="vlStartDate"  > Choose Portal : </label>
                    <!-- <select class="form-select form-select-lg" aria-label=".form-select-lg example" aria-label="Default select example">
                        <option selected>Select Viber Portal</option>
                        <option value="1">Portal One</option>
                        <option value="2">Portal Two</option>
                        <option value="3">Roche</option>
                        <option value="3">Yoma</option>
                    </select> -->
                    <select class="form-control ml-2 mr-5" required="required" id="viber_type" name="viber_type">
                        <option selected="selected" value="">Select Portal</option>
                        <option value="portal_one">Portal One</option>
                        <option value="portal_two">Portal Two</option>
                        <option value="roche">Roche</option>
                        <option value="yoma">Yoma</option>
                    </select>
                </div>
                <div class="form-group" >
                    <label for="vlStartDate"  > Start Date : </label>
                    <input type="date" name="vlStartDate" class="form-control ml-2 mr-5" id="vlStartDate" required="required" />
                </div>
                <div class="form-group ml-10" >
                    <label for="vlEndDate" > End Date : </label>
                    <input type="date" name="vlEndDate" class="form-control ml-2 mr-5" id="vlEndDate" required="required"/>
                </div>
                <div class="form-group">
                    <input type="submit" value="Download" id="vldfilter" class="btn-primary btn ml-10" style="background-color: #7360f2 !important;"/>
                </div>
            </form>
        </div>

    </div>
                        <!-- <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <select class="form-select col-md-2" aria-label="Default select example">
                                    <option selected>Select Viber Portal</option>
                                    <option value="1">Portal One</option>
                                    <option value="2">Portal Two</option>
                                    <option value="3">Roche</option>
                                    <option value="3">Yoma</option>
                                </select>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                        </div> -->
                        <!-- <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div> -->
                    <!-- </div> -->
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <!-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Portal Two</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bot_users->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Earnings (Monthly) Card Example -->
        <!-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Roche
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $today_engagement->count() }}</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Pending Requests Card Example -->
        <!-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Yoma 
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user_engagements->pluck('engage_count')->sum() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <!-- <div id="container"></div>

    <button id="large" style="display: none;">Large</button>
    <button id="small" style="display: none;">Small</button>

    <div class="container mt-5">
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div> -->

</div>
<!-- /.container-fluid -->

<script>
    var users = [];
    axios.get('/api/get_users').then(response => {

        // console.log("data",response.data);
        users = response.data.data;
    
        const chart = Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Highcharts responsive chart'
            },
            subtitle: {
                text: 'Resize the frame to see subtitle and legend hide'
            },
            xAxis: {
                // categories: ['nay', 'aung', 'Bananas']
                categories: users
            },
            yAxis: {
                title: {
                    text: 'Amount'
                }
            },
            series: [{
                name: 'Users',
                data: [1, 4, 3, 6]
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        chart: {
                            className: 'small-chart'
                        }
                    }
                }]
            }
        });
    });

    document.getElementById('small').addEventListener('click', () => {
        chart.setSize(400, 300);
    });

    document.getElementById('large').addEventListener('click', () => {
        chart.setSize(600, 300);
    });

    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dashboard') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
    });

</script>

@endsection

