<div>
    <h3>Employee List for Company {{ $companyId }}</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Department</th>
                <th>Business Unit</th>
                <th>Supervisor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr wire:key="user-{{ $user->id }}">
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->position }}</td>
                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                    <td>{{ $user->business_unit->name ?? 'N/A' }}</td>
                    <td>{{ $user->supervisor->first_name }} {{ $user->supervisor->last_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->isEmpty())
        <p>No employees found for your role or department.</p>
    @endif
</div>
