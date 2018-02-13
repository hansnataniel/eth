<?php
    use App\Models\Purchase;
    use App\Models\Machinemininghistory;
?>

@extends('front.template.master')

@section('title')
    DEDICATED MACHINE MINING
@endsection

@section('head_additional')
    {!!HTML::style('css/front/dashboard.css')!!}

    <style type="text/css">
        .detail-button {
            position: relative;
            display: inline-block;
            width: auto;
            padding: 5px 15px;
            background: #222;
            color: #fff;
            text-align: center;
            border: 1px solid #222;

            -webkit-transition: background 0.4s, color 0.4s;
            -moz-transition: background 0.4s, color 0.4s;
            -ms-transition: background 0.4s, color 0.4s;
            transition: background 0.4s, color 0.4s;
        }

        .detail-button:hover {
            background: transparent;
            color: #222;

            -webkit-transition: background 0.4s, color 0.4s;
            -moz-transition: background 0.4s, color 0.4s;
            -ms-transition: background 0.4s, color 0.4s;
            transition: background 0.4s, color 0.4s;
        }
    </style>
@endsection

@section('js_additional')
    <script>
        $(document).ready(function(){
            $('.mh-now').html("Loading...");

            $.ajax({
                type: "GET",
                url: "https://api.nanopool.org/v1/eth/balance/0x9f3d97d8887eb429c0c500d4084f151796212e1e",
                success:function(msg) {
                    var datax = msg.data;

                    $('.mh-now').html(msg.data);
                    $('.machine-now').html(msg.status);
                    var now = parseFloat($('.mh-now').text());

                    setInterval(function(){
                        $('.mh-now').text(now += (0.02 / 3600));
                    }, 1000);
                },
                error:function(msg) {
                    $('.mh-now').html('fail' + msg.responseText);
                }
            });

            $.ajax({
                type: "GET",
                url: "https://api.nanopool.org/v1/eth/prices",
                success:function(msg) {
                    var datax = msg.data.price_usd;

                    $('.dollar-now').html(msg.data.price_usd);
                },
                error:function(msg) {
                    $('.dollar-now').html('fail' + msg.responseText);
                }
            });
        });
    </script>
@endsection

@section('content')
        <div class="content-container" style="max-width: none; width: 100%;">
            <h1 class="content-title" style="font-size: 20px;">
                YOUR BALANCE<br>
                <span class="mh-now" style="font-size: 45px;"></span><br>

                {{$machine->machine_id}} ({{$machine->mh}} MH)
            </h1>

            @if (Auth::check())
                <div class="machine-group">
                    {{-- <div class="machine-item" style="height: 300px !important;"> --}}
                    <div class="machine-item">
                        <div class="machine-list">
                            <table class="machine-table">
                                <tr>
                                    <th>
                                        Hour
                                    </th>
                                    <th>
                                        Machine Status
                                    </th>
                                    <th>
                                        Income
                                    </th>
                                    <th>
                                        Balance
                                    </th>
                                </tr>
                                @foreach($machinemininghistories as $machinemininghistory)
                                    <?php
                                        $lastmining = Machinemininghistory::where('machine_id', '=', Auth::user()->id)->where('id', '<', $machinemininghistory->id)->orderBy('id', 'desc')->first();
                                    ?>
                                    <tr>
                                        <td>
                                            {{date('H:i', strtotime($machinemininghistory->created_at))}}
                                        </td>
                                        <td>
                                            @if($machinemininghistory->balance <= $lastmining->balance)
                                                <span style="color: red;">
                                                    Not Active
                                                </span>
                                            @else
                                                <span style="color: green;">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$machinemininghistory->selisih}}
                                        </td>
                                        <td>
                                            {{$machinemininghistory->balance}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            @foreach($machinemininghistories as $machinemininghistory)
                                <?php
                                    $lastmining = Machinemininghistory::where('user_id', '=', Auth::user()->id)->where('id', '<', $machinemininghistory->id)->orderBy('id', 'desc')->first();
                                ?>
                                <table class="machine-table-mob">
                                    <tr>
                                        <td>
                                            Hour
                                        </td>
                                        <td>
                                            : {{date('H:i', strtotime($machinemininghistory->created_at))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Machine Status
                                        </td>
                                        <td>
                                            @if($machinemininghistory->balance <= $lastmining->balance)
                                                <span style="color: red;">
                                                    : Not Active
                                                </span>
                                            @else
                                                <span style="color: green;">
                                                    : Active
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Income
                                        </td>
                                        <td>
                                            : {{$usermininghistory->selisih}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Balance
                                        </td>
                                        <td>
                                            : {{$usermininghistory->balance}}
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
                
            @endif

            <a class="content-history" href="{{ url('dashboard/machine') }}">
                BACK
            </a>
        </div>

@endsection