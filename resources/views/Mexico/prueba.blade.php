@extends('layout.app')
@section('conten')

<p>Idioma actual: {{ app()->getLocale() }}</p>

@endsection