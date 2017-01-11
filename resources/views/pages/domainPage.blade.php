

@extends('layout.basePage')

@section('titlePage')
Domain Page
@stop

@section('afterTitle')
<form action="{{ url(action('MainController@insertDomain')) }}" method="post" class="register-form">
	{{ csrf_field() }}
  <div class="input-group">
    <span class="input-group-addon">http://</span>
    <input name="url" type="text" class="form-control" placeholder="Insert Domain">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        Add To Queue
      </button>
    </div>
  </div>
</form>
@stop

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h4 class="title">Domain Data</h4>
                <p class="category"></p>
            </div>
            @if (!empty($domain_list))
            <div class="content table-responsive table-full-width">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Domain</th>
                        <th>First Check</th>
                        <th>Last Update</th>
                    </tr></thead>
                    <tbody>
                        @foreach($domain_list as $domain)
                        <tr>
                            <td>http://{{ $domain->url }}</td>
                            <td>{{ $domain->created_at }}</td>
                            <td>{{ $domain->update_at }}</td>
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

                
                                       
