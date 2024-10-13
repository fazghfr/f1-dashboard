@extends('layout.layout')

@section('content')
    <h1>F1 Team Data List</h1>
    <br>

    <!-- Search Form -->
    <form method="GET" action="/search/team" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Team Name or Origin..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Teams Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th >Team Name</th>
                <th >Origin</th>
                <th >Livery Color</th>
                <th >Team Chief</th>
                <th class="text-center">Delete</th>
                <th class="text-center">Edit</th>
                <th class="text-center">View</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($homeData['teams']) && is_array($homeData['teams'])): ?>
                <?php foreach ($homeData['teams'] as $team): ?>
                    <tr>
                        <td><?= htmlspecialchars($team->name) ?></td>
                        <td><?= htmlspecialchars($team->origin) ?></td>
                        <td><?= htmlspecialchars($team->livery_color) ?></td>
                        <td><?= htmlspecialchars($team->team_chief) ?></td>
                        <td>
                            <form method="POST" action="/teams/<?= htmlspecialchars($team->id) ?>" style="display:inline;">
                                @csrf
                                @method('DELETE') <!-- Use DELETE method -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this team?')">
                                        Delete
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="/edit/teams/<?= htmlspecialchars($team->id) ?>" style="display:inline;">
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
                            <form method="POST" action="/view/teams/<?= htmlspecialchars($team->id) ?>" style="display:inline;">
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
        <a href="/form/team" class="btn btn-success">Add New Team</a>
    </div>
@endsection
