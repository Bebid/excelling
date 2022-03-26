<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-4">
                <form action="/handleExcel" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                      <label for="product-list" class="form-label">Product List</label>
                      <input class="form-control @error('product-list') is-invalid @enderror" type="file" name="product-list" aria-describedby="productListValidation" required >
                      @error('product-list')
                        <div class="invalid-feedback" id="productListValidation">{{$message}}</div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="new-pricing" class="form-label">New pricing</label>
                      <input class="form-control @error('new-pricing') is-invalid @enderror" type="file" name="new-pricing" aria-describedby="newPricingValidation" required>
                      @error('new-pricing')
                        <div class="invalid-feedback" id="newPricingValidation">{{$message}}</div>
                      @enderror
                    </div>
                    <button class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>