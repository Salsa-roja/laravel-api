<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\hash;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function save(Request $request)
    {
        try {
            $request->validate( [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
            if($request->input('name')<=0){
                if (User::where(['email' => trim(strtolower($request->input('email')))])
                ->first()) {
                    $response['msg'] = 'El nombre de usuario ya está en uso, utiliza otro';
                    $response['statusHttp'] = 400;
                    return response()->json($response);
                }
            }
            #valida si el usuario ya existe
            if (User::where(['email' => trim(strtolower($request->input('email')))])
            ->first()) {
                $response['msg'] = 'El nombre de usuario ya está en uso, utiliza otro';
                $response['statusHttp'] = 400;
                return response()->json($response);
            }

            $user = new User();
            $user->name =$request->input('name');
            $user->email =$request->input('email');
            $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
            $user->save();
            return $user;
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $user = User::where(['email' => trim(strtolower($request->input('email')))])
            ->first();



        if (!$user) {
            return [
                'status' => 400,
                'token' => '',
                'message' => 'El usuario no existe'
            ];
        }

        // Verify the password and generate the token
        if (Hash::check($request->input('password'), $user->password)) {
            $token = $user->createToken("token");
            return [
                'status' => true,
                'token' => $token->plainTextToken,
                'info' => $user,
                'message' => 'Autorizado'
            ];
        }

        // Bad Request response
        return [
            'status' => 400,
            'token' => '',
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
}
