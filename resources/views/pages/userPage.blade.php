@extends('layout.base')

@section('content')
<div class="block-header">
    <div class="row clearfix">
        <div class="col-md-6 col-sm-12">
            <h2>User Page</h2>
        </div>
        <div class="col-md-6 col-sm-12 text-right hidden-xs">
            <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-round" title="" data-toggle="modal"
                data-target="#immModal" data-content-url="/registerModal">Add New User</a>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <h5>User Data</h5>
        @if (!empty($user_list))
        <div class="table-responsive">
            <table class="table table-hover table-custom spacing8">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_list as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td><button class="btn btn-primary">Edit</button>
                            @if(!Auth::user()->haveRole('admin'))<button
                                class="btn btn-primary">Delete</button>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>No Data Available</p>
        @endif
    </div>
</div>

@stop