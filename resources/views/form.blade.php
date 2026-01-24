<x-layout>
    <x-slot:title>FADIVA - Carteirinha de Identificação Estudantil Digital</x-slot:title>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 flex flex-col lg:flex-row overflow-x-hidden">
        <!-- Left Column - Form -->
        <div
            class="w-full lg:w-1/2 min-h-screen lg:h-screen bg-gradient-fadiva flex items-center justify-center p-4 lg:p-6 relative overflow-hidden">
            <!-- Animated Background Gradient -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-gradient-fadiva-animated animate-gradient-shift"></div>
            </div>

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>

            <div class="w-full max-w-lg relative z-10">
                <!-- Header with Logo -->
                <x-header-logo />

                <!-- Form Card -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 lg:p-7 border border-white/20">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-5 text-center">Acesse sua Carteirinha
                    </h2>

                    <form action="{{ route('card.proccess') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- RA Field -->
                        <div>
                            <label for="id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                RA (Registro Acadêmico)
                            </label>
                            <input type="text" id="id" name="id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-fadiva-burgundy focus:ring-2 focus:ring-fadiva-rose/30 transition-all duration-200 outline-none bg-white text-gray-900"
                                placeholder="Digite seu RA">
                        </div>

                        <!-- CPF Field -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1.5">
                                CPF
                            </label>
                            <input type="text" id="document" name="document" required maxlength="14"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-fadiva-burgundy focus:ring-2 focus:ring-fadiva-rose/30 transition-all duration-200 outline-none bg-white text-gray-900"
                                placeholder="000.000.000-00">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="cursor-pointer w-full bg-gradient-fadiva hover:opacity-90 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-fadiva-rose focus:ring-offset-2">
                            Acessar Carteirinha
                        </button>
                    </form>

                    <!-- Alerts -->
                    @if (session('error'))
                        <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg flex items-start shadow-sm transition-all duration-300 animate-fade-in-up"
                            role="alert">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 text-red-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mt-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg flex items-start shadow-sm transition-all duration-300 animate-fade-in-up"
                            role="alert">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 text-emerald-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Right Column - Information (Desktop) / Bottom Section (Mobile) -->
        <x-info-column />
    </div>

    <!-- CPF Mask Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cpfInput = document.getElementById('document');

            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }

                e.target.value = value;
            });
        });
    </script>
</x-layout>
