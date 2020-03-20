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
                            <label for="segundaTorre" class="col-md-4 control-label">{{ __('Código da segunda torre') }}</label>
                            <div class="col-md-6">
                                <input id="segundaTorre" type="text" class="form-control" name="segundaTorre" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dateFilter" class="col-md-4 control-label">{{ __('Data') }}</label>
                            <div class="col-md-6">
                                <input id="dateFilter" type="text" class="form-control" name="datefilter" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <!--<button type="submit" class="btn btn-success btn-fw">-->
                            <button id="compareTowers" name="compareTowers">
                                <i class="mdi mdi-upload"></i>{{ __('Chamar R') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="tab">
                        @foreach($grouped as $nome => $torres)
                            <button class="tablinks" onmouseover="showSites(event, '{{ $nome }}')">{{ $nome }}</button>
                        @endforeach
                    </div>
                  
                    @foreach($grouped as $nome => $torres)
                        <div id="{{ $nome }}" class="tabcontent">
                            @foreach($torres as $torre)
                                <div class="row">
                                    <div class="stretch-card grid-margin" style=" width: 100%;">
                                        <div class="card bg-gradient-info card-img-holder text-white">
                                            <div class="card-body">
                                                <h4 class="font-weight-normal mb-3">{{ $torre->ESTACAO }}</h4>
                                                <h2 class="mb-5">{{ $torre->SITENAME }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="loading"></div>
@endsection
