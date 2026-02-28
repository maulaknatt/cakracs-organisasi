<!DOCTYPE html>
<html>
<head>
    <title>Admin Organisasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

<nav class="sticky top-0 z-50 bg-white shadow-md border-b border-gray-200 px-6 py-4 mb-6 flex gap-6">
    <a href="/kegiatan" class="text-gray-700 hover:text-blue-600 font-medium transition">Kegiatan</a>
    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition">Dokumentasi</a>
    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition">Anggota</a>
</nav>

<hr>

@yield('content')

</body>
</html>
