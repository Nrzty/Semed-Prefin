<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel do Gestor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">    
                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif
                    <h3 class="text-lg font-medium">Repasse Ativo: {{ $repasseAtivo->numero_parcela }}ª Parcela de {{ $repasseAtivo->ano_exercicio }}</h3>                
                    @if ($repasseAtivo)  
                            <div class="bg-purple-100 p-4 rounded-lg">
                                <h4 class="font-bold text-purple-800">Total da {{ $repasseAtivo->numero_parcela }}ª Parcela </h4>
                                <p class="text-2xl font-semibold text-purple-900">
                                    R$ {{ number_format($repasseAtivo->valor_custeio + $repasseAtivo->valor_capital, 2, ',', '.') }}
                                </p>
                            </div>
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Card Saldo Custeio --}}
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <h4 class="font-bold text-blue-800">Saldo de Custeio</h4>
                                <p class="text-2xl font-semibold text-blue-900">
                                    R$ {{ number_format($repasseAtivo->valor_custeio, 2, ',', '.') }}
                                </p>
                            </div>

                            <div class="bg-green-100 p-4 rounded-lg">
                                <h4 class="font-bold text-green-800">Saldo de Capital</h4>
                                <p class="text-2xl font-semibold text-green-900">
                                    R$ {{ number_format($repasseAtivo->valor_capital, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h4 class="text-lg font-medium">Últimos Pagamentos Lançados</h4>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="py-2 px-4 text-left">Data</th>
                                            <th class="py-2 px-4 text-left">Fornecedor</th>
                                            <th class="py-2 px-4 text-left">Tipo</th>
                                            <th class="py-2 px-4 text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ultimosPagamentos as $pagamento)
                                            <tr class="border-b">
                                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                                                <td class="py-2 px-4">{{ $pagamento->nome_fornecedor }}</td>
                                                <td class="py-2 px-4">{{ $pagamento->tipo_despesa }}</td>
                                                <td class="py-2 px-4 text-right">R$ {{ number_format($pagamento->valor_total_pagamento, 2, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                                    Nenhum pagamento lançado para este repasse ainda.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-100 p-4 rounded-lg text-yellow-800">
                            Nenhum repasse com status "Aberto" foi encontrado para esta escola.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>