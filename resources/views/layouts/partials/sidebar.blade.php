<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center h-16 border-b">
        <img src="{{ asset('imgs/logo2.png') }}" alt="Logo SIGED" class="h-8 w-auto">
    </div>

    <nav class="mt-4 px-2 flex-1">
        <a href="{{ route('gestor.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-md
            {{ request()->routeIs('gestor.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="ml-3 font-medium">Início</span>
        </a>

        <div x-data="{ open: @json(request()->routeIs('gestor.pagamentos.*')) }" class="mt-2">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 rounded-md text-left
                {{ request()->routeIs('gestor.pagamentos.*') ? 'bg-gray-200 text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="flex items-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="ml-3 font-medium">Despesas</span>
                </span>
                <svg :class="{'transform -rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" x-transition class="mt-1 pl-8 space-y-1">
                <a href="{{ route('gestor.pagamentos.create') }}" class="block px-4 py-2 text-sm rounded-md
                    {{ request()->routeIs('gestor.pagamentos.create') ? 'font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                    Lançar Despesa
                </a>
                <a href="{{ route('gestor.pagamentos.index') }}" class="block px-4 py-2 text-sm rounded-md
                    {{ request()->routeIs('gestor.pagamentos.index') ? 'font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                    Listar Despesas
                </a>
            </div>

            <a href="{{ route('gestor.documentos.index') }}" class="flex items-center px-4 py-2.5 mt-2 rounded-md
                {{ request()->routeIs('gestor.documentos.index') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="ml-3 font-medium">Documentos</span>
            </a>

            <a href="{{ route('gestor.prestacao-contas.escolher-repasse') }}" class="flex items-center px-4 py-2.5 mt-2 rounded-md
                {{ request()->routeIs('gestor.prestacao-contas.escolher-repasse') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="ml-3 font-medium">Organizar Prestação de Contas</span>
            </a>

            <div x-data="{ open: @json(request()->routeIs('plano-aplicacao.*')) }" class="mt-2">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 rounded-md text-left
                {{ request()->routeIs('plano-aplicacao.*') ? 'bg-gray-200 text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="flex items-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="ml-3 font-medium">Plano de Aplicação</span>
                </span>
                    <svg :class="{'transform -rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" x-transition class="mt-1 pl-8 space-y-1">
                    <a href="{{ route('gestor.plano-aplicacao.index') }}" class="block px-4 py-2 text-sm rounded-md
                    {{ request()->routeIs('gestor.plano-aplicacao.index') ? 'font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                        Listar Planos
                    </a>
                    <a href="{{ route('gestor.plano-aplicacao.create') }}" class="block px-4 py-2 text-sm rounded-md
                    {{ request()->routeIs('gestor.plano-aplicacao.create') ? 'font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                        Enviar Novo Plano
                    </a>
                </div>
        </div>
    </nav>
</aside>
