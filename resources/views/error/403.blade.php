<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col items-center justify-center text-center px-4">

    <div class="text-9xl mb-4">
        ☕
    </div>

    <h1 class="text-6xl font-black text-gray-800 mb-2">403</h1>
    
    <h2 class="text-2xl font-bold text-gray-700 mb-2">Oops! Employees Only.</h2>
    <p class="text-gray-500 mb-8 max-w-md">
        Sorry, you don't have permission to enter the Kitchen/Admin area. 
        Please enjoy our menu from the customer area!
    </p>

    <a href="{{ route('home') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
        &larr; Return to Cafe Menu
    </a>

</body>
</html>