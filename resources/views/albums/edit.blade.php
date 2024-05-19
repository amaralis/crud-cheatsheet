@extends('layouts.master')
@section('content')
    <section class="row justify-content-center">
        <form action="{{ route('albums.update', $album->uuid) }}" method="post" style="max-width:10cm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="my-5">

                <label for="album-{{ $album->uuid }}-name-edit-form" class="form-label">Album name: {{ $album->name }}</label>
                <input type="text" name="name" class="form-control" id="album-{{ $album->uuid }}-name-edit-form" placeholder="Insert new name for your album here">

                <label for="album-image-form" class="form-label">Change image (optional)</label>
                <input class="form-control" name="file" type="file" id="album-image-form" accept=".png,.jpg">
                {{-- Sem restrições de tamanho de ficheiro --}}

                <button type="submit" class="btn btn-primary my-3">Update album</button>
            
            </div>
        </form>
    </section>
@endsection