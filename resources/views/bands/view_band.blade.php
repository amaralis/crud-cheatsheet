@extends('layouts.master')
@section('content')

<div class="bg-primary bg-gradient">
            <h1>Band name:</h1>
            <h2>{{ $band->name }}</h2>
            <p>UUID: {{ $band->uuid }}</p>

            @if ($band->cover_image !== null)
                <div class="band-image">
                    <img src="{{ Storage::url("band-pics/".$band->cover_image) }}" alt="">
                </div>
            @endif
            
            <h1>Albums:</h1>
            
            @foreach ($band->albums as $album)

                <div class="bg-secondary bg-gradient">
                    <h3>Album name: {{ $album->name}}</h3>
                    <p>Launched: {{ $album->launch_date }}</p>
                    <p>Id: {{ $album->id }}</p>
                    <p>UUID: {{ $album->uuid }}</p>
                    <p>Album belongs to song with Id: {{ $album->band_id }}</p>
    
                    <h4>Songs in this album:</h4>

                    <div class="bg-info bg-gradient">

                        @foreach($album->songs as $song)        
                            <h5>Song name: {{ $song->name }}</h5>
                            <p>Song Id: {{ $song->id }}</p>
                            <p>Song UUID: {{ $song->uuid }}</p>
                            <p>Song belongs to album with Id: {{ $song->album_id }}</p>
                        @endforeach

                    </div>
            
                </div>  

            @endforeach
        </div>



@endsection