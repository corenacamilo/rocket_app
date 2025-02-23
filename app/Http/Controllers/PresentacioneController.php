<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresentacioneRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use App\Models\Presentacione;
use App\Models\Caracteristica; // Importar el modelo Caracteristica
use Illuminate\Support\Facades\DB; // Importar DB
use Exception;
use Illuminate\Http\Request;

class PresentacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presentacione = Presentacione::with('caracteristica')->latest()->get();
        return view('presentacione.index', ['presentaciones' => $presentacione]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacione.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePresentacioneRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Lanzar la excepción
        }

        return redirect()->route('presentaciones.index')->with('success', 'Presentación creada correctamente');
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
    public function edit(Presentacione $presentacione)
    {
        return view('presentacione.edit', ['presentacione' => $presentacione]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacioneRequest $request, Presentacione $presentacione)
    {
        Caracteristica::where('id', $presentacione->caracteristica->id)->update($request->validated());
        return redirect()->route('presentaciones.index')->with('success', 'Presentación actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $presentacione = Presentacione::find($id);
        if ($presentacione->caracteristica->estado == 1) {
            Caracteristica::where('id', $presentacione->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'Categoría eliminada correctamente';
        } else {
            Caracteristica::where('id', $presentacione->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'Presentación recuperada correctamente';
        }


        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}
