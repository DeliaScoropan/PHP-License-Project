<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ColorFormRequest;

class ColorController extends Controller
{
    public function index() 
    {
        return view('admin.colors.index');
    }

    public function create() 
    {
        return view('admin.colors.create');   
    }
    public function store(ColorFormRequest $request) 
    {
        $validatedData = $request->validated();
        Color::create($validatedData);
        return redirect('admin/colors')->with('message', 'Color Added Successfully');
    }
}
