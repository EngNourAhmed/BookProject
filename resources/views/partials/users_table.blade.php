<table class="table table-responsive table-borderless bg-white shadow-sm users-table">
    <thead class="text-center">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>User Status</th>
            <th>Role</th>
            <th>Creation Date</th>
            <th>Manage Status</th>
            <th>Change Role</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
        <tr class="text-center">
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>

            <td>
                @if($user->status == 'active')
                <span class="badge bg-success">Active</span>
                @elseif($user->status == 'suspended')
                <span class="badge bg-warning">Suspended</span>
                @elseif($user->status == 'banned')
                <span class="badge bg-danger">Banned</span>
                @endif
            </td>

            <td>{{ $user->role }}</td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>

            <td> ... إدارة الحالة ... </td>
            <td> ... تغيير الدور ... </td>

        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No users found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3 text-center">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>