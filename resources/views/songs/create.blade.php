@extends('layouts.master')
@section('content')
@can('create', App\Models\User::class, App\Models\Album::class)
{{-- Mais uma vez, a documentação não é explícita quanto a passar múltiplos argumentos para o @can, mas por esta altura já percebemos o esquema --}}
    <section class="row justify-content-center">
        <form action="{{ route('songs.store') }}" method="post" style="max-width:10cm my-3">
            @csrf
            <div class="my-5">
                <label for="name" class="form-label">Insert song name:</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Insert your song's name here">

                <input type="hidden" name="album_uuid" value="{{ $album->uuid }}">

                <button type="submit" class="btn btn-primary my-3">Add song to album {{ $album->name }}</button>
            </div>
        </form>
    </section>
    @else
    <div class="d-flex my-5 justify-content-center">
        <h1>Only admins can add new songs</h1>
    </div>
@endcan
@endsection