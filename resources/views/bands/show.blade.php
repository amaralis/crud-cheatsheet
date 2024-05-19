@extends('layouts.master')
@section('content')

    <div class="accordion border border-5 rounded border-primary-subtle" id="band-{{ $band->uuid }}-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#band-{{ $band->uuid }}-accordion-collapse" aria-expanded="true" aria-controls="band-{{ $band->uuid }}-accordion-collapse">
                    <div class="d-flex">
                        <div class="d-flex flex-wrap align-items-start">
                            @if ($band->cover_image == null)
                                <img class="band-img pe-5" src="{{ Storage::disk('images')->url('default_band.jpg') }}" alt="default picture of band">
                            @else
                                <img class="band-img pe-5" src="{{ Storage::disk('images')->url($band->cover_image) }}" alt="picture of {{ $band->name }}">                                
                            @endif
                            <h1 class="align-self-center pt-3" >{{ $band->name }}</h1>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="band-{{ $band->uuid }}-accordion-collapse" class="accordion-collapse collapse" data-bs-parent="#band-{{ $band->uuid }}-accordion">
                <div class="accordion-body  px-0 px-md-3" style="background-color:#a8c5f0">
                        {{-- @if(Auth::user()) --}}
                            <div class="d-flex justify-content-end">
                                {{-- @can('create', Auth::user()) --}}
                                    <a href="{{ route('albums.create', $band->uuid) }}" class="btn btn-info m-1">Add album</a>
                                {{-- @endcan --}}
                                {{-- @can('update', Auth::user()) --}}
                                    <a href="{{ route('bands.edit', $band->uuid) }}" class="btn btn-warning m-1">Edit band</a>
                                {{-- @endcan --}}
                                {{-- @can('delete', Auth::user()) --}}
                                    <a href="{{ route('bands.show', $band->uuid) }}" class="btn btn-danger m-1">Delete band</a>
                                {{-- @endcan --}}
                            </div>
                        {{-- @endif --}}
                    <h2 class="py-3 px-0 px-md-3">{{ $band->albums->count() }} Albums:</h2>
        
                    @foreach ($band->albums as $album)

                        <div class="my-3 p-3 border rounded border-primary-subtle" style="background-color:#5889d1">
                            @if ($album->cover_image == null)
                                <img class="album-img" src="{{ Storage::disk('images')->url('default_album.jpg') }}" alt="default picture of album">
                            @else
                                <img class="album-img" src="{{ Storage::disk('images')->url($album->cover_image) }}" alt="picture of {{ $album->name }}">                                
                            @endif
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