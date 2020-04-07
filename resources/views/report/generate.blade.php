@extends('layouts.app')

@section('content')
    <div id="loading"></div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('Digite os códigos das torres') }}</h4>
                    <form class="mt-5" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="primeiraTorre" class="col-md-4 control-label">{{ __('Código da primeira torre') }}</label>
                            <div class="col-md-6">
                                <input id="primeiraTorre" type="text" class="form-control" name="primeiraTorre" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dateFilter" class="col-md-4 control-label">{{ __('Data') }}</label>
                            <div class="col-md-6">
                                <input id="dateFilter" type="text" class="form-control" name="datefilter" autocomplete="off" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <!--<button type="submit" class="btn btn-success btn-fw">-->
                            <button id="generateTowers" name="generateTowers">
                                <i class="mdi mdi-upload"></i>{{ __('Chamar R') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('report.sites')
@endsection