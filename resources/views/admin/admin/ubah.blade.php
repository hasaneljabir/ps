@extends('admin.template')

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Ubah Administrator</h3>
    </div>

    <div class="col-md-12 m-t-20">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Perhatian</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    {!! Form::model($data, ['method'=>'PATCH', 'route'=>['admin.update', $data->id]]) !!}
        
        @include('admin.admin.form')

    {!! Form::close() !!}

</div>
@endsection