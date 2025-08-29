<x-app-layout>
    <div x-data="planoAplicacaoForm({ itens: {{ $plano->itens }} })">
        <form action="{{ route('gestor.plano-aplicacao.update', $plano) }}" method="POST" @submit.prevent="submitForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="itens_json" :value="JSON.stringify(itens)">

            <div class="mb-6">
                <a href="{{ route('gestor.plano-aplicacao.index') }}" class="flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-2">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Voltar
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Revisar Plano de Aplicação</h1>
                <p class="text-gray-500 mt-1">Ajuste os itens necessários e reenvie o plano para uma nova análise.</p>
            </div>

            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
                <p class="font-bold">Motivo da Reprovação:</p>
                <p>{{ $plano->motivo_reprovacao }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Adicionar Item</h3>
                    <p class="text-sm text-gray-500 mt-1">Preencha os detalhes do bem ou serviço a ser adquirido.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-6">
                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição do Item</label>
                            <input type="text" x-model="novoItem.descricao" id="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Resma de Papel A4">
                        </div>
                        <div class="md:col-span-2">
                            <label for="categoria_despesa" class="block text-sm font-medium text-gray-700">Categoria</label>
                            <select x-model="novoItem.categoria_despesa" id="categoria_despesa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Custeio">Custeio</option>
                                <option value="Capital">Capital</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
                            <input type="number" x-model.number="novoItem.quantidade" id="quantidade" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="1">
                        </div>
                        <div class="md:col-span-2">
                            <label for="valor_unitario" class="block text-sm font-medium text-gray-700">Valor Unitário (R$)</label>
                            <input type="number" x-model.number="novoItem.valor_unitario" id="valor_unitario" min="0.01" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0,00">
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <button @click="adicionarItem" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Adicionar Item à Lista
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800">Itens do Plano</h2>
                <div x-show="itens.length === 0" class="mt-4 bg-white rounded-lg shadow-md p-8 text-center">
                    <p class="text-gray-500">Nenhum item adicionado ainda. Preencha o formulário acima para começar.</p>
                </div>
                <div x-show="itens.length > 0" class="mt-4 space-y-3">
                    <template x-for="(item, index) in itens" :key="index">
                        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-indigo-600 font-bold mr-4" x-text="`${index + 1}.`"></span>
                                <div>
                                    <p class="font-semibold text-gray-900" x-text="item.descricao"></p>
                                    <p class="text-sm text-gray-600" x-text="`${item.quantidade} x R$ ${item.valor_unitario.toFixed(2).replace('.', ',')} (${item.categoria_despesa})`"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <p class="font-bold text-lg text-gray-800" x-text="`R$ ${(item.quantidade * item.valor_unitario).toFixed(2).replace('.', ',')}`"></p>
                                <button @click="removerItem(index)" type="button" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="itens.length > 0" class="mt-6 bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between font-medium text-gray-600">
                            <span>Total Custeio:</span>
                            <span class="text-lg" x-text="`R$ ${totalCusteio.toFixed(2).replace('.', ',')}`"></span>
                        </div>
                        <div class="flex justify-between font-medium text-gray-600">
                            <span>Total Capital:</span>
                            <span class="text-lg" x-text="`R$ ${totalCapital.toFixed(2).replace('.', ',')}`"></span>
                        </div>
                        <div class="flex justify-between font-bold text-xl text-gray-900 mt-2 border-t pt-3">
                            <span>TOTAL GERAL:</span>
                            <span x-text="`R$ ${totalGeral.toFixed(2).replace('.', ',')}`"></span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full text-center py-3 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200" :disabled="itens.length === 0">
                            Atualizar e Reenviar Plano
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
