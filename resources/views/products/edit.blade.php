@extends('layouts.app')
@section('content')
<main class="container">
    <section>
    <form method="post" action="{{ route('products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
            @csrf
            @method ('PUT')
            <div class="titlebar">
                <h1>Edit Product</h1>
                <!-- <button>Save</button> -->
            </div>
            @if ($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="card">
                <div>
                    <label>Name</label>
                    <input type="text" name="nama" value="{{ $product->nama }}">
                    <label>Description (optional)</label>
                    <textarea cols="10" rows="5" name="deskripsi" value="{{ $product->deskripsi }}">{{ $product->deskripsi }}</textarea>
                    <label>Add Image</label>
                    <img src="{{ asset('gambar/'.$product->gambar) }}" alt="" class="img-product" id="file-preview" />
                    <input type="hidden" name="hidden_product_gambar" value="{{ $product->gambar }}">
                    <input type="file" name="gambar" accept="gambar/*" onchange="showFile(event)">
                </div>
                <div>
                    <label>Category</label>
                    <select name="kategori">
                        @foreach (json_decode('{"Smartphone":"Smartphone","Smart TV":"Smart TV","computer":"computer"}', true) as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}" {{ (isset($product->kategori) && $product->kategori == $optionKey ) ? 'selected' : '' }}>{{ $optionValue }}</option>
                        @endforeach

                    </select>
                    <hr>
                    <label>Inventory</label>
                    <input type="text" name="qty" value="{{$product->qty}}" >
                    <hr>
                    <label>Price</label>
                    <input type="text" name="harga" value="{{ $product->harga }}">
                </div>
            </div>
            <div class="titlebar">
                <h1></h1>
                <input type="hidden" name="hidden_id" value="{{$product->id}}">
                <button>Save</button>
            </div>
        </form>
    </section>
</main>
<script>
    function showFile(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var dataURL = reader.result;
            var output = document.getElementById('file-preview');
            output.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection