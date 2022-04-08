<?php

namespace App\Http\Controllers\API;
   use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Validator;


class AuthController extends BaseController
{

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $success['token'] =  $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $success['name'] =  $auth->name;

            return $this->handleResponse($success, 'Usuario logado!');
        }
        else{
            return $this->handleError('Unauthorised.', ['error'=>'Não Autorizado']);
        }
    }

    public function logout(){
        // auth()->user()->tokens()->delete() // remove todos os tokens do usuário
        auth()->user()->currentAccessToken()->delete(); // remove apenas o token de acesso da requisição
        return $this->handleError([], 'Usuário desconectado');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->handleResponse($success, 'Usuario criado com sucesso!');
    }

}
