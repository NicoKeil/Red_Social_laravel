
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Mis imagenes favoritas</h1>
            <hr>
            @foreach ($likes as $like)
            @include('includes.image', ['image'=>$like->image])
            @endforeach
        </div>
        <!--paginación -->
        <div class="clear-fix"></div>
            {{$likes->links()}}
    </div>
</div>
@endsection
