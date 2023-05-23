@extends("superadmin.layouts.master")
<style>
    #container {
    height: 300px;
    min-width: 310px;
    max-width: 800px;
}
</style>
@section("body")
@if(session()->has('message'))
    <!-- <div class="alert alert-danger">
        {{ session()->get('message') }}
    </div> -->
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong>Oops !</strong> {{ session('message') }}
    </div>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card shadow h-100 py-4">
                <div class="card-body">
                    <div class="row" style="margin-bottom: 2rem">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form  method="post" class="form-inline" action="{{ route('dashboard.download') }}" id="dformsubmit">
                            {{ csrf_field() }}
                                <div class="form-group" >
                                    <label for="viber_type"  > Choose Portal : </label>
                                    <select class="form-control ml-2 mr-5" name="viber_portal" required="required">
                                        <option selected="selected">Select Portal</option>
                                        <option value="portal_one">Portal One</option>
                                        <option value="portal_two">Portal Two</option>
                                        <option value="roche">Roche</option>
                                        <option value="yoma">Yoma</option>
                                    </select>
                                </div>
                                <div class="form-group" >
                                    <label for="start_date"  > Start Date : </label>
                                    <input type="date" name="start_date" class="form-control ml-2 mr-5" id="start_date" required="required" />
                                </div>
                                <div class="form-group ml-10" >
                                    <label for="end_date" > End Date : </label>
                                    <input type="date" name="end_date" class="form-control ml-2 mr-5" id="end_date" required="required"/>
                                </div>
                                <div class="form-group">
                                    <!-- <input type="submit" value="Download" id="vldfilter" class="btn-primary btn ml-10" style="background-color: #7360f2 !important;"/> -->
                                    <input type="submit" class="btn-primary btn ml-10" id="filter" value="Download" style="background-color: #7360f2 !important;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


@endsection

