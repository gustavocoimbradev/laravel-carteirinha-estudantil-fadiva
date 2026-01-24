<x-layout>
    <x-slot:title>Carteirinha Digital FADIVA</x-slot:title>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 flex flex-col lg:flex-row overflow-x-hidden">

        <!-- Left Column - Card Display -->
        <div class="w-full lg:w-1/2 min-h-screen lg:h-screen relative overflow-hidden">
            <!-- Base Background -->
            <div class="absolute inset-0 bg-gradient-fadiva z-0"></div>

            <!-- Animated Background Gradient -->
            <div class="absolute inset-0 opacity-30 pointer-events-none z-0">
                <div class="absolute inset-0 bg-gradient-fadiva-animated animate-gradient-shift"></div>
            </div>

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10 pointer-events-none z-0">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>

            <!-- Scrollable Content -->
            <div
                class="relative z-10 h-full overflow-y-auto p-4 lg:p-6 no-scrollbar flex flex-col items-center justify-center">

                <div class="w-full max-w-[500px] flex flex-col items-center space-y-8 py-8">
                    <!-- Header with Logo -->
                    <x-header-logo />

                    <!-- Card Container -->
                    <div class="w-full perspective-1000">
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-3xl p-4 border border-white/20 shadow-2xl relative transition-all duration-500 ease-out">

                            <!-- Loader -->
                            <div id="cardLoader"
                                class="absolute inset-0 flex flex-col items-center justify-center text-white z-20">
                                <svg class="animate-spin h-10 w-10 mb-3 text-fadiva-rose"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium tracking-wider opacity-90">Gerando
                                    Carteirinha...</span>
                            </div>

                            <!-- Card Content (Hidden initially) -->
                            <div id="cardContent" class="opacity-0 transition-opacity duration-1000 ease-out">
                                <!-- Frente da Carteirinha -->
                                <div class="relative w-full rounded-2xl overflow-hidden shadow-lg bg-gray-200/20"
                                    style="aspect-ratio: 500/315;">
                                    <img src="{{ route('card.image', ['hash' => $hash]) }}" alt="Carteirinha FADIVA"
                                        width="500" height="315" class="w-full h-full object-cover"
                                        oncontextmenu="return false;" onload="showCard()">
                                </div>

                                <!-- Verso da Carteirinha -->
                                <div class="relative w-full rounded-xl overflow-hidden shadow-inner mt-4 bg-white"
                                    style="aspect-ratio: 500/315;">
                                    <img src="{{ asset('img/seguro.png') }}" alt="Verso da Carteirinha"
                                        class="w-full h-full object-cover opacity-90 hover:opacity-100 transition-opacity">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-center gap-3 w-full max-w-lg px-4 no-print">
                        <a href="{{ route('form') }}"
                            class="flex-1 min-w-[120px] py-3 px-4 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-xl shadow-lg hover:bg-white/30 hover:shadow-xl transition-all duration-300 font-semibold text-center text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar
                        </a>

                        <button onclick="document.getElementById('photoModal').classList.remove('hidden')"
                            class="cursor-pointer flex-1 min-w-[140px] py-3 px-4 bg-blue-600/90 hover:bg-blue-600 backdrop-blur-md text-white rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 font-semibold text-center text-sm flex items-center justify-center gap-2">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alterar Foto
                        </button>

                        <a href="{{ route('card.image', ['hash' => $hash]) }}" download="carteirinha-fadiva.jpg"
                            class="flex-1 min-w-[140px] py-3 px-4 bg-white text-fadiva-burgundy rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 font-bold text-center text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Baixar
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Information -->
        <x-info-column />

    </div>

    <!-- Photo Upload Modal -->
    <div id="photoModal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 no-print p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-fadiva p-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Atualizar Foto
                </h2>
                <button onclick="document.getElementById('photoModal').classList.add('hidden')"
                    class="cursor-pointer text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="{{ route('card.upload-photo', ['hash' => $hash]) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4" onsubmit="return handlePhotoSubmit(event)">
                    @csrf

                    <!-- Preview Area -->
                    <div class="relative group cursor-pointer"
                        onclick="document.getElementById('photoInput').click()">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center transition-all hover:border-fadiva-rose hover:bg-gray-50 min-h-[200px] flex items-center justify-center overflow-hidden bg-gray-50"
                            id="dropZone">

                            <img id="photoPreview" src="" alt="Preview"
                                class="hidden absolute inset-0 w-full h-full object-contain p-2 z-10">

                            <div id="uploadPlaceholder" class="space-y-2">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-white group-hover:shadow-md transition-all">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-fadiva-burgundy transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">Clique para selecionar</p>
                                <p class="text-xs text-gray-400">JPG ou PNG (máx. 5MB)</p>
                            </div>
                        </div>
                        <!-- Overlay on hover when image selected -->
                        <div id="reselectOverlay"
                            class="hidden absolute inset-0 bg-black/40 flex items-center justify-center z-20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white font-bold text-sm bg-black/50 px-3 py-1 rounded-full">Alterar
                                seleção
                            </p>
                        </div>
                    </div>

                    <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg"
                        required class="hidden" onchange="previewPhoto(this)">

                    <button type="submit"
                        class="cursor-pointer w-full py-3.5 bg-gradient-fadiva text-white rounded-xl shadow-lg hover:shadow-xl hover:opacity-95 transition-all duration-200 font-bold flex items-center justify-center gap-2">
                        <span>Salvar Foto</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Saul Modal -->
    <div id="saulModal"
        class="hidden fixed inset-0 bg-yellow-500/90 backdrop-blur-md flex items-center justify-center z-[60] no-print p-4">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100 border-4 border-yellow-400">
            <!-- Modal Header -->
            <div class="bg-yellow-400 p-4 flex justify-between items-center">
                <h2 class="text-xl font-extrabold text-red-700 flex items-center gap-2 uppercase tracking-wide"
                    style="font-family: 'Arial Black', sans-serif;">
                    ALERTA LEGAL!
                </h2>
                <button onclick="document.getElementById('saulModal').classList.add('hidden')"
                    class="cursor-pointer text-red-700 hover:text-red-900 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-8 text-center space-y-4">
                <div
                    class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-500">
                    <svg class="w-14 h-14 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-gray-800 uppercase"
                    style="font-family: 'Arial Black', sans-serif; letter-spacing: -1px;">
                    É MELHOR CHAMAR O SAUL!
                </h3>

                <p class="text-gray-600 font-medium">
                    Aconselhamos fortemente que você não tente alterar esta evidência fotográfica sem a presença do seu
                    advogado.
                </p>

                <button onclick="document.getElementById('saulModal').classList.add('hidden')"
                    class="cursor-pointer w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transform transition active:scale-95 uppercase tracking-wider mt-4">
                    Entendi, Sr. McGill
                </button>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media print {
            @page {
                size: auto;
                margin: 0mm;
            }

            body {
                background: white;
                margin: 0;
            }

            .min-h-screen {
                display: block;
                height: auto;
                background: white;
            }

            /* Hide left col inputs/buttons */
            .no-print,
            x-info-column,
            .lg\:w-1\/2 {
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                position: static !important;
            }

            /* Only show the cards */
            .bg-gradient-fadiva {
                background: none !important;
                padding: 0 !important;
            }

            .backdrop-blur-md {
                background: none !important;
                border: none !important;
                box-shadow: none !important;
            }

            img[alt="Carteirinha FADIVA"],
            img[alt="Verso da Carteirinha"] {
                display: block;
                page-break-inside: avoid;
                margin: 20px auto;
                max-width: 100%;
                width: 85mm;
                /* Standard ID card width approx */
            }

            /* Hide everything else */
            x-info-column,
            .sticky-bg,
            .perspective-1000 p {
                display: none !important;
            }
        }

        /* Disable right-click */
        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            pointer-events: none;
        }
    </style>

    <script>
        function showCard() {
            setTimeout(() => {
                document.getElementById('cardLoader').classList.add('hidden');
                document.getElementById('cardContent').classList.remove('opacity-0');
            }, 500); // Pequeno delay para garantir suavidade
        }

        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const dropZone = document.getElementById('dropZone');
            const overlay = document.getElementById('reselectOverlay');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');

                    // Add border success state
                    dropZone.classList.remove('border-gray-300');
                    dropZone.classList.add('border-green-500', 'bg-green-50');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function handlePhotoSubmit(e) {
            const isEasterEgg = @json($isEasterEgg ?? false);

            if (isEasterEgg) {
                e.preventDefault();
                document.getElementById('photoModal').classList.add('hidden');
                document.getElementById('saulModal').classList.remove('hidden');
                return false;
            }

            return true;
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('photoModal').classList.add('hidden');
            }
        });
    </script>
</x-layout>
