<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function save(Request $request){
        
        //validación
        $validate = $this->validate($request, [
            'image_id' => ['int', 'required'],
            'content' => ['string', 'required'],
        ]);
        //Recoger datos
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');
        
        //Asigno nuevos valores a mi nuevo objeta a guardar
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;
        
        //guardar en la base de datos
        $comment->save();
        
        //redirección
        
        return redirect()->route('image.detail', ['id' => $image_id])
                         ->with([
                           'message' => 'Has publicado tu comentario correctamente'  
                         ]);
    }
    
    public function delete($id) {
        //conseguir datos del usuario identificado 
        $user = \Auth::user();
        
        //conseguir el objeto del comentario
        $comment = Comment::find($id);
        
        //comprobar si soy el dueño del comentario o de la publicación
        
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete();
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                           'message' => 'Comentario eliminado correctamente'  
                         ]);
        }else{
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                           'message' => 'El comentario no se ha eliminado'  
                         ]);
            
        }
        
    }
}
