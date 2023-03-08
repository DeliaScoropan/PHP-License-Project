<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index() 
    {
        return view('admin.products.index');
    }

    public function create() 
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductFormRequest $request) 
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($validatedData['category_id']);

       $product = $category->products()->create([
            'category_id' =>  $validatedData['category_id'],
            'name' =>  $validatedData['name'],
            'slug' =>  Str::slug($validatedData['slug']),
            'small_description' =>  $validatedData['small_description'],
            'description' =>  $validatedData['description'],
            'original_price' =>  $validatedData['original_price'],
            'quantity' =>  $validatedData['quantity'],
            'status' =>  $request->status == true ? '1':'0',
            'meta_title' =>  $validatedData['meta_title'],
            'meta_keyword' =>  $validatedData['meta_keyword'],
            'meta_description' =>  $validatedData['meta_description'],

        ]);

        if($request->hasFile('image')) {
            $uploadPath = 'ulpoads/products/';

            $i= 1;
            foreach($request->file('image') as $imageFile) {
                $extention = $imageFile->getClientOriginalExtension();
                $filename = time().$i++.'.'.$extention;
                $imageFile->move($uploadPath,$filename);
                $finalImagePathName = $uploadPath.$filename;

                $product->productImages()->create([

                    'product_id' => $product->id,
                    'image' => $finalImagePathName,
                ]);
            }
        }

        

       return redirect('/admin/products')->with('messsage', 'Product Added Succsesfully');

    }
}
