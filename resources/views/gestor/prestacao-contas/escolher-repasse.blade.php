<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Organizar Prestação de Contas</h1>
        <p class="text-gray-500 mt-1">Selecione o repasse para o qual deseja organizar os documentos.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($repasses as $repasse)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-full">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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

                <div class="mt-6">
                    @if ($repasse->status == 'Aberto')
                        <a href="{{ route('gestor.repasses.prestacao-contas.index', $repasse) }}" class="w-full text-center block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                            Organizar Documentos
                        </a>
                    @else
                        <button class="w-full text-center block px-4 py-2 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed text-sm" disabled>
                            Prestação Finalizada
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                Nenhum repasse encontrado para organizar a prestação de contas.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $repasses->links() }}
    </div>
</x-app-layout>
