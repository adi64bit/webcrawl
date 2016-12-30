@extends('layout.basePage')

@section('titlePage')
User Page
@stop

@section('afterTitle')
<button class="btn btn-info btn-fill" data-toggle="modal" data-target="#immModal" data-content-url="registerModal">Add New User</button>
@stop

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h4 class="title">User Data</h4>
                <p class="category"></p>
            </div>
            @if (!empty($user_list))
            <div class="content table-responsive table-full-width">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>action</th>
                    </tr></thead>
                    <tbody>
                        @foreach($user_list as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td><button class="btn btn-info btn-fill">Edit</button> @if(!Auth::user()->haveRole('admin'))<button class="btn btn-info btn-fill">Delete</button>@endif</td>
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

                
                                       
                                    