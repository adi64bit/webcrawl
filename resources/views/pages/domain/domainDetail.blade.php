@extends('layout.base')

@section('content')
<div class="block-header">
    <div class="row clearfix">
        <div class="col-md-6 col-sm-12">
            <h2>Domain Detail : {{ $domain->url }}</h2>
        </div>
        <div class="col-md-6 col-sm-12 text-right hidden-xs">
            <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-round" title="">Re-Test</a>
            <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-round" title="">Compare Result</a>
        </div>
    </div>
</div>
<div class="row clearfix">
    
</div>
@stop