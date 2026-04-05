<x-layout title="Editar Perfil">

    <div class="max-w-2xl mx-auto space-y-6">

        <h2 class="text-2xl font-bold text-base-content">Editar Perfil</h2>

        <div class="card bg-base-100 shadow-md">
            <div class="card-body">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar --}}
                    <div class="flex items-center gap-5">
                        <img id="avatar-preview"
                             src="{{ auth()->user()->avatarUrl() }}"
                             class="w-20 h-20 rounded-full object-cover ring-2 ring-primary"
                             alt="Avatar">
                        <div class="space-y-1">
                            <label class="label label-text font-medium" for="avatar">Foto de perfil</label>
                            <input id="avatar" name="avatar" type="file" accept="image/*"
                                   class="file-input file-input-bordered file-input-sm w-full max-w-xs"
                                   onchange="previewAvatar(event)">
                            @error('avatar')
                                <p class="text-error text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nome --}}
                    <div class="form-control">
                        <label class="label label-text font-medium" for="name">Nome</label>
                        <input id="name" name="name" type="text"
       class="input input-bordered w-full text-base-content @error('name') input-error @enderror"
       value="{{ old('name', auth()->user()->name) }}"
       required autofocus>
                        @error('name')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="form-control">
                        <label class="input input-bordered flex items-center gap-1 text-base-content @error('username') input-error @enderror">
                            <span class="text-base-content/50">@</span>
                            <input id="username" name="username" type="text"
                                class="grow text-base-content"
                                value="{{ old('username', auth()->user()->username) }}">
                        </label>
                    @error('username')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div class="form-control">
                        <label class="label label-text font-medium" for="bio">
                            Bio
                            <span class="label-text-alt text-base-content/50" id="bio-count">
                                {{ strlen(old('bio', auth()->user()->bio ?? '')) }}/160
                            </span>
                        </label>
                        <textarea id="bio" name="bio" rows="3" maxlength="160" class="textarea textarea-bordered w-full resize-none text-base-content @error('bio') textarea-error @enderror" placeholder="Fale um pouco sobre você...">
                            {{ old('bio', auth()->user()->bio) }}
                        </textarea>
                        @error('bio')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        @if (auth()->user()->username)
                            <a href="{{ route('profile.show', auth()->user()->username) }}"
                               class="btn btn-ghost">Cancelar</a>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('avatar-preview').src = URL.createObjectURL(file);
            }
        }

        const bioInput = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');
        bioInput.addEventListener('input', () => {
            bioCount.textContent = bioInput.value.length + '/160';
        });
    </script>

</x-layout>