<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request->all());    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',

                Password::min(8)
                // ->letters()
                // ->mixedCase()
                // ->numbers()
                // ->symbols()
                // ->uncompromised()
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login existing user
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    // Logout current user
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function getUserData(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'posts' => $user->posts()->with('comments')->get(),
            'comments' => $user->comments()->with('post')->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender' => 'nullable|string|in:male,female,other',
        ]);


        // Only update provided fields
        $updateData = [];
        if (isset($validated['name'])) $updateData['name'] = $validated['name'];
        if (isset($validated['email'])) $updateData['email'] = $validated['email'];
        if (isset($validated['bio'])) $updateData['bio'] = $validated['bio'];
        if (isset($validated['gender'])) $updateData['gender'] = $validated['gender'];

        // Handle avatar upload and save correct path
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = '/storage/' . $avatarPath;
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
