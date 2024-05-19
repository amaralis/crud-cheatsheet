@extends('layouts.master')
@section('content')
    <section class="row justify-content-center">
        <form action="{{ route('bands.update', $band->uuid) }}" method="post" style="max-width:10cm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="my-5">

                <label for="band-{{ $band->uuid }}-name-edit-form" class="form-label">Band name: {{ $band->name }}</label>
                <input type="text" name="name" class="form-control" id="album-{{ $band->uuid }}-name-edit-form" placeholder="Insert new name for your band here">

                <div class="mb-3">
                    <label for="band-image-form" class="form-label">Change image (optional)</label>
                    <input class="form-control" name="file" type="file" id="band-image-form" accept=".png,.jpg">
                    {{-- Sem restrições de tamanho de ficheiro --}}
                </div>
                <button type="submit" class="btn btn-primary my-3">Update band name</button>
            
            </div>
        </form>
    </section>
@endsection