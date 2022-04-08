<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\Agente as AgenteResource;
use App\Models\Agente;
use Validator;


class AgenteController extends BaseController
{

    public function index()
    {
        $agentes = Agente::all();
        return $this->handleResponse(AgenteResource::collection($agentes), 'Agentes have been retrieved!');
        //return $agentes;
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nome' => 'required',
            'matricula' => 'required'
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());
        }
        $agente = Agente::create($input);
        return $this->handleResponse(new AgenteResource($agente), 'Agente Criado!');

    }


    public function show($id)
    {
        $agente = Agente::find($id);
        if (is_null($agente)) {
            return $this->handleError('Agente not found!');
        }
        return $this->handleResponse(new AgenteResource($agente), 'Agente encontrado.');
    }


    public function update(Request $request, Agente $agente)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nome' => 'required',
            'matricula' => 'required'
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $agente->nome = $input['nome'];
        $agente->matricula = $input['matricula'];
        $agente->save();

        return $this->handleResponse(new AgenteResource($agente), 'Agente atualizado com sucesso!');
    }

    public function destroy(Agente $agente)
    {
        $agente->delete();
        return $this->handleResponse([], 'Agente deletado!');
    }
}
