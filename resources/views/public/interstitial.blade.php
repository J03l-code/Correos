@extends('layouts.public')

@section('title', 'Acceso Seguro')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div 
    x-data="{ 
        countdown: {{ $link->redirect_mode === 'automatic' ? 3 : 0 }},
        loading: false,
        error: '',
        proceed() {
            if (this.loading) return;
            this.loading = true;
            this.error = '';
            
            fetch('{{ route('public.redirect', $link->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('No se pudo verificar el acceso o el cupo se ha completado.');
                return res.json();
            })
            .then(data => {
                window.location.href = data.url;
            })
            .catch(err => {
                this.loading = false;
                this.error = err.message;
            });
        }
    }"
    x-init="
        if (countdown > 0) {
            let timer = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timer);
                    proceed();
                }
            }, 1000);
        }
    "
    class="max-w-xl mx-auto px-4 py-16"
>
    
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xl text-center space-y-6">
        
        <!-- Logo -->
        <div class="flex justify-center">
            @if($settings['logo'])
                <img src="{{ $settings['logo'] }}" alt="Logo Oficial" class="h-16 w-auto object-contain">
            @else
                <span class="text-2xl font-black tracking-wider text-[var(--primary)] font-montserrat">QUITO <span class="text-[var(--coral)]">2026</span></span>
            @endif
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Estás accediendo a
            </h1>
            <h2 class="text-xl font-bold text-[var(--coral)] font-montserrat">
                {{ $link->title }}
            </h2>
        </div>

        @if($link->description)
            <p class="text-xs text-gray-500 font-semibold leading-relaxed px-2 bg-gray-50 py-3 rounded-xl border border-gray-100">
                {{ $link->description }}
            </p>
        @endif

        <!-- Warnings & Rules -->
        <div class="bg-amber-50/50 border border-amber-200/80 rounded-2xl p-4 text-left space-y-2">
            <h4 class="text-xs font-black uppercase text-amber-800 tracking-wider flex items-center font-montserrat">
                <i data-lucide="shield-alert" class="h-4 w-4 mr-1.5 flex-shrink-0 text-amber-600"></i>
                Recomendaciones de Seguridad
            </h4>
            <ul class="text-[10px] text-amber-800/90 font-bold space-y-1.5 list-disc pl-4 leading-relaxed">
                <li>Este enlace es oficial de la plataforma Quito 2026.</li>
                <li>Por tu seguridad, no compartas este enlace directamente.</li>
                <li>Respeta las normas de convivencia del grupo al ingresar.</li>
            </ul>
        </div>

        <!-- Action / State -->
        <div class="pt-4 space-y-4">
            
            <!-- Countdown for automatic mode -->
            @if($link->redirect_mode === 'automatic')
                <div x-show="countdown > 0" class="text-xs font-bold text-gray-400">
                    Redirigiéndote automáticamente en <span class="text-[var(--coral)] text-sm font-black" x-text="countdown">3</span> segundos...
                </div>
            @endif

            <!-- Error message if fetch fails -->
            <div x-show="error" x-text="error" class="text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 py-2.5 px-3 rounded-xl" style="display: none;"></div>

            <!-- Loader or Action Button -->
            <div class="flex flex-col gap-3">
                <button 
                    type="button" 
                    @click="proceed()"
                    :disabled="loading"
                    class="w-full inline-flex items-center justify-center font-extrabold text-xs text-white bg-[var(--coral)] hover:bg-[var(--coral-dark, #ED4654)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--coral)] rounded-xl py-3 cursor-pointer min-h-[44px] shadow-lg shadow-[var(--coral)]/20 transition-all duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:shadow-none active:scale-95"
                >
                    <!-- Spinner -->
                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <span x-text="loading ? 'Procesando acceso...' : 'Confirmar e ingresar al enlace'">Confirmar e ingresar al enlace</span>
                </button>

                <a href="{{ route('public.index') }}" class="text-xs text-gray-500 font-bold hover:underline focus:outline-none py-2">
                    Volver al inicio
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
