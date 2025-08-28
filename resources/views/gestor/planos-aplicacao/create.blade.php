<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Criar Novo Plano de Aplicação</h1>
        <p class="text-gray-500 mt-1">Adicione os itens de Custeio e Capital que a sua escola planeia adquirir com a próxima parcela do repasse.</p>
    </div>

    <div x-data="planoAplicacaoForm()">
        <form action="{{ route('gestor.planos-aplicacao.store') }}" method="POST" @submit.prevent="submitForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Adicionar Item ao Plano</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição do Item</label>
                                <input type="text" x-model="novoItem.descricao" id="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Resma de Papel A4" required>
                            </div>
                            <div>
                                <label for="categoria_despesa" class="block text-sm font-medium text-gray-700">Categoria da Despesa</label>
                                <select x-model="novoItem.categoria_despesa" id="categoria_despesa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="Custeio">Custeio</option>
                                    <option value="Capital">Capital</option>
                                </select>
                            </div>
                            <div>
                                <label for="unidade" class="block text-sm font-medium text-gray-700">Unidade</label>
                                <input type="text" x-model="novoItem.unidade" id="unidade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                            </div>
                            <div>
                                <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
                                <input type="number" x-model.number="novoItem.quantidade" id="quantidade" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="1" required>
                            </div>
                            <div>
                                <label for="valor_unitario" class="block text-sm font-medium text-gray-700">Valor Unitário (R$)</label>
                                <input type="number" x-model.number="novoItem.valor_unitario" id="valor_unitario" min="0.01" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button @click="adicionarItem" type="button" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 text-sm">
                                Adicionar Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Resumo do Plano</h3>

                        <div class="space-y-3 max-h-64 overflow-y-auto pr-2" x-show="itens.length > 0">
                            <template x-for="(item, index) in itens" :key="index">
                                <div class="flex justify-between items-center text-sm border-b pb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800" x-text="`${item.quantidade}x ${item.descricao}`"></p>
                                        <p class="text-xs text-gray-500" x-text="`${item.categoria_despesa} - R$ ${item.valor_unitario.toFixed(2)} cada`"></p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <p class="font-bold text-gray-800" x-text="`R$ ${(item.quantidade * item.valor_unitario).toFixed(2)}`"></p>
                                        <button @click="removerItem(index)" type="button" class="text-red-500 hover:text-red-700">&times;</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="text-center text-sm text-gray-500 py-4" x-show="itens.length === 0">
                            Nenhum item adicionado ainda.
                        </div>

                        <div class="mt-4 border-t pt-4 space-y-2">
                            <div class="flex justify-between font-medium text-gray-600">
                                <span>Total Custeio:</span>
                                <span x-text="`R$ ${totalCusteio.toFixed(2)}`"></span>
                            </div>
                            <div class="flex justify-between font-medium text-gray-600">
                                <span>Total Capital:</span>
                                <span x-text="`R$ ${totalCapital.toFixed(2)}`"></span>
                            </div>
                            <div class="flex justify-between font-bold text-xl text-gray-900 mt-2">
                                <span>TOTAL GERAL:</span>
                                <span x-text="`R$ ${totalGeral.toFixed(2)}`"></span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full text-center px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200" :disabled="itens.length === 0">
                                Submeter Plano para Análise
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="itens_json" :value="JSON.stringify(itens)">
        </form>
    </div>
</x-app-layout>
