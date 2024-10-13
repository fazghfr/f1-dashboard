@extends('layout.layout')

@section('content')
    <h1><?= htmlspecialchars($homeData['driver']->name) ?> Data</h1>

    <br>
    <br>
    <br>
    <h2>Drivers</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Origin</th>
                <th>Age</th>
                <th>Driver Number</th>
                <th>Points</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($homeData['driver']))
                <tr>
                    <td>{{ $homeData['driver']->name }}</td>
                    <td>{{ $homeData['driver']->origin }}</td>
                    <td>{{ $homeData['driver']->age }}</td>
                    <td>{{ $homeData['driver']->driver_number }}</td>
                    <td>{{ $homeData['driver']->points }}</td>
                    <td>{{ $homeData['driver']->role }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6">No Driver data available.</td>
                </tr>
            @endif

        </tbody>
@endsection
