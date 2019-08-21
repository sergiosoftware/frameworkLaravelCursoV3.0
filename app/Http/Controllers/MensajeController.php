<?php

namespace App\Http\Controllers;

use App\Events\MensajeRecibido;
use App\Repositories\Mensajes;
use Illuminate\Http\Request;

class MensajeController extends Controller {

   protected $mensajes;

   function __construct(Mensajes $mensajes) {
       $this->mensajes = $mensajes;
       $this->middleware('auth', ['except' => ['create', 'store']]);
   }

   public function index() {
       $mensajes = $this->mensajes->getPaginacion();
       return view('mensajes.index', compact('mensajes'));
   }



   public function create() {
       $mostrarCampos = auth()->guest();
       return view('mensajes.crear', 
                    compact('mostrarCampos', 'mostrarCampos'));
   }

   public function store(Request $request) {
       $mensaje = $this->mensajes->guardar($request);
       event(new MensajeRecibido($mensaje));
       return redirect()->route('mensajes.create')
                        ->with('info', 'Hemos recibido tu mensaje');
   }

   public function show($id) {
       $mensaje = $this->mensajes->buscarPorId($id);
       return view('mensajes.show', compact('mensaje'));
   }

   public function edit($id) {
       $mensaje = $this->mensajes->buscarPorId($id);
       $mostrarCampos = !$mensaje->user_id;
       return view('mensajes.editar', 
                    compact('mensaje', 'mostrarCampos'));
   }

   public function update(Request $request, $id) {
       $this->mensajes->actualizar($request, $id);

       return redirect()->route('mensajes.index');
   }

   public function destroy($id) {
       $this->mensajes->eliminar($id);
       return redirect()->route('mensajes.index');
   }
}