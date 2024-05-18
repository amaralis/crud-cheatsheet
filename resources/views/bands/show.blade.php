@extends('layouts.master')
@section('content')

    <div class="accordion border border-5 rounded border-primary-subtle" id="band-{{ $band->uuid }}-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#band-{{ $band->uuid }}-accordion-collapse" aria-expanded="true" aria-controls="band-{{ $band->uuid }}-accordion-collapse">
                    <div class="d-flex">
                        <div class="d-flex flex-wrap align-items-start">
                            @if ($band->cover_image == null)
                                <img class="band-img" src="{{ Storage::url('images/default_band.jpg') }}" alt="default picture of band">
                            @else
                                <img class="band-img" src="{{ Storage::url('images/'.$band->cover_image) }}" alt="picture of {{ $band->name }}">                                
                            @endif
                            <h1 class="align-self-center ps-0 ps-lg-5 pt-3 pt-lg-0 mb-0" >{{ $band->name }}</h1>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="band-{{ $band->uuid }}-accordion-collapse" class="accordion-collapse collapse" data-bs-parent="#band-{{ $band->uuid }}-accordion">
                <div class="accordion-body  px-0 px-md-3" style="background-color:#a8c5f0">
                    <h2 class="py-3 px-0 px-md-3">{{ $band->albums->count() }} Albums:</h2>
        
                    @foreach ($band->albums as $album)

                        <div class="my-3 py-3 border rounded border-primary-subtle px-0 px-md-3" style="background-color:#5889d1">
                            <h3 class="d-block">Album name:</h3>
                            <h4>{{ $album->name}}</h4>
                            <p>Launched: {{ $album->launch_date }}</p>

                            <div class="accordion" id="album-{{ $album->uuid }}-accordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#album-{{ $album->uuid }}-accordion-collapse" aria-expanded="true" aria-controls="album-{{ $album->uuid }}-accordion-collapse">
                                        <h3>Songs in {{ $album->name }}</h3>
                                    </button>
                                    </h2>
                                    <div style="background-color:#3066b6 color:#eee" id="album-{{ $album->uuid }}-accordion-collapse" class="accordion-collapse collapse" data-bs-parent="#album-{{ $album->uuid }}-accordion">
                                        <div class="accordion-body px-1 px-md-3" style="background-color:#103b7c; color:#eee">

                                                @foreach($album->songs as $song)
                                                    <div class="p-3 px-md-3 my-1 border rounded border-primary-subtle" style="background-color:#082858; color:#eee">
                                                        <h5>Song name:</h5>
                                                        <p> {{ $song->name }}</p>
                                                    </div>
                                                @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  

                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection