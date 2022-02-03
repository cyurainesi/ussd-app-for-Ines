<?php

namespace App\Http\Controllers;

use App\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

    public function index()
    {
        return Destination::get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return Destination::create($request->all());
    }

    public function show(Destination $destination)
    {
        //
    }


    public function edit(Destination $destination)
    {
        //
    }

    public function update(Request $request, Destination $destination)
    {
        //
    }

    public function destroy(Destination $destination)
    {
        //
    }
}
