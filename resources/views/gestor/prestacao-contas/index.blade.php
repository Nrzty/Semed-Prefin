<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Organizar Prestação de Contas</h1>
        <p class="text-gray-500 mt-1">Envie os documentos necessários para o repasse: <strong>{{ $repasse->numero_parcela }}ª Parcela de {{ $repasse->ano_exercicio }}</strong>.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
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

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Checklist de Documentos Gerais</h3>

        <div class="space-y-2">
            @foreach($checklistGeral as $tipo => $nome)
                <div class="flex items-center justify-between p-3 rounded-md @if($documentosEnviados->has($tipo)) bg-green-50 @else hover:bg-gray-50 @endif">
                    <span class="font-medium text-gray-700">{{ $nome }}</span>

                    @if($documentosEnviados->has($tipo))
                        <div class="flex items-center space-x-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Enviado
                            </span>
                            <a href="#" class="text-sm text-indigo-600 hover:underline">Ver</a>
                        </div>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pendente
                        </span>
                    @endif
                </div>
            @endforeach
        </div>

        <form action="{{ route('gestor.repasses.prestacao-contas.upload', $repasse) }}" method="POST" enctype="multipart/form-data" class="mt-6 border-t pt-4">
            @csrf
            <label for="tipo_documento" class="block text-sm font-medium text-gray-700">Selecione o tipo de documento e o ficheiro (apenas PDF, máx 5MB):</label>
            <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                <select name="tipo_documento" id="tipo_documento" class="block w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    {{-- Agora todas as opções aparecem na lista --}}
                    @foreach($checklistGeral as $tipo => $nome)
                        <option value="{{ $tipo }}">{{ $nome }}</option>
                    @endforeach
                </select>
                <input type="file" name="arquivo" id="arquivo" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100" required>
            </div>
            <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Enviar Arquivo
            </button>
        </form>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">8) Kit de Despesas (por Pagamento)</h3>
        <div class="space-y-6">
            @forelse($pagamentos as $pagamento)
                <div class="border rounded-lg p-4">
                    <p class="font-semibold text-gray-800">Pagamento para: <span class="text-indigo-600">{{ $pagamento->nome_fornecedor }}</span></p>
                    <p class="text-sm text-gray-500">Valor: R$ {{ number_format($pagamento->valor_total_pagamento, 2, ',', '.') }} | Data: {{ $pagamento->data_pagamento_efetivo->format('d/m/Y') }}</p>
                    <div class="mt-4 space-y-2">
                        <p class="text-sm font-medium">Documentos do Kit:</p>
                        <ul>
                            <li>Comparativo:
                                @if($pagamento->documentosKitDespesas->where('tipo_documento', 'comparativo')->isNotEmpty())
                                    <span class="text-green-600 font-semibold">OK</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Pendente</span>
                                @endif
                            </li>
                            <li>Orçamentos ({{ $pagamento->documentosKitDespesas->where('tipo_documento', 'orcamento')->count() }}/3):
                                @if($pagamento->documentosKitDespesas->where('tipo_documento', 'orcamento')->count() >= 3)
                                    <span class="text-green-600 font-semibold">OK</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Pendente</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('gestor.repasses.prestacao-contas.upload-kit', ['repasse' => $repasse, 'pagamento' => $pagamento]) }}" method="POST" enctype="multipart/form-data" class="mt-4 border-t pt-4">
                        @csrf
                        <label for="tipo_documento_kit_{{ $pagamento->id }}" class="block text-sm font-medium text-gray-700">Selecione o tipo e envie o(s) arquivo(s) para este pagamento:</label>
                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                            <select name="tipo_documento_kit" id="tipo_documento_kit_{{ $pagamento->id }}" class="block w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm sm:text-sm">
                                <option value="comparativo">Comparativo</option>
                                <option value="orcamento">Orçamento(s)</option>
                                <option value="nota_fiscal">Nota Fiscal</option>
                                <option value="cheque">Cheque</option>
                                <option value="recibo">Recibo</option>
                                <option value="certidao">Certidão</option>
                            </select>
                            <input type="file" name="arquivos_kit[]" id="arquivos_kit_{{ $pagamento->id }}" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        </div>
                        <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800">
                            Enviar Kit
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-center text-gray-500">Nenhum pagamento foi lançado para este repasse ainda.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="font-bold text-lg text-gray-800 border-b pb-2 mb-4">Finalizar Organização</h3>
        <p class="text-sm text-gray-600 mb-4">
            Após enviar todos os documentos obrigatórios, clique no botão abaixo para gerar um único ficheiro PDF com toda a prestação de contas, pronta para ser enviada.
        </p>

        @php
            $documentosGeraisObrigatorios = collect($checklistGeral)->except(['termo_doacao'])->keys();
            $documentosGeraisEnviados = $documentosEnviados->keys();
            $todosGeraisEnviados = $documentosGeraisObrigatorios->diff($documentosGeraisEnviados)->isEmpty();
        @endphp

        @if($todosGeraisEnviados)
            <a href="{{ route('gestor.repasses.prestacao-contas.consolidar', $repasse) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Consolidar e Baixar PDF Final
            </a>
        @else
            <button class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed" disabled>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                (Envie todos os documentos para habilitar)
            </button>
            <p class="text-xs text-gray-500 mt-2">É necessário enviar todos os documentos obrigatórios para finalizar.</p>
        @endif
    </div>
</x-app-layout>
