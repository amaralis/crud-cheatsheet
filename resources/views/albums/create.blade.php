@extends('layouts.master')
@section('content')
@can('create', App\Models\User::class, App\Models\Album::class)
{{-- Mais uma vez, a documentação não é explícita quanto a passar múltiplos argumentos para o @can, mas por esta altura já percebemos o esquema --}}
    <section class="row justify-content-center">
        <form action="{{ route('albums.store') }}" method="post" style="max-width:10cm my-3" enctype="multipart/form-data">
            @csrf

            <div class="my-5">

                <label for="name" class="form-label">Insert album name:</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Insert your album's name here">

                {{-- Aqui devia estar um calendário para validar inputs --}}
                <label for="launch_date" class="form-label">Insert album launch date (YYYY-MM-DD):</label>
                <input type="text" name="launch_date" class="form-control" id="name" placeholder="Insert launch date for your album here">

                
                <label for="album-image-form" class="form-label">Upload an image (optional)</label>
                <input class="form-control" name="file" type="file" id="album-image-form" accept=".png,.jpg">
                {{-- Sem restrições de tamanho de ficheiro --}}

                <input type="hidden" name="band_uuid" value="{{ $band->uuid }}">

                <button type="submit" class="btn btn-primary my-3">Add album</button>
            
            </div>
        </form>
    </section>
    @else
    <div class="d-flex my-5 justify-content-center">
        <h1>Only admins can add new albums</h1>
    </div>
@endcan
@endsection