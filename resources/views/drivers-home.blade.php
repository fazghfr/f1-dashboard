@extends('layout.layout')

@section('content')
    <h1>F1 Driver Data List</h1>
    <br>

    <!-- Search Form -->
    <form method="GET" action="/search/driver" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Name or Origin..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Teams Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th >Name</th>
                <th >Origin</th>
                <th >Age</th>
                <th >Driver Number</th>
                <th >points</th>
                <th >Team</th>
                <th >Role</th>
                <th class="text-center">Delete</th>
                <th class="text-center">Edit</th>
                <th class="text-center">View</th>
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
                        <td><?= htmlspecialchars($driver->team_name) ?></td>
                        <td><?= htmlspecialchars($driver->role) ?></td>
                        <td>
                            <form method="POST" action="/drivers/<?= htmlspecialchars($driver->id) ?>" style="display:inline;">
                                @csrf
                                @method('DELETE') <!-- Use DELETE method -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this driver?')">
                                        Delete
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="/edit/drivers/<?= htmlspecialchars($driver->id) ?>" style="display:inline;">
                                @csrf
                                @method('GET')
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        Edit
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="/drivers/<?= htmlspecialchars($driver->id) ?>" style="display:inline;">
                                @csrf
                                @method('GET')
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        View
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No teams available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Button to Add a New Team -->
    <div class="d-flex justify-content-center mb-4">
        <a href="/form/driver" class="btn btn-success">Add New Driver</a>
    </div>
@endsection
