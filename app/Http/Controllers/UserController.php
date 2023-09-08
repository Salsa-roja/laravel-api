<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class   UserController extends Controller
{

    // Función para mostrar una lista de Users
    public function listing()
    {
        $Usuarios = User::all();
     
        return response()->json(($Usuarios));
    }

    public function dtailUser($id)
    {

        $Users = User::where('id', $id)->first();
        return response()->json(($Users));
    }
    // Función para guardar un nuevo User en la base de datos
    public function save(Request $request)
    {
        try {
          
            $request->validate([
                'name' => 'required',
                'email' => 'required'
            ]);
            if ($request->input('id')!=null) {
                $user =  User::find($request->input('id'));
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $Us=User::where(['email' => trim(strtolower($request->input('email')))])->first();
                if ($Us && $Us->email!=$request->input('email')) {
                    $response['msg'] = 'El nombre de usuario ya está en uso, utiliza otro';
                    $response['statusHttp'] = 400;
                    return response()->json('error al actualizar');
                } else {        
                    $user->save();
                    return response()->json($user);
                }
            } else {

                if (User::where(['email' => trim(strtolower($request->input('email')))])
                    ->first()
                ) {
                    $response['msg'] = 'El   de usuario ya está en uso, utiliza otro';
                    $response['statusHttp'] = 400;
                    return response()->json($response);
                } else {
                    $user = new User();
                    $user->name = $request->input('name');
                    $user->email = $request->input('email');
                    $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
                    $user->save();
                    return $user;
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $User = User::findOrFail($id);
            if ($User) {
                $User->delete();
                $response["msg"] = " eliminar";
            } else {
                $response["msg"] = "Error al eliminar";
            }

            return response()->json($response);
        } catch (\Exception $ex) {
            return response()->json(['error' => "error al guardar"], 500);
        };
    }
}
