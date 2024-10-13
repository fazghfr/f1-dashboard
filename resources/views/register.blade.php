@extends('layout.layout')

@section('content')
    <div class="container d-flex justify-content-center p-5 bg-white"
        style="margin-top: 4rem; width:60%; border-radius: 25px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">

        <div class="form-frame" style="width: 60%;">
            <h1 class="text-center">Register</h1>
            <form method="POST" action="/register">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="name">name</label>
                    <input type="name" class="form-control" id="name" name="name" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8f7f3; /* Specify your desired background color */
        }
    </style>
@endsection
