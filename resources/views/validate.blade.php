<x-layout>
    <x-slot:title>Validação de Carteirinha - FADIVA</x-slot:title>

    <div class="min-h-screen bg-gradient-fadiva flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-30 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-fadiva-animated animate-gradient-shift"></div>
        </div>

        <!-- Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative z-10 w-full flex flex-col items-center">

            <x-header-logo />

            <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                @if ($valid)
                    <!-- Status Bar: VÁLIDO -->
                    <div class="p-6 flex flex-col items-center gap-4 pt-8">
                        <!-- Foto -->
                        <div
                            class="w-32 h-32 rounded-full border-4 border-gray-100 shadow-inner overflow-hidden relative bg-gray-200">
                            @php
                                if ($id == '00000') {
                                    $photoPath = public_path('img/jimmy.jpg');
                                } else {
                                    $photoPath = storage_path('app/public/photos/' . $id . '.jpg');
                                }
                                $hasPhoto = file_exists($photoPath);
                            @endphp

                            @if ($hasPhoto)
                                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoPath)) }}"
                                    alt="Foto do Estudante" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Dados -->
                        <div class="text-center space-y-3 w-full">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Nome do Estudante</p>
                                <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $data['name'] }}</h2>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">RA</p>
                                    <p class="font-semibold text-gray-800">{{ $data['ra'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Nascimento</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ date('d/m/Y', strtotime($data['birth'])) }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Curso</p>
                                    <p class="font-medium text-gray-800 leading-tight">
                                        {{ $data['last_enrollment']['course']['description'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Turma</p>
                                    <p class="font-medium text-gray-800">{{ $data['last_enrollment']['class']['id'] }}
                                    </p>
                                </div>
                            </div>

                            <!-- Validade -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Validade do Documento</p>
                                <p class="text-xl font-bold text-fadiva-burgundy">
                                    {{ date('d/m/Y', strtotime($data['card']['expiration'])) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
                        <p class="text-[10px] text-gray-400">Verificado em {{ date('d/m/Y H:i:s') }}</p>
                    </div>
                @else
                    <div class="p-8 text-center space-y-4 pt-12">
                        <div
                            class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto text-red-500 mb-2">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800">Dados não encontrados</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Não foi possível validar este documento. O QR Code pode estar expirado ou os dados não
                            conferem
                            com nossos registros.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
                        <p class="text-[10px] text-gray-400">Verificado em {{ date('d/m/Y H:i:s') }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-8">
                <a href="{{ route('form') }}"
                    class="text-sm font-medium text-white/80 hover:text-white transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</x-layout>
