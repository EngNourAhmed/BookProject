<table class="table table-responsive table-borderless bg-white shadow-sm users-table">
    <thead class="text-center">
        <tr>
            <th>اسم المستخدم</th>
            <th>البريد الإلكتروني</th>
            <th>حالة المستخدم</th>
            <th>الدور</th>
            <th>تاريخ الإنشاء</th>
            <th>إدارة الحالة</th>
            <th>تغيير الدور</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
        <tr class="text-center">
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>

            <td>
                @if($user->status == 'active')
                <span class="badge bg-success">نشط</span>
                @elseif($user->status == 'suspended')
                <span class="badge bg-warning">موقوف مؤقتًا</span>
                @elseif($user->status == 'banned')
                <span class="badge bg-danger">محظور</span>
                @endif
            </td>

            <td>{{ $user->role }}</td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>

            <td> ... إدارة الحالة ... </td>
            <td> ... تغيير الدور ... </td>

        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">لا يوجد مستخدمين</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3 text-center">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>