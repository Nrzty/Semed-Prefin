<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Planos para Análise</h1>
        <p class="text-gray-500 mt-1">Lista de planos de aplicação pendentes de aprovação ou reprovação.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Escola</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Enviado por</th>
                        <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Data de Envio</th>
                        <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse ($planos as $plano)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 whitespace-nowrap">{{ $plano->escola->nome_escola }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $plano->user->name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-center">{{ $plano->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.planos.show', $plano) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Analisar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                                Nenhum plano para análise no momento.
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
</x-app-layout>
