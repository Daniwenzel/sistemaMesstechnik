@extends('layouts.app')

@section('content')
    <div id="loading"></div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                     <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">{{ __('Utilizando arquivos EPE') }}</h4>

                            <form name="plotsCorrelacao" method="POST" action="{{ route('reports.compareepe') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="primeiroEpe">
                                </div>
                                <div class="form-group">
                                    <input type="file" name="segundoEpe">
                                </div>
                            </form>
                        </div>
                        <div class="col-6">
                            <h4 class="card-title">{{ __('Ou os códigos das estações') }}</h4>
                            <div class="form-group">
                                <label for="primeiraTorre" class="col-md-4 control-label">{{ __('Estação da 1ª torre') }}</label>
                                <div class="col-md-6">
                                    <input id="primeiraTorre" type="text" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="segundaTorre" class="col-md-4 control-label">{{ __('Estação da 2ª torre') }}</label>
                                <div class="col-md-6">
                                    <input id="segundaTorre" type="text" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dateFilter" class="col-md-4 control-label">{{ __('Data') }}</label>
                                <div class="col-md-6">
                                    <input id="dateFilter" type="text" class="form-control" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <button id="compareTowers" name="compareTowers">
                                <i class="mdi mdi-upload">{{ __('Gerar Plots') }}</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('report.sites')
@endsection
