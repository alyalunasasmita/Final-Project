<header class="w-full bg-white shadow-sm px-6 py-4 flex items-center justify-between">

    <!-- Left: Title / breadcrumb -->
    <div>
        <h2 class="text-xl font-semibold text-gray-700">Dashboard Kamu</h2>
        <p class="text-sm text-gray-400 -mt-1">Selamat datang kembali 👋</p>
    </div>

    <!-- Middle: Search Bar -->
    <div class="hidden md:flex items-center w-1/3">
        <div class="w-full relative">
            <input 
                type="text" 
                placeholder="Cari sesuatu..."
                class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-300 focus:outline-none transition"
            >
            <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
        </div>
    </div>

    <!-- Right: Profile -->
    <div class="flex items-center gap-4">
        <!-- Notification -->
        <button class="text-xl">🔔</button>

        <!-- User Profile -->
        <div class="flex items-center gap-3">
            <img src="https://i.pinimg.com/736x/40/68/0b/40680be1090e771f666a67adf511282c.jpg"
                 class="w-10 h-10 rounded-full border-2 border-pink-300 shadow-sm">
            <span class="text-gray-700 font-medium">Alya</span>
        </div>
    </div>

</header>
