@extends('layout.layout')

@section('content')
    <div class="container d-flex justify-content-center p-5 bg-white"
        style="margin-top: 4rem; width:60%; border-radius: 25px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">

        <div class="form-frame" style="width: 60%;">
            <h1 class="text-center">Input Driver Data</h1>

            <form method="POST" action="/drivers/">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" >
                </div>

                <div class="form-group">
                    <label for="driver_number">Driver Number</label>
                    <input type="number" class="form-control" id="driver_number" name="driver_number" >
                </div>

                <div class="form-group">
                    <label for="origin">Origin</label>
                    <input type="text" class="form-control" id="origin" name="origin" >
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age" >
                </div>

                <div class="form-group">
                    <label for="points">Points</label>
                    <input type="text" class="form-control" id="points" name="points" >
                </div>

                <div class="form-group">
                    <label for="racing_team_id">Racing Team</label>
                    <select class="form-control" id="racing_team_id" name="racing_team_id" >
                        <option value="" disabled selected>Select a team</option>
                        <?php if (isset($homeData['teams']) && is_array($homeData['teams'])): ?>
                            <?php foreach ($homeData['teams'] as $index => $teamName): ?>
                                <option value="<?= $index ?>">
                                    <?= htmlspecialchars($teamName) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>



                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" class="form-control" id="role" name="role" >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add</button>
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
