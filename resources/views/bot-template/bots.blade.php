@extends("superadmin.layouts.master")
@section("body")
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card">
        <div class="card-body">
            <i class="fas fa-robot float-left mr-3" style="line-height: 29px; font-size: 25px;"></i>
            <h4 class="h4 m-0 text-gray-800 float-left">All Bots</h4>
            <button type="button" class="btn btn-primary float-right">Create New Bot</button>
        </div>
    </div>

    <div class="row mt-4">
        @foreach ($data['botsDatas'] as $botDatas)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card bots-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center my-4">
                            <div class="bot-img-div">
                                {{-- <img src="img/{{ $botDatas->image }}" class="bot-card-img"> --}}
                                <img src="{{ asset('img/'.$botDatas->image ) }}" class="bot-card-img">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4 bot-name">
                            <h6 class="m-0">{{ $botDatas->name }}</h6>
                        </div>
                        <div class="d-flex justify-content-center my-4 bot-actions">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-warning">
                                <a href="{{ route('edit.template', $botDatas->id) }}"><i class="fas fa-project-diagram"></i></a>
                            </button>
                            <button type="button" class="btn btn-info">
                                <i class="fas fa-comments"></i>
                            </button>
                            <button type="button" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
<!-- /.container-fluid -->
@endsection
