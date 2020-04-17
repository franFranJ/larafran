@extends('layouts.app')
@section('content')


        <div class="container">
            <div class="row">
            @foreach($list as $file)
                    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                        <div class="shadow p-3 mb-4 bg-light text-truncate">
                            <i class="far fa-folder"></i>

                        <a href="https://drive.google.com/drive/folders/{{$file->getId()}}" target="_blank">{{$file->getName()}}</a>
                        </div>
                    </div>                    
                @endforeach
            </div>
        </div>
@endsection