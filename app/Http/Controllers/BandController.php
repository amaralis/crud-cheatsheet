<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class BandController extends Controller //implements HasMiddleware
{
    // public static function middleware(): array
    // {
    //     return [
    //         'auth',
    //         new Middleware('log', only: ['index']),
    //         new Middleware('subscribed', except: ['store']),
    //     ];
    // }
    
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
        //
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
