<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; // Importar el modelo Categoria
use App\Models\Presentacione; // Importar el modelo Presentacione
use App\Models\Marca; // Importar el modelo Marca
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Facades\DB; // Importar DB
use Exception;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categorias.caracteristica','marca.caracteristica','presentacione.caracteristica'])->latest()->get();
        return view("producto.index",compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.create', compact('categorias', 'marcas', 'presentaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            DB::beginTransaction();
            // Tabla de Productos
            $producto = new Producto();
            if ($request->hasFile('img_path')) {
                $name = $producto->handleUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id
            ]);
            $producto->save();

            // Tabla categoria producto
            $categorias = $request->get('categorias');
            $producto->categorias()->attach($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
             // Lanzar la excepción para manejarla adecuadamente
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
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
    public function edit(Producto $producto)
    {
        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();
        return view('producto.edit',compact('producto','marcas','presentaciones','categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            DB::beginTransaction();
            // Tabla de Productos
            
            if ($request->hasFile('img_path')) {
                $name = $producto->handleUploadImage($request->file('img_path'));


                if(Storage::disk('public')->exists('productos/'.$producto->img_path)){
                    Storage::disk('public')->delete('productos/'.$producto->img_path);
                }


            } else {
                $name = $producto->img_path;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id
            ]);
            $producto->save();

            // Tabla categoria producto
            $categorias = $request->get('categorias');
            $producto->categorias()->sync($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
             // Lanzar la excepción para manejarla adecuadamente
        }

        return redirect()->route('productos.index')->with('success','Producto editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)->update([
                'estado' => 0
            ]);
            $message = 'Producto eliminado correctamente';
        } else {
            Producto::where('id', $producto->id)->update([
                'estado' => 1
            ]);
            $message = 'Producto recuperado correctamente';
        }

        return redirect()->route('productos.index')->with('success', $message);
    }
}
