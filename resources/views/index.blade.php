@extends('layout')

@section('content')

    <div class="container">
        <div class="card-deck mb-3 text-center">

            @foreach($staticObject as $object => $value)
                <div class="card mb-4 box-shadow">
                    <div class="card-header" style="background-color: #1b4b72; color: white">
                        <h4 class="my-0 font-weight-normal">{{$object}}</h4>
                    </div>
                    <div class="card-body">
                        @for($i=0; $i< $value['number']; $i++)
                            <div class="card-header"
                                 style="background-color: @if(session()->get("$object.$i") == 0) green @else red @endif; color: white">
                                {{$object . "  ".($i+1)}}
                            </div>
                            <p>
                            <div>
                                <div>
                                    @if(isset($checkForLife["$object.$i"]))
                                        @foreach($checkForLife["$object.$i"] as $feedAt)
                                            <div>
                                                feed @ {{$feedAt + 1}}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            </p>
                        @endfor
                    </div>
                </div>


            @endforeach

        </div>

@endsection