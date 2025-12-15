<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Permissions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($query as $data)
            <tr>
                <td>{{ strtoupper($data->last_name) }}, {{ strtoupper($data->first_name) }}
                    {{ strtoupper($data->middle_name) }} {{ strtoupper($data->name_extension) }}
                </td>
                <td>{{ $data->username }}</td>
                <td>{{ $data->email }}</td>
                <td>
                    @foreach ($data->userDetail->roles ?? [] as $role)
                        {{ $role->role_name }} @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($data->userDetail?->roles ?? [] as $role)
                        @foreach ($role->permissions ?? [] as $permission)
                            {{ $permission->name }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endforeach
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
