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
                <div class="align-self-center py-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3 card-title">Update products pricing</h5>
                                        <div class="card-text">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Pricing Type</label>
                                                <select class="form-select @error('type') is-invalid @enderror" aria-label="WooCommerce" name='type' aria-describedby="typeValidation" required>
                                                  <option value="" @if(old('type') === '') selected @endif >Select pricing type</option>
                                                  <option value="wooc" @if(old('type') === 'wooc') selected @endif >WooCommerce</option>
                                                  <option value="bigc" @if(old('type') === 'bigc') selected @endif >BigCommerce</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback" id="typeValidation">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="old-pricing" class="form-label">Old Pricing</label>
                                                <input class="form-control @error('old-pricing') is-invalid @enderror" type="file" name="old-pricing"" aria-describedby="productListValidation" required >
                                                @error('old-pricing')
                                                    <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="new-pricing" class="form-label">New Pricing</label>
                                                <input class="form-control @error('new-pricing') is-invalid @enderror" type="file" name="new-pricing" aria-describedby="newPricingValidation" required>
                                                @error('new-pricing')
                                                    <div class="invalid-feedback" id="newPricingValidation">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="multiplier" class="form-label">Multiplier</label>
                                                <input type="number" name="multiplier" class="form-control @error('multiplier') is-invalid @enderror" value="1.4" required>
                                                @error('multiplier')
                                                    <div class="invalid-feedback" id="multiplierValidation">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <button class="btn btn-primary">Generate New Pricing</button>
                                        </div>
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