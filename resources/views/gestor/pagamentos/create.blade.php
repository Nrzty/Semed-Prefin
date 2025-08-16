<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Pagamentos') }}
        </h2>
    </x-slot>

    <div> 
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div class="p-6 text-gray-900 flex flex-col h-full">
                
                <div class="mb-6 flex-shrink-0">
                    <h3 class="text-2xl font-bold text-gray-800">Lançar Nova Despesa</h3>
                    <p class="text-gray-500 mt-1">Preencha os campos para registrar uma nova despesa.</p>
                </div>

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

                <form method="POST" action="{{ route('gestor.pagamentos.store') }}" class="flex-grow flex flex-col justify-between">
                    @csrf
                    
                    <div class="space-y-4">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">Informações do Fornecedor</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <x-input-label for="nome_fornecedor" value="Nome / Razão Social" class="text-xs"/>
                                    <x-text-input id="nome_fornecedor" class="block mt-1 w-full bg-gray-50 text-sm" type="text" name="nome_fornecedor" :value="old('nome_fornecedor')" required autofocus />
                                </div>

                                <div>
                                    <x-input-label for="cnpj_cpf_fornecedor" value="CNPJ ou CPF" class="text-xs"/>
                                    <x-text-input id="cnpj_cpf_fornecedor" class="block mt-1 w-full bg-gray-50 text-sm" type="text" name="cnpj_cpf_fornecedor" :value="old('cnpj_cpf_fornecedor')" required />
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">Informações da Despesa</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="tipo_despesa" value="Tipo da Despesa" class="text-xs"/>
                                    <select id="tipo_despesa" name="tipo_despesa" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-gray-50 text-sm" required>
                                        <option value="" disabled selected>Selecione uma opção</option>
                                        <option value="Material de Custeio" @selected(old('tipo_despesa') == 'Material de Custeio')>Material de Custeio</option>
                                        <option value="Prestação de Serviço" @selected(old('tipo_despesa') == 'Prestação de Serviço')>Prestação de Serviço</option>
                                        <option value="Material de Capital" @selected(old('tipo_despesa') == 'Material de Capital')>Material de Capital</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="numero_nota_fiscal" value="Número da Nota Fiscal" class="text-xs"/>
                                    <x-text-input id="numero_nota_fiscal" class="block mt-1 w-full bg-gray-50 text-sm" type="text" name="numero_nota_fiscal" :value="old('numero_nota_fiscal')" required />
                                </div>
                                <div>
                                    <x-input-label for="data_emissao_documento" value="Data de Emissão (NF)" class="text-xs"/>
                                    <x-text-input id="data_emissao_documento" class="block mt-1 w-full bg-gray-50 text-sm" type="date" name="data_emissao_documento" :value="old('data_emissao_documento')" required />
                                </div>
                                <div>
                                    <x-input-label for="data_pagamento_efetivo" value="Data do Pagamento Efetivo" class="text-xs"/>
                                    <x-text-input id="data_pagamento_efetivo" class="block mt-1 w-full bg-gray-50 text-sm" type="date" name="data_pagamento_efetivo" :value="old('data_pagamento_efetivo')" required />
                                </div>
                                <div>
                                    <x-input-label for="numero_cheque" value="Número do Cheque" class="text-xs"/>
                                    <x-text-input id="numero_cheque" class="block mt-1 w-full bg-gray-50 text-sm" type="text" name="numero_cheque" :value="old('numero_cheque')" required/>
                                </div>
                                <div>
                                    <x-input-label for="data_vencimento_cheque" value="Data do Pagamento do Cheque" class="text-xs"/>
                                    <x-text-input id="data_vencimento_cheque" class="block mt-1 w-full bg-gray-50 text-sm" type="date" name="data_vencimento_cheque" :value="old('data_vencimento_cheque')" required/>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="valor_total_pagamento" value="Valor Total do Pagamento (R$)" class="text-xs"/>
                                    <x-text-input id="valor_total_pagamento" class="block mt-1 w-full bg-gray-50 text-sm" type="number" name="valor_total_pagamento" :value="old('valor_total_pagamento')" required step="0.01" placeholder="Ex: 150.75"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-200 flex-shrink-0">
                        <a href="{{ route('gestor.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 mr-6">
                            Cancelar
                        </a>
                        <x-primary-button>
                            {{ __('Salvar Pagamento') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>