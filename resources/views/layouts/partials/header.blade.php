<header class="flex items-center justify-between h-16 bg-white shadow-md px-6">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        @if ($nomeEscola)
            <h1 class="text-lg font-semibold text-gray-700 ml-4"> {{ $nomeEscola }}</h1>
        @endif
    </div>

    <div class="flex items-center space-x-4">

        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative">
                <span class="font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                <svg class="h-5 w-5 text-gray-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-20" x-transition>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
