<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use Illuminate\Http\Request;
use App\Models\Documento; // Importar el modelo Documento
use App\Models\Persona; // Importar el modelo Persona
use App\Models\Proveedore; // Importar el modelo Proveedore
use Illuminate\Support\Facades\DB; // Importar DB
use Exception;

class ProveedoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedore::with('persona.documento')->get();
        return view("proveedore.index", compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        return view("proveedore.create", compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->proveedore()->create([
                'persona_id'=> $persona->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Lanzar la excepción para manejarla adecuadamente
        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedore $proveedore)
    {
        $proveedore->load('persona.documento');
        $documentos = Documento::all();
        return view('proveedore.edit', compact('proveedore', 'documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedoreRequest $request, Proveedore $proveedore)
    {
        try {
            DB:: beginTransaction();
            Persona::where('id',$proveedore->persona->id)
            ->update($request->validated());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('proveedores.index')->with('success','Proveedor editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $persona = Persona::find($id);
        if ($persona->estado == 1) {
            Persona::where('id', $persona->id)->update([
                'estado' => 0
            ]);
            $message = 'Proveedor eliminado correctamente';
        } else {
            Persona::where('id', $persona->id)->update([
                'estado' => 1
            ]);
            $message = 'Proveedor recuperado correctamente';
        }

        return redirect()->route('proveedores.index')->with('success', $message);
    }
}
