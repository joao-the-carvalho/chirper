<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user   = User::where('username', $username)->firstOrFail();
        $chirps = $user->chirps()->latest()->get();

        return view('profile.show', compact('user', 'chirps'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:30', 'alpha_dash', 'unique:users,username,' . $user->id],
        'bio'      => ['nullable', 'string', 'max:160'],
        'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('avatar')) {
        // Deleta o avatar antigo do Cloudinary se existir
        if ($user->avatar) {
            Cloudinary::destroy($user->avatar);
        }

        // Faz upload e salva o public_id
        $result = Cloudinary::upload(
            $request->file('avatar')->getRealPath(),
            ['folder' => 'chirper/avatars']
        );

        $validated['avatar'] = $result->getPublicId();
    }

    $user->update($validated);

    return redirect()
        ->route('profile.show', $user->username)
        ->with('success', 'Perfil atualizado com sucesso!');
}
}