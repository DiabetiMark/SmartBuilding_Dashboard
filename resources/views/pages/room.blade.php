@extends('layouts.default')

@section('content')
    <section class="section">
        <script>var roomId = '{{ $room }}';</script>
        <div id="app"><room-component></room-component></div>
    </section>
@stop