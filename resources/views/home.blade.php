@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Royal Donor</div>
             
                @if (Session::has('status'))
                <div class="alert alert-{{Session::get('status')}}">
                    <p>{{Session::get('message')}}</p>
                </div>
                @endif
                <div class="panel-body">
                    Welcome To Royal Donor Front End Page.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
