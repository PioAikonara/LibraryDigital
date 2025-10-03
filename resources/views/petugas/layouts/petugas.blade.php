<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Library Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-cyan-600 to-teal-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex items-center">
                        <a href="{{ route('petugas.dashboard') }}" class="text-xl font-bold">Library Digital</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-100">Dashboard</a>
                        <a href="{{ route('petugas.borrows.index') }}" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-100">Manage Borrows</a>
                        <a href="{{ route('petugas.books.index') }}" class="inline-flex items-center px-1 pt-1 text-white hover:text-gray-100">Books</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-white hover:text-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            
            @yield('petugas-content')
        </div>
    </main>
</body>
</html>