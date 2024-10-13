@extends('layout.layout')

@section('content')
    <div class="container d-flex justify-content-center p-5 bg-white"
        style="margin-top: 4rem; width:60%; border-radius: 25px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">

        <div class="form-frame" style="width: 60%;">
            <h1 class="text-center">Update <?= htmlspecialchars($homeData['team_name']) ?> Data</h1>

            <form method="POST" action="/teams/<?= htmlspecialchars($homeData['team_id']) ?>">
                @csrf

                <div class="form-group">
                    <label for="name">Team Name</label>
                    <input type="text" class="form-control" id="name" name="name" autofocus>
                </div>

                <div class="form-group">
                    <label for="origin">Origin</label>
                    <input type="text" class="form-control" id="origin" name="origin">
                </div>

                <div class="form-group">
                    <label for="livery_color">Livery Color</label>
                    <input type="text" class="form-control" id="livery_color" name="livery_color">
                </div>

                <div class="form-group">
                    <label for="team_chief">Team Chief</label>
                    <input type="text" class="form-control" id="team_chief" name="team_chief">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8f7f3;
        }
    </style>
@endsection
