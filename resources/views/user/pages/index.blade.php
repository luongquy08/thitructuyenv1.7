@extends('user.master')
@section('title', 'Trang chủ')
@section('content')
<div class="container">
    <div class="row">
        <ul class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span> Trang chủ</a></li>
        </ul>
    </div>
</div>
<div id="section">
    <div class="container">
        <div class="row">
            @foreach($subjectsData as $subjectItem)
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">{!! $subjectItem['name'] !!}</h3>
                </div>
                <div class="panel-body">
                    @foreach($testsData as $testItem)
                        @if($testItem['subject_id'] == $subjectItem['id'])
                    <div class="col-md-6">
                        <table class="table table-striped">
                            <tr>
                                <td class="col-md-6"><a href="{!! route('getQuestion', ['id'=>$testItem['id']]) !!}"><span class="glyphicon glyphicon-list-alt"> {!! $testItem['name'] !!}</span></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection