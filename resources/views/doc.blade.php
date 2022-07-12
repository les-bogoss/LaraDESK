@extends('layouts.app')

@section('title', 'Doc')

@section('content')
    <style>
        .gist {
            height: calc(100vh - 75px);
            width: calc(100vw - 75px);
            position: absolute;
            top: 75px;
            left: 75px;
            overflow: scroll;
            overflow-x: hidden;
        }
        img {
            width: 125px;

        }
    </style>
    <script src="https://gist.github.com/celian-hamon/a474f4af482046a7bbd42deaf342efa6.js"></script>
@endsection
