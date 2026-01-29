<x-layout>
    <x-slot:title>FADIVA - Carteirinha de Identificação Estudantil Digital</x-slot:title>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 flex flex-col lg:flex-row overflow-x-hidden">
        <div
            class="w-full lg:w-1/2 min-h-screen lg:h-screen bg-gradient-fadiva flex items-center justify-center p-4 lg:p-6 relative overflow-hidden">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-gradient-fadiva-animated animate-gradient-shift"></div>
            </div>

            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>

            <div class="w-full max-w-lg relative z-10">
                <x-header-logo />

                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 lg:p-7 border border-white/20">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-5 text-center">Acesse sua Carteirinha
                    </h2>

                    <form action="{{ route('card.proccess') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="user"
                                class="block text-sm font-medium text-gray-700 mb-1.5 flex items-center gap-2">
                                <span>Usuário</span>
                                <div class="group relative flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-3 w-3 text-fadiva-burgundy hover:text-fadiva-burgundy/80 transition-colors duration-200 cursor-help"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 p-3 bg-gray-900/95 text-white text-xs rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 text-center backdrop-blur-sm pointer-events-none">
                                        O usuário e a senha são os mesmos do <span
                                            class="font-bold text-blue-300">Portal do Aluno</span>.
                                        <div
                                            class="absolute top-full left-1/2 transform -translate-x-1/2 border-8 border-transparent border-t-gray-900/95">
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <input type="tel" id="user" name="user" required maxlength="6"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-fadiva-burgundy focus:ring-2 focus:ring-fadiva-rose/30 transition-all duration-200 outline-none bg-white text-gray-900"
                                placeholder="Digite seu usuário">
                        </div>

                        <div>
                            <label for="pass" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Senha
                            </label>
                            <input type="password" id="pass" name="pass" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-fadiva-burgundy focus:ring-2 focus:ring-fadiva-rose/30 transition-all duration-200 outline-none bg-white text-gray-900"
                                placeholder="Digite sua senha">
                        </div>

                        <button type="submit"
                            class="cursor-pointer w-full bg-gradient-fadiva hover:opacity-90 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-fadiva-rose focus:ring-offset-2">
                            Acessar Carteirinha
                        </button>




                    </form>


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

        <x-info-column />
    </div>


</x-layout>
