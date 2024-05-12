@extends('layouts.master')
@section('content')

<section class="row justify-content-center">

    <div class="text-center">
        <h1>Add a new band</h1>
    </div>

    <form style="max-width:10cm" action="{{ route('bands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
        <div class="row g-3">
            <div class="col-sm-7">
                <input type="text" class="form-control" name="name" placeholder="Band name" aria-label="Band name">
            </div>
            <div class="mb-3">
                <label for="band-image-form" class="form-label">Upload an image (optional)</label>
                <input class="form-control" name="file" type="file" id="band-image-form" accept=".png,.jpg">
                {{-- Sem restrições de tamanho de ficheiro --}}
            </div>
    
        </div>
    
        <button type="submit" class="btn btn-primary">Add</button>
    
    </form>
</section>
    
@endsection