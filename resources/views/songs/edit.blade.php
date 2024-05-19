@extends('layouts.master')
@section('content')
    <section class="row justify-content-center">
        <form action="{{ route('songs.update', $song->uuid) }}" method="post" style="max-width:10cm">
            @csrf
            @method('PUT')

            <div class="my-5">

                <label for="album-{{ $song->uuid }}-name-edit-form" class="form-label">Song name: {{ $song->name }}</label>
                <input type="text" name="name" class="form-control" id="album-{{ $song->uuid }}-name-edit-form" placeholder="Insert new name for your song here">

                <button type="submit" class="btn btn-primary my-3">Update song</button>
            
            </div>
        </form>
    </section>
@endsection