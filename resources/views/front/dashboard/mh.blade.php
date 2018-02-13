<?php
    use App\Models\Purchase;
    use App\Models\Usermininghistory;
    use App\Models\Avg;
    use App\Models\Setting;

    $setting = Setting::first();
    $avg = Avg::orderBy('id', 'desc')->first();
?>

@extends('front.template.master')

@section('title')
    CLOUD MH
@endsection

@section('head_additional')
    {!!HTML::style('css/front/dashboard.css')!!}

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();

            data.addColumn('number', '(Hours)');
            data.addColumn('number', ' ');

            data.addRows([
                @foreach($usermininghistories as $usermininghistory)
                    [{{date('H', strtotime($usermininghistory->created_at))}}, {{$usermininghistory->balance}}],
                @endforeach
            ]);

            var options = {
                chart: {
                    title: 'MINING PERFORMANCE'
                }
            };

            var chart = new google.charts.Line(document.getElementById('curve_chart'));

            chart.draw(data, google.charts.Line.convertOptions(options));
        }
    </script>
@endsection

@section('js_additional')
    <script>
        $(document).ready(function(){
            $('.mh-now').html("Loading...");

            @if($avg == null)
                var hashrate = "0";
            @else
                var hashrate = "{{$avg->average}}";
            @endif
            // alert(parseFloat(hashrate) / 3600);

            $.ajax({
                type: "GET",
                url: "{{ url('dashboard/get-avg') }}",
                success:function(msg) {
                    var getdata = JSON.parse(msg);
                    // alert(getdata);

                    $('.mh-now').html(getdata.balance);

                    var now = parseFloat($('.mh-now').text());

                    setInterval(function(){
                        $('.mh-now').text(now += (parseFloat(hashrate) / 3600));
                    }, 1000);
                },
                error:function(msg) {
                    $('.mh-now').html('fail' + msg.responseText);
                }
            });

            var cloudwalletid = $('.machine-title').attr('walletdata');
            // var getstatuslink = "https://api.nanopool.org/v1/eth/user/" + cloudwalletid;
            var getstatuslink = "https://api.nanopool.org/v1/eth/user/0x9f3d97d8887eb429c0c500d4084f151796212e1e";
            // alert(cloudwalletid);

            $.ajax({
                type: "GET",
                url: getstatuslink,
                success:function(msg) {
                    var isactive = msg.status;

                    if(isactive == true)
                    {
                        $('.machine-now').html("<span style='color: green;'>Active</span>");
                    }
                    else
                    {
                        $('.machine-now').html("<span style='color: red;'>Not Active</span>");
                    }
                    // var datax = msg.data.price_usd;

                    // $('.dollar-now').html(msg.data.price_usd);
                },
                error:function(msg) {
                    $('.machine-now').html('fail' + msg.responseText);
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
                Your Balance<br>
                <span class="mh-now" style="font-size: 45px;"></span>
            </h1>

            <div id="curve_chart"></div>
            @if (Auth::check())
                <div class="machine-group">
                    <div class="machine-item">
                        <h2 class="machine-title" walletdata="{{$setting->cloud_mining_walletid}}">
                            YOUR MH
                        </h2>
                        <div class="machine-list">
                            <?php
                                $total = 0;
                                $purchases = Purchase::where('user_id', '=', Auth::user()->id)->where('is_active', '=', true)->where('status', '=', 'Paid')->get();
                                foreach ($purchases as $purchase) {
                                    $total = $total + $purchase->mh;
                                }
                            ?>
                            <strong>{{$total}}</strong> MH
                        </div>
                    </div>
                    <div class="machine-item">
                        <h2 class="machine-title">
                            MINING STATUS
                        </h2>
                        <div class="machine-list" style="line-height: 24px;">
                            Machine Status : <strong class="machine-now"></strong><br>
                            USD Price : <strong class="dollar-now"></strong>
                            {{-- 0.89009987 --}}
                        </div>
                    </div>
                    <div class="machine-item">
                        <h2 class="machine-title">
                            MINING HISTORY TODAY
                        </h2>
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
                                @foreach($usermininghistories as $usermininghistory)
                                    <?php
                                        $lastmining = Usermininghistory::where('user_id', '=', Auth::user()->id)->where('id', '<', $usermininghistory->id)->orderBy('id', 'desc')->first();
                                    ?>
                                    <tr>
                                        <td>
                                            {{date('H:i', strtotime($usermininghistory->created_at))}}
                                        </td>
                                        <td>
                                            @if($usermininghistory->balance <= $lastmining->balance)
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
                                            {{$usermininghistory->selisih}}
                                        </td>
                                        <td>
                                            {{$usermininghistory->balance}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            @foreach($usermininghistories as $usermininghistory)
                                <?php
                                    $lastmining = Usermininghistory::where('user_id', '=', Auth::user()->id)->where('id', '<', $usermininghistory->id)->orderBy('id', 'desc')->first();
                                ?>
                                <table class="machine-table-mob" hourdata="{{date('H:i', strtotime($usermininghistory->created_at))}}" balancedata="{{$usermininghistory->balance}}">
                                    <tr>
                                        <td>
                                            Hour
                                        </td>
                                        <td>
                                            : {{date('H:i', strtotime($usermininghistory->created_at))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Machine Status
                                        </td>
                                        <td>
                                            @if($usermininghistory->balance <= $lastmining->balance)
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
        </div>
@endsection