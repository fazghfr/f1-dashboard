@extends('layout.layout')

@section('content')
    <h1><?= htmlspecialchars($homeData['team']->name) ?> Data</h1>
    <br>
    <br>
    <br>

    <h2>Team Details</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th >Team Name</th>
                <th >Origin</th>
                <th >Livery Color</th>
                <th >Team Chief</th>
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($homeData['team']->name) ?></td>
                <td><?= htmlspecialchars($homeData['team']->origin) ?></td>
                <td><?= htmlspecialchars($homeData['team']->livery_color) ?></td>
                <td><?= htmlspecialchars($homeData['team']->team_chief) ?></td>
                <td>
                    <form method="POST" action="/teams/<?= htmlspecialchars($homeData['team']->id) ?>" style="display:inline;">
                        @csrf
                        @method('DELETE') <!-- Use DELETE method -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this team? this will delete the drivers data too')">
                                Delete
                            </button>
                        </div>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>

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
            <?php if (isset($homeData['drivers']) && is_array($homeData['drivers'])): ?>
                <?php foreach ($homeData['drivers'] as $driver): ?>
                    <tr>
                        <td><?= htmlspecialchars($driver->name) ?></td>
                        <td><?= htmlspecialchars($driver->origin) ?></td>
                        <td><?= htmlspecialchars($driver->age) ?></td>
                        <td><?= htmlspecialchars($driver->driver_number) ?></td>
                        <td><?= htmlspecialchars($driver->points) ?></td>
                        <td><?= htmlspecialchars($driver->role) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No Drivers for this team.</td>
                </tr>
            <?php endif; ?>

        </tbody>
@endsection
