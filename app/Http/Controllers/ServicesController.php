<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    // Method to handle travel requests
    public function travel()
    {
		//dd('s');
        // Logic for the travel method
        // This can be anything, such as querying the database, processing data, etc.
        // For demonstration, let's return a view named 'travel'

        return view('services.travel');
    }
}
