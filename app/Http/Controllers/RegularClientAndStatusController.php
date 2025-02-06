<?php

namespace App\Http\Controllers;

use App\Models\RegularClientAndStatus;
use Illuminate\Http\Request;

class RegularClientAndStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.regular_client.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(RegularClientAndStatus $regularClientAndStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegularClientAndStatus $regularClientAndStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegularClientAndStatus $regularClientAndStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegularClientAndStatus $regularClientAndStatus)
    {
        //
    }
}
