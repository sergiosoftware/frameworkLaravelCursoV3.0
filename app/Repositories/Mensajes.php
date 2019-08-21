<?php

namespace App\Repositories;

use App\Mensaje;
use Illuminate\Support\Facades\Cache;

class Mensajes {

   public function getPaginacion() {
       $key = "mensajes.pagina." . request('page', 1);

       return Cache::store('redis')->tags('mensajes')
                                   ->rememberForever($key, function () {
           return Mensaje::with([
               'user',
               'nota',
               'etiquetas',
           ])->orderBy('created_at', request('orden', 'DESC'))
             ->paginate(10);
       });
   }

   public function guardar($request) {
       if (auth()->check()) {
           $request->request->add([
               'nombre' => auth()->user()->name,
               'email' => auth()->user()->email,
           ]);
       }

       $mensaje = Mensaje::create($request->all());

       if (auth()->check()) {
           auth()->user()->messages()->save($mensaje);
       }

       Cache::tags('mensajes')->flush();
       return $mensaje;
   }

   public function buscarPorId($id) {
       return Cache::store('redis')->tags('mensajes')
              ->rememberForever("mensajes.{$id}", function () use ($id) {
           return Mensaje::findOrFail($id);
       });
   }

   public function actualizar($request, $id) {
       Cache::tags('mensajes')->flush();
       return Mensaje::findOrFail($id)->update($request->all());
   }

   public function eliminar($id) {
       Cache::tags('mensajes')->flush();
       return Mensaje::findOrFail($id)->delete();
   }
}