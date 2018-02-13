<?php
    use App\Models\Purchase;
?>

@extends('front.template.master')

@section('title')
    Dashboard
@endsection

@section('head_additional')
    {!!HTML::style('css/front/dashboard.css')!!}

    <style>
        .machine-item {
            position: relative;
            display: inline-block !important;
            vertical-align: top !important;
            width: calc(100% / 3) !important;
            height: 100px !important;
            text-align: center;
            font-size: 25px;
            line-height: 100px;
            max-height: none;
            padding: 0px;
            margin: 0px 20px !important;
        }
    </style>
@endsection

@section('js_additional')
    
@endsection

@section('content')
    <div class="content-container" style="max-width: none; width: 100%; text-align: center;">
        <h1 class="content-title" style="font-size: 20px;">
            <span class="mh-now" style="font-size: 45px;">
                DASHBOARD
            </span><br>
            Click links below to manage your mining status
        </h1>

        <a href="{{ url('dashboard/mh') }}" class="machine-item dash first">
            CLOUD MINING
        </a>
        <a href="{{ url('dashboard/machine') }}" class="machine-item dash">
            DEDICATED MACHINE MINING
        </a>
    </div>
@endsection