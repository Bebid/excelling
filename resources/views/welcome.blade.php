<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="/js/form.js"></script>

    <style>
        html, body{
            height:100%;
            width:100%;
        }
    </style>
</head>
<body>
    <form action="/handleExcel" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7 align-self-center py-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="card col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title">Upload Files</h5>
                                    <div class="card-text">
                                        <div class="mb-3">
                                            <label for="product-list" class="form-label">Product List File</label>
                                            <input class="form-control @error('product-list') is-invalid @enderror" type="file" name="product-list" aria-describedby="productListValidation" required >
                                            @error('product-list')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="new-pricing" class="form-label">New Pricing File</label>
                                            <input class="form-control @error('new-pricing') is-invalid @enderror" type="file" name="new-pricing" aria-describedby="newPricingValidation" required>
                                            @error('new-pricing')
                                                <div class="invalid-feedback" id="newPricingValidation">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <button class="btn btn-primary">Generate New Pricing</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 p-0 border-start" style="height: 100vh; overflow: auto;">
                    <div class="accordion" id="accordion-settings">
                        <div class="accordion-item rounded-0 border-start-0">
                            <h2 class="accordion-header" id="accheader-product-list">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accbody-product-list" aria-expanded="true" aria-controls="accbody-product-list">
                                    Products
                                </button>
                            </h2>
                            <div id="accbody-product-list" class="accordion-collapse collapse show" aria-labelledby="accheader-product-list">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="product-id" class="form-label">Product Id</label>
                                        <input type="text" name="product-id" class="form-control @error('product-id') is-invalid @enderror" 
                                        value="{{old('product-id', 'id')}}">
                                        @error('product-id')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product-price-id" class="form-label">Price Id</label>
                                        <input type="text" name="product-price-id" class="form-control @error('product-price-id') is-invalid @enderror" 
                                            value="{{old('product-price-id', 'parent')}}">
                                        @error('product-price-id')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3"> 
                                        <label for="product-type" class="form-label">Type</label>
                                        <input type="text" name="product-type" class="form-control @error('product-type') is-invalid @enderror" 
                                            value="{{old('product-type', 'type')}}"/>
                                        @error('product-type')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product-type-2" class="form-label">Multiplier Type</label>
                                        <input type="text" name="product-type-2" class="form-control @error('product-type-2') is-invalid @enderror" 
                                            value="{{old('product-type-2', 'attribute_1_values')}}">
                                        @error('product-type-2')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item rounded-0 border-start-0">
                            <h2 class="accordion-header" id="accheader-pricing-list">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accbody-pricing-list" aria-expanded="true" aria-controls="accbody-pricing-list">
                                    Pricing
                                </button>
                            </h2>
                            <div id="accbody-pricing-list" class="accordion-collapse collapse show" aria-labelledby="accheader-pricing-list">
                                <div class="accordion-body">
                                    <!--
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="price-multiple-sheets" value="1" id="check-multiple-sheets"
                                            {{ old('price-multiple-sheets') == '1' ? 'checked' : '' }}>
                                        <label for="price-multiple-sheets">Multiple sheets?</label>
                                    </div>
                                    <div class="mb-3" style="display:none" id="div-sheet-name">
                                        <label for="price-sheet-name" class="form-label">Sheet Name</label>
                                        <input type="text" class="form-control @error('price-sheet-name') is-invalid @enderror" name="price-sheet-name" 
                                            value="{{old('price-sheet-name', 'prices')}}">
                                        @error('price-sheet-name')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
    -->
                                    <div class="mb-3">
                                        <label for="price-price-id" class="form-label">Price Id</label>
                                        <input type="text" name="price-price-id" class="form-control @error('price-price-id') is-invalid @enderror" 
                                            value="{{old('price-price-id', 'sku')}}">
                                        @error('price-price-id')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="price-new-price" class="form-label">New Price</label>
                                        <input type="text" name="price-new-price" class="form-control @error('price-new-price') is-invalid @enderror" 
                                            value="{{old('price-new-price', 'future_price')}}">
                                        @error('price-new-price')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="price-pack" class="form-label">Pack UOM</label>
                                        <input type="text" name="price-pack" class="form-control  @error('price-pack') is-invalid @enderror" 
                                            value="{{old('price-pack', 'pack_uom')}}">
                                        @error('price-pack')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="price-change" class="form-label">Change</label>
                                        <input type="text" name="price-change" class="form-control  @error('price-change') is-invalid @enderror" 
                                            value="{{old('price-change', 'change')}}">
                                        @error('price-change')
                                                <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>