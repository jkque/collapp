<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Image;

class ProductController extends Controller
{
    public function addEdit(Request $request)
    {

        $product = Product::find($request->id) ?? new Product;
        $validator = Validator::make($request->all(), [
			'name'         => 'required|max:255',
			'description'  => 'required|max:255',
			'price'        => 'required|numeric',
			'unit_id'      => 'required',
		]);
		if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(), 
            ];            
		}

        if(!$request->has('id')) {
            $request->merge([
                'code'  => 'sampleProductCode',
            ]);
        }

        $product->fill($request->all());
        $product->save();
        return [
            'success' => true,
            'product' => $product->load('unit'),
            'message' => 'Product successfully added!',
        ];
    	
    }
}
