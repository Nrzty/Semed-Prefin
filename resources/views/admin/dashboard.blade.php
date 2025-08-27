<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Painel do Administrador</h1>
        <p class="text-gray-500 mt-1">Visão geral e estatísticas de todo o sistema para o ano de {{ date('Y') }}.</p>
    </div>

    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total de Escolas</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalEscolas }}</p>
            </div>
            <div class="bg-sky-100 text-sky-600 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Valor Total Repassado ({{ date('Y') }})</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">R$ {{ number_format($totalCusteio + $totalCapital, 2, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Custeio: R$ {{ number_format($totalCusteio, 2, ',', '.') }} | Capital: R$ {{ number_format($totalCapital, 2, ',', '.') }}</p>
            </div>
            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v.01M12 18v-2m0-4v-2m0-4V4m0 0v.01M12 6v.01M12 18v.01M12 6v.01M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Prestações Finalizadas ({{ date('Y') }})</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $escolasComPrestacaoCompleta }} / {{ $totalEscolas }}</p>
                <p class="text-xs text-gray-400 mt-1">(Escolas com 4 parcelas / Total)</p>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-gray-800">Distribuição de Verbas ({{ date('Y') }})</h3>
            <div class="mt-4" style="height: 300px;">
                <canvas id="graficoPizzaVerbas" data-dados='@json([$totalCusteio, $totalCapital])'></canvas>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-gray-800">Progresso da Prestação de Contas ({{ date('Y') }})</h3>
            <div class="mt-4" style="height: 300px;">
                <canvas id="graficoBarrasProgresso" data-dados='@json($progressoParcelas)'></canvas>
            </div>
        </div>
    </div>
</x-app-layout>
