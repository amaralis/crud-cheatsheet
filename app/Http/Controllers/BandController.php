<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Album;
use Illuminate\Http\RedirectResponse;

use function PHPUnit\Framework\isEmpty;

class BandController extends Controller //implements HasMiddleware
{    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bands = Band::all();
        return view('bands.index', compact('bands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sem outras validações. Em contexto comercial, seria necessário limitar, por exemplo, tamanho, e dar feedback sobre sucesso ou não do upload
        $filePath = "";
        if ($request->has('file')) {
            $filePath = Storage::disk('images')->putFile('/', $request->file('file'));
        } else {
            $filePath = Storage::disk('images')->url('default_band.jpg');
        }

        $band = new Band([
            'name' => $request->name,
            'cover_image' => $request->has('file') ? pathinfo($filePath)['basename'] : 'default_band.jpg',
            'uuid' => Str::uuid()
        ]);

        $band->save();
        $band->refresh();
        
        return redirect()->route('bands.show', $band->uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(Band $band)
    {
        return view('bands.show', compact('band'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Band $band)
    {
        return view('bands.edit', compact('band'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Band $band)
    {
        $oldImg = $band->cover_image;
        if ($request->has('file')) {
            // $filePath = Storage::disk('images')->putFile('/', $request->file('file'));
            $band->cover_image = pathinfo(
                Storage::disk('images')->putFile('/', $request->file('file'))
                    )['basename'];
            Storage::disk('images')->delete($oldImg);
        }

        if(!empty($request->name)){
            $band->name = $request->name;
        }

        $band->save();
        $band->fresh();
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Band $band): RedirectResponse
    {
        $band->deleteRecursive();
        return redirect()->route('home');
    }
}
