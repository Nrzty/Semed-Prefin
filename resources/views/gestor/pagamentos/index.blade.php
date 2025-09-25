<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Histórico de Pagamentos') }}
            </h2>
            <a href="{{ route('gestor.pagamentos.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Lançar Novo Pagamento
            </a>
        </div>
    </x-slot>

    <div x-data="{ openModal: false, deleteUrl: '' }">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Pag.</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornecedor</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($pagamentos as $pagamento)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($pagamento->data_pagamento_efetivo)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $pagamento->nome_fornecedor }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-right">R$ {{ number_format($pagamento->valor_total_pagamento, 2, ',', '.') }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-center space-x-4">
                                        <a href="{{ route('gestor.pagamentos.edit', $pagamento->id) }}" class="text-indigo-600 bg-indigo-100 font-semibold rounded-lg hover:text-indigo-900 text-sm">
                                            Editar
                                        </a>
                                        <button @click="openModal = true; deleteUrl = '{{ route('gestor.pagamentos.destroy', $pagamento->id) }}'" class="text-red-600 bg-red-100 font-semibold rounded-lg hover:text-red-900 text-sm">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                        Nenhum pagamento encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $pagamentos->links() }}
                </div>
            </div>
        </div>

        <div x-show="openModal" x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

            <div @click.away="openModal = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">

                <h3 class="text-xl font-bold text-gray-800">Confirmar Exclusão</h3>
                <p class="mt-2 text-gray-600">
                    Você tem certeza que deseja excluir este pagamento? Esta ação não pode ser desfeita.
                </p>

                <div class="mt-6 flex justify-end space-x-4">
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')

                        <x-secondary-button @click="openModal = false">
                            Cancelar
                        </x-secondary-button>

                        <x-danger-button type="submit">
                            Sim, Excluir
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
