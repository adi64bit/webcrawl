@extends('layout.base')

@section('content')
<div class="block-header">
    <div class="row clearfix">
        <div class="col-md-6 col-sm-12">
            <h2>Domain Page</h2>
        </div>
        <div class="col-md-6 col-sm-12 text-right hidden-xs">
            <!-- <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-round" title="">Add New</a> -->
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card planned_task">
            <div class="body">
                <form action="{{ url(action('MainController@insertDomain')) }}" method="post" id="insertDomain">
                    {{ csrf_field() }}
                    <div class="input-group input-group-lg mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">http://www.</span>
                        </div>
                        <input type="text" class="form-control" name="url" placeholder="Insert Domain" aria-label="Insert Domain"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Add To Queue</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <!-- {{ dd($mobileResult->getFormattedResults()) }} -->
        <h5>Domain Data</h5>
        <div class="table-responsive">
            <table class="table table-hover table-custom spacing8">
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>First Check</th>
                        <th>Last Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domain_list as $domain)
                    <tr>
                        <td>{{ $domain->url }}</td>
                        <td>{{ $domain->created_at }}</td>
                        <td>{{ $domain->updated_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@stop