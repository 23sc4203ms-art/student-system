@extends('format.layout')

@section('title')
    Client Page
@endsection

@section('header')
    @parent
@endsection

@section('content')
    This is the Client Page For our client. <br>

    

    @for($a=1;$a<=10;$a++)
        {{$a}}<br>
    @endfor


    <br>
    <!-- If-Else Statement -->
    

    {{$grade}}<br><br>
    @if($grade >= 75 && $grade <= 100)
        Your Grade: {{ $grade }} is Passed.
    @elseif($grade < 75 && $grade >= 0) 
        Your Grade: {{ $grade }} is Failed.
    @else
        Your Grade: {{ $grade }} is Invalid.       
    @endif

    <br>
    <!-- Odd Or Even -->
    
    @if($grade % 2 == 0)
        {{$grade}} is Even.
    @else
        {{$grade}} is Odd.

    @endif

    <br>
     Foor Loop <br>


    @for($i=1;$i<=5;$i++)
        
        @for($j=1;$j<=$i;$j++)
            *
        @endfor
        <br>
    @endfor

    <br>
    While Loop 


    @php
        $i = 1;
    @endphp

    @while($i <= 10)
        {{$i}}<br>
        @php
            $i++;
        @endphp
        
    @endwhile


    <br>


    @foreach($clients as $client)
    <table row = "1" column = "3" border = "1" style = "border : 1px solid black; 
    border-collapse: collapse; 
    position: relative; 
    left: 50%; 
    transform: translateX(-50%);
    ">
        <tr>
            <th>Name</th>
            <th>Sex</th>
            <th>Address</th>
        </tr>
        <tr>
            <td>{{$client['name']}}</td>
            <td>{{$client['sex']}}</td>
            <td>{{$client['Address']}}</td>
        </tr>
    </table>
    @endforeach
    
    <br>
   
    @isset($clients)
        Client Storage is Set
    @endisset

    @empty($clients)
        Client Storage is Empty
    @endempty    
    

     @forelse($clients as $client)
    <table>
        <tr>
            <th>Name</th>
            <th>Sex</th>
            <th>Address</th>
        </tr>
        <tr>
            <td>{{$client['name']}}</td>
            <td>{{$client['sex']}}</td>
            <td>{{$client['Address']}}</td>
        </tr>
    </table>
    @empty
        <p>No Clients Found.</p>
    @endforelse 


    <br>
    
     
    @foreach($clients as $client)
        @if($loop->first)
                {{$client['name']}}
                {{$client['sex']}}
                {{$client['Address']}}
        <@elseif($loop->last)
                {{$client['name']}}
                {{$client['sex']}}
                {{$client['Address']}}  
        @endif
        {{$loop->count}}
    @endforeach
@endsection





@section('footer')
    @parent
@endsection
