<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Native Lazy Loading Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .placeholder {
            height: 100vh;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            color: #888;
        }
        img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>

    <h1>Native Lazy Loading Images Example</h1>

    <!-- Placeholder elements to force scrolling -->
    <div class="placeholder">Scroll down to see the images</div>
    <div class="placeholder">Keep scrolling...</div>
    <div class="placeholder">Almost there...</div>

    <!-- Example image elements with native lazy loading -->
    <img src="images/h1.png" loading="lazy" alt="Image 1">
    <img src="images/h2.png" loading="lazy" alt="Image 2">
    <img src="images/h4.png" loading="lazy" alt="Image 3">
    <img src="images/h5.png" loading="lazy" alt="Image 4">

</body>
</html>