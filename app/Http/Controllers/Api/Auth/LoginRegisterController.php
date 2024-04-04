<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginRegisterController extends Controller
{
    /**
     *
     * Inscrire un nouveau utilisateur
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Erreur de validation',
                'data' => $validated->errors(),
            ], 403);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $data
        ]);
    }

    /**
     *
     * Connexion d'un utilisateur
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Erreur de validation',
                'data' => $validated->errors(),
            ], 403);
        }
        // Verifie si l'email existe
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Verifiez vos identifiants',
            ], 401);
        }
        $data['token'] = $user->createToken($request->email)->plainTextToken;

        return $user->createToken($request->email)->plainTextToken;
    }

    /**
     * Deconnexion d'un utilisateur
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'User logged out successfully',
        ], 200);
    }
}