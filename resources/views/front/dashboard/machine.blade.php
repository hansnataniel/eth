<?php
    use App\Models\Purchase;
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
            <h1 class="content-title" style="font-size: 30px;">
                DEDICATED MACHINE MINING
            </h1>

            @if (Auth::check())
                <div class="machine-group">
                    <div class="machine-item" style="height: 300px !important;">
                        <div class="machine-list">
                            <table class="machine-table">
                                <tr>
                                    <th>
                                        Machine ID
                                    </th>
                                    <th>
                                        Machine Status
                                    </th>
                                    <th>
                                        MH
                                    </th>
                                    <th>
                                        Balance
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                                @foreach($machines as $machine)
                                    <tr>
                                        <td>
                                            {{$machine->machine_id}}
                                        </td>
                                        <td>
                                            <span style="color: green;">
                                                Active
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="font-size: 16px;">{{$machine->mh}}</strong>
                                        </td>
                                        <td>
                                            <strong style="font-size: 16px;">0.09808999</strong>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                $machineids = str_replace('/', '-', $machine->machine_id);
                                            ?>

                                            <a href="{{ url('dashboard/machine/detail/' . $machineids) }}" class="detail-button">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            @foreach($machines as $machine)
                                <table class="machine-table-mob">
                                    <tr>
                                        <td>
                                            Machine ID
                                        </td>
                                        <td>
                                            : {{$machine->machine_id}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Machine Status
                                        </td>
                                        <td>
                                            <span style="color: green;">
                                                : Active
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            MH
                                        </td>
                                        <td>
                                            : {{$machine->mh}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Balance
                                        </td>
                                        <td>
                                            : 0.09808999
                                        </td>
                                    </tr>
                                    <tr>
                                        <?php
                                            $machineids = str_replace('/', '-', $machine->machine_id);
                                        ?>

                                        <td colspan="2">
                                            <a href="{{ url('dashboard/machine/detail/' . $machineids) }}" class="detail-button" style="width: calc(100% + 20px); left: -10px; bottom: -5px; border: 0px; border-top: 1px solid #222;">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
                
            @endif
        </div>
@endsection