<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\comment;
use App\Like;

class ImageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function create() {
        return view('image.create');
    }

    public function save(Request $request) {
        //validación
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required', 'image'
        ]);

        // Recoger datos 
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Asignarle nuevos valores a nuevo objeto
        $user = \Auth::user();
        $image = new image();
        $image->user_id = $user->id;

        $image->description = $description;

        //subir fichero
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        $image->save();

        return redirect()->route('home')->with([
                    'message' => 'La foto ha sido subida correctamente'
        ]);
    }

    public function getImage($filename) {
        $file = Storage::disk('images')->get($filename);

        return new Response($file, 200);
    }

    public function detail($id) {
        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    public function delete($id) {
        $user = \Auth::user();
        $image = Image::find($id);
        $comment = Comment::where('image_id', $id)->get();
        $like = Like::where('image_id', $id)->get();

        if ($user && $image && $image->user->id == $user->id) {
            //Eliminar los comentarios
            if ($comment && count($comment) >= 1) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            }
            //Eliminar los likes
            if ($like && count($like) >= 1) {
                foreach ($likes as $like) {
                    $like->delete();
                }
            }
            //Eliminar los ficheros de la imagen
            Storage::disk('images')->delete($image->image_path);
            
            
            //Eliminar reguistro de la imagen
            
            $image->delete();
            $message = array('message' => 'La imagen se ha borrado');
        }else{
            $message = array('message' => 'La imagen no se ha borrado');
        }
        return redirect()->route('home')->with($message);
    }
    
    
    public function edit($id){
        $user = \Auth::user();
        $image = Image::find($id);
        
      if ($user && $image && $image->user->id == $user->id) {
          return view('image.edit',[
              'image' => $image
          ]);
          
      }else{
          return redirect()->route('home');
      }
    }
    
    public function update(Request $request){
        //validación
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' =>  'image'
        ]);
        
        //recoger datos
        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');
        
        //Conseguir datos 
        $image = Image::find($image_id);
        $image->description = $description;
        
        //subir fichero
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }
        
        // Actualizar reguistro
        $image->update();

        return redirect()->route('image.detail', ['id' => $image_id])->with([
                    'message' => 'La foto ha sido subida correctamente'
        ]);

    }
}
