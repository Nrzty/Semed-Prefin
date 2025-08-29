<x-app-layout>
    <div x-data="{ showModal: false, motivoReprovacao: '' }">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Meus Planos de Aplicação</h1>
                <p class="text-gray-500 mt-1">Acompanhe o status dos seus planos enviados.</p>
            </div>
            <a href="{{ route('gestor.plano-aplicacao.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 text-sm">
                + Novo Plano
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Data de Envio</th>
                            <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Valor Total</th>
                            <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @forelse ($planos as $plano)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 whitespace-nowrap">{{ $plano->created_at->format('d/m/Y \à\s H:i') }}</td>
                                <td class="py-3 px-4 whitespace-nowrap text-center">
                                        <span class="font-medium px-2 py-1 text-xs rounded-full
                                            @switch($plano->status)
                                                @case('Aprovado') bg-green-100 text-green-800 @break
                                                @case('Reprovado') bg-red-100 text-red-800 @break
                                                @default bg-yellow-100 text-yellow-800 @endswitch
                                        ">
                                            {{ $plano->status }}
                                        </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-right font-semibold">
                                    R$ {{ number_format($plano->itens->sum('valor_total'), 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-center">
                                    @if ($plano->status == 'Reprovado')
                                        <a href="{{ route('gestor.plano-aplicacao.edit', $plano) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Revisar Plano
                                        </a>
                                    @elseif ($plano->motivo_reprovacao)
                                        <button
                                            @click="showModal = true; motivoReprovacao = '{{ addslashes($plano->motivo_reprovacao) }}'"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Ver Motivo
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                    Você ainda não enviou nenhum plano de aplicação.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $planos->links() }}
                </div>
            </div>
        </div>

        <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" x-cloak>
            <div @click.away="showModal = false" class="relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Motivo da Reprovação</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-md" x-text="motivoReprovacao"></p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md w-full hover:bg-gray-300">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
