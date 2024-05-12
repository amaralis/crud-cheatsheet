<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BandController extends Controller //implements HasMiddleware
{    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bands = Band::all();
        return view('bands.view_all', compact('bands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bands.view_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sem outras validações. Em contexto comercial, seria necessário limitar, por exemplo, tamanho, e dar feedback sobre sucesso ou não do upload
        $filePath = Storage::disk('public')->put('band-pics', $request->file('file'));
        $bandName = $request->name;
        $band = new Band;
        $band->name = $bandName;
        $band->cover_image = pathinfo($filePath)['basename'];
        $band->uuid = Str::uuid();
        $band->save();
        $band->refresh();
        
        return redirect()->route('bands.show', $band->uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $band = Band::where('uuid', $uuid)->first();
        return view('bands.view_band', compact('band'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
