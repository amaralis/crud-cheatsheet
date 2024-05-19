@extends('layouts.master')
@section('content')
    <section class="row justify-content-center">
        <form action="{{ route('bands.update', $band->uuid) }}" method="post" style="max-width:10cm">
            @csrf
            @method('PUT')

            <div class="my-5">

                <label for="band-{{ $band->uuid }}-name-edit-form" class="form-label">Band name: {{ $band->name }}</label>
                <input type="text" name="name" class="form-control" id="album-{{ $band->uuid }}-name-edit-form" placeholder="Insert new name for your band here">
                <button type="submit" class="btn btn-primary my-3">Update band name</button>
            
            </div>
        </form>
    </section>
@endsection