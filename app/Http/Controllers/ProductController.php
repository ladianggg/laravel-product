<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 5;

        if (!empty($keyword)){
            $products = Product:: where('nama','LIKE',"%$keyword%")
                        ->orWhere('kategori', 'LIKE',"%$keyword%")
                        ->latest ()->paginate($perPage);
        }else{
            $products = Product::latest()->paginate($perPage);
        }


        return view('products.index', ['products' => $products])->with('i', (request()->input('page', 1) - 1) * 5);

    }
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request){

        $request->validate([
            'nama' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2028',
        ]);
        

        $product = new Product();

        $file_name = time() . '.' . request()-> gambar->getClientOriginalExtension();
        request()->gambar->move(public_path('gambar'),$file_name); 

        $product-> nama = $request->nama;
        $product -> deskripsi = $request-> deskripsi;
        $product -> gambar = $file_name;
        $product ->kategori = $request ->kategori;
        $product ->qty = $request ->qty;
        $product ->harga = $request->harga;
        
        $product->save();
return redirect()->route('products.index')->with('success', 'Product Added successfully');


    }

    public function edit($id){
        $product = Product::findOrFail($id);
        return view ('products.edit',['product'=>$product]);

    }
    public function update(Request $request, Product $product){
        $request->validate([
            'nama' => 'required'
        ]);

        $file_name = $request->hidden_product_gambar;

        if ($request->gambar != ''){
            $file_name = time() . '.' . request()-> gambar->getClientOriginalExtension();
             request()->gambar->move(public_path('gambar'),$file_name); 

        }

        $product = Product::find($request->hidden_id);

        $product-> nama = $request->nama;
        $product -> deskripsi = $request-> deskripsi;
        $product -> gambar = $file_name;
        $product ->kategori = $request ->kategori;
        $product ->qty = $request ->qty;
        $product ->harga = $request->harga;
        $product->save();

        return redirect()->route('products.index')->with('success','Product has been updated successfully');
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $image_path = public_path()."/gambar";
        $gambar = $image_path. $product->gamabr;
        if(file_exists($gambar)){
           @unlink($gambar);
        }
        $product->delete();
        return redirect('products')->with('success','Product deleted');  
     }
}
