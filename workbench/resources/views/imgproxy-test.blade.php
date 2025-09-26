<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImgProxy Laravel Package Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">
            ImgProxy Laravel Package Test Suite
        </h1>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Visual Image Processing Tests</h2>
            <p class="text-gray-600 mb-6">
                Below are real-time examples of ImgProxy processing using the Laravel package.
                All images are processed through your configured ImgProxy server.
            </p>

            <!-- Original Image -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Original Image</h3>
                <img src="https://picsum.photos/seed/sandstorm-laravel/3000/3000" alt="Original" class="border rounded shadow-sm max-w-md">
                <p class="text-sm text-gray-500 mt-2">Source: https://picsum.photos/seed/sandstorm-laravel/3000/3000</p>
            </div>

            <!-- Basic Resizing -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Basic Resizing (400x300)</h3>
                <img src="{{ imagor()->resize(width: 400, height: 300)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                     alt="Resized" class="border rounded shadow-sm">
                <p class="text-sm text-gray-500 mt-2">
                    Code: <code class="bg-gray-100 px-1 rounded">imagor()->resize(width: 400, height: 300)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000')</code>
                </p>
            </div>

            <!-- Quality Comparison -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Quality Comparison WEBP</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">Low Quality (30%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(30)->format('webp')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Low Quality" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Medium Quality (70%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(70)->format('webp')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Medium Quality" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">High Quality (95%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(95)->format('webp')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="High Quality" class="border rounded shadow-sm w-full">
                    </div>
                </div>
            </div>

            <!-- Quality Comparison -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Quality Comparison JPEG</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">Low Quality (30%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(30)->format('jpeg')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Low Quality" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Medium Quality (70%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(70)->format('jpeg')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Medium Quality" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">High Quality (95%)</h4>
                        <img src="{{ imagor()->resize(width: 600, height: 500)->quality(95)->format('jpeg')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="High Quality" class="border rounded shadow-sm w-full">
                    </div>
                </div>
            </div>

            <!-- Format Comparison -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Format Comparison</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">JPEG</h4>
                        <img src="{{ imagor()->resize(width: 250)->format('jpeg')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="JPEG" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">PNG</h4>
                        <img src="{{ imagor()->resize(width: 250)->format('png')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="PNG" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">WebP</h4>
                        <img src="{{ imagor()->resize(width: 250)->format('webp')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="WebP" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">AVIF</h4>
                        <img src="{{ imagor()->resize(width: 250)->format('avif')->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="AVIF" class="border rounded shadow-sm w-full">
                    </div>
                </div>
            </div>

            <!-- Resize Types -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Resize Types (300x200)</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">Fit</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 200)->fitIn()->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Fit" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Fill</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 200)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Fill" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Force</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 200)->stretch()->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Force" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Auto</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 200)->smart()->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Auto" class="border rounded shadow-sm w-full">
                    </div>
                </div>
            </div>

            <!-- Visual Effects -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Visual Effects</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">Blur Effect</h4>
                        <img src="{{ imagor()->resize(width: 300)->blur(2.0)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Blur" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Sharpen Effect</h4>
                        <img src="{{ imagor()->resize(width: 300)->sharpen(2.0)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Sharpen" class="border rounded shadow-sm w-full">
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">High Saturation</h4>
                        <img src="{{ imagor()->resize(width: 300)->saturation(2.0)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Saturated" class="border rounded shadow-sm w-full">
                    </div>
                </div>
            </div>

            <!-- Complex Processing -->
            <div class="mb-8">
                <h3 class="text-lg font-medium mb-3 text-gray-700">Complex Processing Pipeline</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium mb-2">Portrait Enhancement</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 400)->brightness(10)->contrast(1.1)->saturation(1.05)->sharpen(0.8)->quality(92)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Enhanced Portrait" class="border rounded shadow-sm w-full">
                        <p class="text-xs text-gray-500 mt-1">Enhanced brightness, contrast, saturation, and sharpening</p>
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Vintage Effect</h4>
                        <img src="{{ imagor()->resize(width: 300, height: 400)->saturation(0.7)->contrast(0.9)->brightness(-10)->quality(85)->uriFor('https://picsum.photos/seed/sandstorm-laravel/3000/3000') }}"
                             alt="Vintage Effect" class="border rounded shadow-sm w-full">
                        <p class="text-xs text-gray-500 mt-1">Reduced saturation, lower contrast, and decreased brightness</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- API Test Links -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">API Test Endpoints</h2>
            <p class="text-gray-600 mb-4">
                Click the links below to test the ImgProxy package API endpoints:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="/imgproxy-test/basic" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded block text-center transition">
                    Basic Test
                </a>
                <a href="/imgproxy-test/effects" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded block text-center transition">
                    Effects Test
                </a>
                <a href="/imgproxy-test/formats" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded block text-center transition">
                    Formats Test
                </a>
                <a href="/imgproxy-test/resize" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded block text-center transition">
                    Resize Test
                </a>
                <a href="/imgproxy-test/facade-vs-helper" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded block text-center transition">
                    Facade vs Helper
                </a>
                <a href="/imgproxy-test/config" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded block text-center transition">
                    Config Test
                </a>
                <a href="/imgproxy-test/error-handling" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded block text-center transition">
                    Error Handling
                </a>
                <a href="/imgproxy-test/performance" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded block text-center transition">
                    Performance Test
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
