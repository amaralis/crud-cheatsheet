<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\Album;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{

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
            $filePath = Storage::disk('images')->url('default_album.jpg');
        }

        $band = Band::where('uuid', $request->band_uuid)->first();

        $album = new Album([
            'band_id' => $band->id,
            'name' => $request->name,
            'launch_date' => $date,
            'cover_image' => pathinfo($filePath)['basename'],
            'uuid' => Str::uuid()
        ]);

        $album->save();
        $album->refresh();
        
        return redirect()->route('bands.show', $band->uuid);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $oldImg = $album->cover_image;
        
        if ($request->has('file')) {
            $album->cover_image = pathinfo(
                Storage::disk('images')->putFile('/', $request->file('file'))
            )['basename'];
            Storage::disk('images')->delete($oldImg);
        }

        if (!empty($request->name)) {
            $album->name = $request->name;
        }

        $album->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album): RedirectResponse
    {
        $album->deleteRecursive();
        return redirect()->back();
    }
}
