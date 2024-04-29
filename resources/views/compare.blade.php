<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Images</title>
</head>
<body>
    <h1>Compare Images</h1>
    <p>Please take a selfie and then upload images to compare with the selfie.</p>
    <form action="{{ route('compare-images') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="selfie">Selfie:</label><br>
        <input type="file" id="selfie" name="selfie" accept="image/*;"><br><br>

        <label for="images">Images to Compare:</label><br>
        <input type="file" id="images" name="images[]" multiple><br><br>
        <button type="submit">Compare</button>
    </form>
</body>
</html>
