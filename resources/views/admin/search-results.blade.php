@extends('layouts.master')

@section('content')
    <div class="p-4">
        <h2>Search Results</h2>

        @if ($employees->isEmpty())
            <p>No results found.</p>
        @else
            <ul>
                @foreach ($employees as $employee)
                    <li>{{ $employee->name }}</li>
                @endforeach
            </ul>
    @endif
    </div>

@endsection
