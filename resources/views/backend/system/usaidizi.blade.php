@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">
                            MWONGOZO WA KUTUMIA
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-end">
                                <a href="{{url('orodha_majina')}}"><button class="btn btn-info btn-sm mr-2">Orodha ya majina</button></a>
                                <a href="{{url('orodha_familia')}}"><button class="btn btn-info btn-sm mr-2">Familia</button></a>
                                <a href="{{url('orodha_wanafamilia')}}"><button class="btn btn-info btn-sm mr-0">Wanafamilia</button></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary"><b>a). Upakiaji wa majina</b></h5><hr>
                            <p>Hakikisha una orodha ya majina yenye taarifa zifuatazo</p>
                            <ul>
                                <li>Jina kamili, Jina la jumuiya ( kama lionekanavyo kwenye mfumo ) atokayo..</li>
                                <li>Namba ya simu mfano (0xxxxxxxxx)</li>
                                <li>Jinsia ya mhusika (Mwanaume au Mwanamke)</li>
                                <li>Cheo cha familia (Baba, Mama, Mtoto au Wengineo)</li>
                                <li>Bonyeza kitufe cha orodha ya majina kisha bofya pakia majina</li>
                            </ul>

                            <h5 class="text-primary"><b>b). Utengenezaji wa familia</b></h5><hr>
                            <ul>
                                <li>Fungua mfumo ukurasa wa usaidizi kisha Bonyeza kitufe cha familia</li>
                                <li>Bofya kitufe cha chagua majina, chagua majina ya viongozi wa familia kisha wasilisha..</li>
                                <li>Ili kuunda familia bofya kitufe cha tengeneza, mfumo utaongeza familia katika kanzidata yake..</li>
                            </ul>

                            <h5 class="text-primary"><b>c). Utengenezaji wa wanafamilia</b></h5><hr>
                            <ul>
                                <li>Bofya kitufe cha wanafamilia, chagua kiongozi wa familia kutoka orodha ya majina ya familia..</li>
                                <li>Fanya uchaguzi wa majina ya wanafamilia kutoka kwenye orodha</li>
                                <li>Bofya kitufe cha wasilisha ili kuhifadhi majina</li>
                                <li>Bofya kitufe cha tengeneza ili kuhifadhi majina kwenye kanzidata ya mfumo..</li>
                            </ul>

                        </div>
                        <div class="col-md-4">
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
@endsection