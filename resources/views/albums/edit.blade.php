@extends('layouts.master')
@section('content')
    <section class="row justify-content-center">
        <form action="{{ route('albums.update', $album->uuid) }}" method="post" style="max-width:10cm">
            @csrf
            @method('PUT')

            <div class="my-5">

                <label for="album-{{ $album->uuid }}-name-edit-form" class="form-label">Album name: {{ $album->name }}</label>
                <input type="text" name="name" class="form-control" id="album-{{ $album->uuid }}-name-edit-form" placeholder="Insert new name for your album here">
                <button type="submit" class="btn btn-primary my-3">Update album name</button>
            
            </div>
        </form>
    </section>
@endsection