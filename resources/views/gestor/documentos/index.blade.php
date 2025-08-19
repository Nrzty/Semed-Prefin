<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Central de Documentos') }}
        </h2>
    </x-slot>

    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
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
                <p class="text-gray-600 mb-6">
                    Abaixo está o histórico de todos os repasses. É possível baixar o relatório de prestação de contas de qualquer parcela.
                </p>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repasse</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total (Custeio + Capital)</th>
                                <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($repasses as $repasse)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $repasse->numero_parcela }}ª Parcela de {{ $repasse->ano_exercicio }}</div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if ($repasse->status == 'Aberto')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aberto
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                                                Finalizado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        R$ {{ number_format($repasse->valor_custeio + $repasse->valor_capital, 2, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap text-center">
                                        <a href="{{ route('gestor.repasses.demonstrativo', $repasse->id) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Gerar Demonstrativo
                                        </a>
                                        <a href="{{ route('gestor.repasses.demonstrativo', $repasse->id) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Gerar Plano
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                        Nenhum repasse encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $repasses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
