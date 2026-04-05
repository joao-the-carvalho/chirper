<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @if (session('status') === 'profile-updated')
                    <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg text-sm">
                        Perfil atualizado com sucesso!
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar --}}
                    <div class="flex items-center gap-5">
                        <img id="avatar-preview"
                             src="{{ $user->avatarUrl() }}"
                             class="w-20 h-20 rounded-full object-cover ring-2 ring-indigo-300"
                             alt="Avatar">
                        <div>
                            <x-input-label for="avatar" :value="__('Foto de perfil')" />
                            <input id="avatar" name="avatar" type="file" accept="image/*"
                                   class="mt-1 block text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                          file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 cursor-pointer"
                                   onchange="previewAvatar(event)">
                            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                        </div>
                    </div>

                    {{-- Nome --}}
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $user->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    {{-- Username --}}
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <div class="flex mt-1">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300
                                         dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-sm">
                                @
                            </span>
                            <x-text-input id="username" name="username" type="text"
                                          class="block w-full rounded-l-none"
                                          :value="old('username', $user->username)" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('username')" />
                    </div>

                    {{-- Bio --}}
                    <div>
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea id="bio" name="bio" rows="3" maxlength="160"
                                  class="mt-1 block w-full border-gray-300 dark:border-gray-700
                                         dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                                         dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600
                                         rounded-md shadow-sm resize-none"
                                  placeholder="Fale um pouco sobre você...">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1 text-right" id="bio-count">
                            {{ strlen(old('bio', $user->bio ?? '')) }}/160
                        </p>
                        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Salvar') }}</x-primary-button>

                        <a href="{{ route('profile.show', $user->username ?? '') }}"
                           class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview do avatar antes de enviar
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('avatar-preview').src = URL.createObjectURL(file);
            }
        }

        // Contador da bio
        const bioInput = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');
        bioInput.addEventListener('input', () => {
            bioCount.textContent = bioInput.value.length + '/160';
        });
    </script>
</x-app-layout>