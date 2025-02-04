<?php

namespace App\Http\Controllers;

use App\Models\ClientList;
use App\Models\User;
use Illuminate\Http\Request;

class ClientListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Replace 'Student' with the actual value if it's a string
        $clients = User::where('role', 'Student')->get();
        return view('admin.clientlist', compact('clients'));
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
    public function show(ClientList $clientList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientList $clientList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientList $clientList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientList $clientList)
    {
        //
    }
}
