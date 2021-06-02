@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('files/upload')}}" class="dropzone" id="dropzone-dados">
                    @csrf
                </form>
                <button type="button">teste</button>
            </div>
        </div>
    </div>
@endsection