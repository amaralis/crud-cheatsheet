<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($band)
    {
        return view('albums.create', compact('band'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $date = Carbon::createFromFormat('Y-m-d', $request->launch_date)->toDateString(); // Sem validação nenhuma, só como exemplo de parse, talvez até devesse ser feito no Blade
        $filePath = "";
        if($request->has('file')){
            $filePath = Storage::disk('images')->putFile('/', $request->file('file'));
        } else {
            $filePath = Storage::url('images/default_album.jpg');
        }

        $band = Band::where('uuid', $request->band_uuid)->first();

        $album = new Album([
            'band_id' => $band->id,
            'name' => $request->name,
            'launch_date' => $date,
            'cover_image' => pathinfo($filePath)['basename'],
            'uuid' => Str::uuid()
        ]);

        // dd(pathinfo($filePath)['basename']);

        $album->save();
        $album->refresh();
        
        return redirect()->route('bands.show', $band->uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $album = Album::where('uuid', $uuid)->first();
        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $album = Album::where('uuid', $uuid)->first();
        $album->name = $request->name;
        $album->save();
        $album->fresh();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $album = Album::where('uuid', $uuid)->first();
        dd($album);
    }
}
