@extends('layouts.master')
@section('content')
    @foreach ($bands as $band)
        <div class="container bg-primary bg-gradient">
            <h1>Band name:</h1>
            <h2>{{ $band->name }}</h2>
            <p>Id: {{ $band->id }}</p>
            <h1>Albums:</h1>
            
            @foreach ($band->albums as $album)
                <div class="container bg-secondary bg-gradient">
                    <h3>Album name: {{ $album->name}}</h3>
                    <p>Launched: {{ $album->launch_date }}</p>
                    <p>Id: {{ $album->id }}</p>
                    <p>Album belongs to song with Id: {{ $album->band_id }}</p>
    
                    <h3>Songs in this album:</h3>

                    <div class="container bg-info bg-gradient">
                        @foreach($album->songs as $song)
        
                            <h4>Song name: {{ $song->name }}</h4>
                            <p>Song Id: {{ $song->id }}</p>
                            <p>Song belongs to album with Id: {{ $song->album_id }}</p>
                        @endforeach
                    </div>
                </div>            
            @endforeach
        </div>
    @endforeach
@endsection