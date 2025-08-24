<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Central de Documentos</h1>
        <p class="text-gray-500 mt-1">Gere e baixe os relatórios detalhados de cada repasse recebido.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Ocorreu um Erro</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($repasses as $repasse)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-full">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="ml-4 font-bold text-lg text-gray-800">
                            {{ $repasse->numero_parcela }}ª Parcela de {{ $repasse->ano_exercicio }}
                        </h3>
                    </div>

                    <p class="text-sm text-gray-600 mb-2">
                        Valor Total: <span class="font-semibold">R$ {{ number_format($repasse->valor_custeio + $repasse->valor_capital, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Status:
                        @if ($repasse->status == 'Aberto')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aberto
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                                Finalizado
                            </span>
                        @endif
                    </p>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                    <a href="{{ route('gestor.repasses.demonstrativo', $repasse->id) }}" class="w-full text-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                        Baixar Demonstrativo
                    </a>

                    <a href="{{ route('gestor.repasses.plano', $repasse->id) }}" class="w-full text-center px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-200 text-sm">
                        Baixar Plano
                    </a>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                Nenhum repasse encontrado.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $repasses->links() }}
    </div>
</x-app-layout>
