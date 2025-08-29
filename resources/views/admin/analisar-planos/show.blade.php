<x-app-layout>
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Atenção</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mb-6">
        <a href="{{ route('admin.planos.index') }}" class="flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-2">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Voltar para a Lista de Planos
        </a>
        <h1 class="text-2xl font-semibold text-gray-800">Analisar Plano de Aplicação</h1>
        <p class="text-gray-500 mt-1">
            Escola: <span class="font-medium text-gray-700">{{ $plano->escola->nome_escola }}</span> | Enviado em: <span class="font-medium text-gray-700">{{ $plano->created_at->format('d/m/Y') }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Itens do Plano</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                                <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Qtd.</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Valor Unitário</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse ($plano->itens as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $item->descricao }}</td>
                                    <td class="py-3 px-4">{{ $item->categoria_despesa }}</td>
                                    <td class="py-3 px-4 text-center">{{ $item->quantidade }}</td>
                                    <td class="py-3 px-4 text-right">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">
                                        Este plano de aplicação não contém itens.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Ações do Administrador</h3>

                <div class="space-y-2">
                    <div class="flex justify-between font-medium text-gray-600">
                        <span>Total Custeio:</span>
                        <span>R$ {{ number_format($totalCusteio, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-medium text-gray-600">
                        <span>Total Capital:</span>
                        <span>R$ {{ number_format($totalCapital, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-xl text-gray-900 mt-2">
                        <span>TOTAL GERAL:</span>
                        <span>R$ {{ number_format($totalGeral, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div x-data="{ reprovar: false }" class="mt-6 border-t pt-4">
                    <div x-show="reprovar" class="mb-4">
                        <form action="{{ route('admin.planos.reprovar', $plano) }}" method="POST">
                            @csrf
                            <label for="motivo_reprovacao" class="block text-sm font-medium text-gray-700">Motivo da Reprovação</label>
                            <textarea name="motivo_reprovacao" id="motivo_reprovacao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            <div class="flex items-center space-x-2 mt-2">
                                <button type="submit" class="w-full text-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 text-sm">Confirmar Reprovação</button>
                                <button @click="reprovar = false" type="button" class="w-full text-center px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                            </div>
                        </form>
                    </div>

                    <div x-show="!reprovar" class="space-y-3">
                        <form action="{{ route('admin.planos.aprovar', $plano) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200">
                                Aprovar Plano
                            </button>
                        </form>
                        <button @click="reprovar = true" type="button" class="w-full text-center px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-lg hover:bg-red-200 transition-colors duration-200">
                            Reprovar Plano
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
