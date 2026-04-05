<x-layout :title="$user->name">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Card do perfil --}}
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-start gap-5">
                    <img src="{{ $user->avatarUrl() }}"
                         class="w-20 h-20 rounded-full object-cover ring-2 ring-primary flex-shrink-0"
                         alt="Avatar de {{ $user->name }}">

                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-bold text-base-content">{{ $user->name }}</h1>
                        @if ($user->username)
                            <p class="text-sm text-primary">&#64;{{ $user->username }}</p>
                        @endif
                        @if ($user->bio)
                            <p class="mt-2 text-base-content/70 text-sm leading-relaxed">{{ $user->bio }}</p>
                        @endif
                        <p class="text-xs text-base-content/40 mt-2">
                            {{ $chirps->count() }} {{ $chirps->count() === 1 ? 'chirp' : 'chirps' }}
                        </p>
                    </div>

                    @auth
                        @if (Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm">
                                Editar perfil
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Chirps --}}
        <div class="space-y-4">
            @forelse ($chirps as $chirp)
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body py-4">
                        <div class="flex items-center gap-3 mb-1">
                            <img src="{{ $user->avatarUrl() }}"
                                 class="w-8 h-8 rounded-full object-cover"
                                 alt="">
                            <span class="font-semibold text-sm text-base-content">{{ $user->name }}</span>
                            <span class="text-xs text-base-content/40">{{ $chirp->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-base-content/80 text-sm">{{ $chirp->message }}</p>
                    </div>
                </div>
            @empty
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body items-center text-center py-12">
                        <p class="text-base-content/40">Nenhum chirp ainda.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

</x-layout>