<?php
    use App\Models\Purchase;
?>

@extends('front.template.master')

@section('title')
    Home
@endsection

@section('head_additional')
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
                width: 100%;
            }

            .title {
                font-size: 84px;
            }

            .links-item > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: bold;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .machine-group {
                position: relative;
                display: block;
                width: calc(100% - 60px);
                margin: auto;
                font-size: 0px;
                text-align: center;
            }

            .machine-item {
                position: relative;
                display: inline-block;
                vertical-align: top;
                width: calc((100% / 3) - 30px);
                padding: 30px 20px;
                /*border: 1px solid #feeb2d;*/
                background: #feeb2d;
                box-shadow: 10px 10px 0px #4c4c4c;
                margin-right: 30px;
                font-size: 16px;
                text-align: left;
                line-height: 20px;
                height: 200px;
                overflow: auto;
                color: #222;
                margin-bottom: 30px;
            }

            .machine-item:nth-child(2) {
                margin-right: 0px;
            }
            
            .machine-item:last-child {
                margin-right: 0px;
                display: block;
                margin: auto;
                width: calc(100% - 30px - (100% / 3));
                margin-bottom: 50px;
                height: 530px;
            }

            .machine-title {
                position: relative;
                display: block;
                font-size: 18px;
                font-weight: normal;
                font-weight: bold;
                margin: 0px 0px 20px;
            }

            .machine-list-item {
                position: relative;
                display: block;
                padding: 10px;
                border-bottom: 1px solid #222;
            }

            .machine-list-item span {
                position: relative;
                display: block;
            }

            .machine-list-item span:first-child {
                font-size: 12px;
            }

            .machine-list-item:last-child {
                border-bottom: 0px;
            }
        </style>
@endsection

@section('js_additional')
    <script>
        $(document).ready(function(){
            $('.mh-now').html("Loading...");

            $.ajax({
                type: "GET",
                // url: "https://eth.nanopool.org/account/0x9f3d97d8887eb429c0c500d4084f151796212e1e",
                url: "https://api.nanopool.org/v1/eth/balance/0x9f3d97d8887eb429c0c500d4084f151796212e1e",
                // url: "http://www.google.com",
                success:function(msg) {
                    var datax = msg.data;

                    $('.mh-now').html(msg.data);
                    // var now = parseFloat(0.02 / 3600);
                    var now = parseFloat($('.mh-now').text());
                    // var now = parseInt($('.mh-now').text());
                    // var now = $('.mh-now').text();
                    alert(now);

                    setInterval(function(){
                        $('.mh-now').text(now += (0.02 / 3600));
                    }, 1000);
                },
                error:function(msg) {
                    $('.mh-now').html('fail' + msg.responseText);
                }
            });
        });
    </script>
@endsection

@section('content')
        <div class="content-container" style="max-width: none; width: 100%;">
            <h1 class="content-title" style="font-size: 25px;">
                <span style="font-size: 45px;">Dashboard</span><br>
                ETH - Mining pool
            </h1>

            @if (Auth::check())
                <div class="machine-group">
                    <div class="machine-item">
                        <h2 class="machine-title">
                            MACHINE STATUS
                        </h2>
                        <div class="machine-list">
                            <?php
                                $total = 0;
                                $purchases = Purchase::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->where('status', '=', 'Paid')->get();
                                foreach ($purchases as $purchase) {
                                    $total = $total + $purchase->mh;
                                }
                            ?>
                            Your MH : {{$total}}<br>
                            Machine Status : Active
                        </div>
                    </div>
                    <div class="machine-item">
                        <h2 class="machine-title">
                            MINING STATUS
                        </h2>
                        <div class="machine-list mh-now">
                            {{-- 0.89009987 --}}
                        </div>
                    </div>
                    <div class="machine-item">
                        <h2 class="machine-title">
                            MINING HISTORY TODAY
                        </h2>
                        <div class="machine-list">
                            <div class="machine-list-item">
                                <span>
                                    13:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    12:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    11:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    10:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    09:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    08:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                            <div class="machine-list-item">
                                <span>
                                    07:00
                                </span>
                                <span>
                                    0.89000009
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
            @endif
        </div>
@endsection