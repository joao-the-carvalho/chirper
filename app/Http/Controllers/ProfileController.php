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

    $disk = app()->isProduction() ? 's3' : 'public';

    if ($request->hasFile('avatar')) {
        try {
            if ($user->avatar) {
                if (Storage::disk($disk)->exists($user->avatar)) {
                    Storage::disk($disk)->delete($user->avatar);
                }
            }

            $path = $request->file('avatar')->store('avatars', [
                'disk'       => $disk,
                'visibility' => 'public',
            ]);

            if (!$path) {
                throw new \Exception('Falha ao salvar o arquivo');
            }

            $validated['avatar'] = $path;
            
            \Log::info('Avatar salvo com sucesso', [
                'user_id' => $user->id,
                'path' => $path,
                'disk' => $disk
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar avatar: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Erro ao fazer upload da imagem: ' . $e->getMessage())
                ->withInput();
        }
    }

    $user->update($validated);

    return redirect()
        ->route('profile.show', $user->username)
        ->with('success', 'Perfil atualizado com sucesso!');
}
}