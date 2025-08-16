<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Pagamento') }}
        </h2>
    </x-slot>

    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div class="p-6 md:p-8 text-gray-900">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800">Editando Pagamento</h3>
                    <p class="text-gray-500 mt-1">Altere as informações necessárias e clique em "Atualizar".</p>
                </div>

                <form method="POST" action="{{ route('gestor.pagamentos.update', $pagamento->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="nome_fornecedor" value="Nome / Razão Social" />
                                <x-text-input id="nome_fornecedor" class="block mt-1 w-full" type="text" name="nome_fornecedor" :value="old('nome_fornecedor', $pagamento->nome_fornecedor)" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="cnpj_cpf_fornecedor" value="CNPJ ou CPF" />
                                <x-text-input id="cnpj_cpf_fornecedor" class="block mt-1 w-full" type="text" name="cnpj_cpf_fornecedor" :value="old('cnpj_cpf_fornecedor', $pagamento->cnpj_cpf_fornecedor)" required />
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="tipo_despesa" value="Tipo da Despesa" />
                                <select id="tipo_despesa" name="tipo_despesa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="Material de Custeio" @selected(old('tipo_despesa', $pagamento->tipo_despesa) == 'Material de Custeio')>Material de Custeio</option>
                                    <option value="Prestação de Serviço" @selected(old('tipo_despesa', $pagamento->tipo_despesa) == 'Prestação de Serviço')>Prestação de Serviço</option>
                                    <option value="Material de Capital" @selected(old('tipo_despesa', $pagamento->tipo_despesa) == 'Material de Capital')>Material de Capital</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="numero_nota_fiscal" value="Número da Nota Fiscal" />
                                <x-text-input id="numero_nota_fiscal" class="block mt-1 w-full" type="text" name="numero_nota_fiscal" :value="old('numero_nota_fiscal', $pagamento->numero_nota_fiscal)" required />
                            </div>
                            <div>
                                <x-input-label for="data_emissao_documento" value="Data de Emissão (NF)" />
                                <x-text-input id="data_emissao_documento" class="block mt-1 w-full" type="date" name="data_emissao_documento" :value="old('data_emissao_documento', $pagamento->data_emissao_documento)" required />
                            </div>
                             <div>
                                <x-input-label for="data_pagamento_efetivo" value="Data do Pagamento Efetivo" />
                                <x-text-input id="data_pagamento_efetivo" class="block mt-1 w-full" type="date" name="data_pagamento_efetivo" :value="old('data_pagamento_efetivo', $pagamento->data_pagamento_efetivo)" required />
                            </div>
                            <div>
                                <x-input-label for="numero_cheque" value="Número do Cheque (Opcional)" />
                                <x-text-input id="numero_cheque" class="block mt-1 w-full" type="text" name="numero_cheque" :value="old('numero_cheque', $pagamento->numero_cheque)" />
                            </div>
                            <div>
                                <x-input-label for="data_vencimento_cheque" value="Vencimento do Cheque (Opcional)" />
                                <x-text-input id="data_vencimento_cheque" class="block mt-1 w-full" type="date" name="data_vencimento_cheque" :value="old('data_vencimento_cheque', $pagamento->data_vencimento_cheque)" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="valor_total_pagamento" value="Valor Total do Pagamento (R$)" />
                                <x-text-input id="valor_total_pagamento" class="block mt-1 w-full" type="number" name="valor_total_pagamento" :value="old('valor_total_pagamento', $pagamento->valor_total_pagamento)" required step="0.01" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('gestor.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 mr-6">
                            Cancelar
                        </a>
                        <x-primary-button>
                            {{ __('Atualizar Pagamento') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>