<header class="bg-white shadow fixed top-0 left-0 w-full">
    <nav class="mx-auto max-w-7xl px-4 py-1 sm:px-6 md:py-3 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo/Brand -->
            <div class="flex-shrink-0">
                <h1 class="text-xs font-bold tracking-tight text-blue-600 md:text-xl">{{ $slot }}</h1>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    {{-- <a href="{{ route('matching') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Matching</a>
                    <a href="{{ route('upload-dn') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Upload DN</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a> --}}
                    <a href="" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Matching</a>
                    <a href="" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Upload DN</a>
                    <a href="" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Hamburger icon -->
                    <svg class="block h-6 w-6" id="hamburger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
                    <svg class="hidden h-6 w-6" id="close-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Matching</a>
                <a href="" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Upload DN</a>
                <a href="" class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            
            // Toggle menu visibility
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icons
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
            
            // Update aria-expanded
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
        });
    });
</script>

<table class="datatable">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        <!-- Your table data -->
    </tbody>
</table>