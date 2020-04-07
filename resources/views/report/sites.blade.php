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