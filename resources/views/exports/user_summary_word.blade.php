<header style="text-align:center; font-weight: bold;">
    <h2>This is my App Project</h2>
    <h4>Generate Report System</h4>
    <h4>charlielomiwesd@gmail.com | 09777759113</h4>
</header>
<main class="section">
    <div>
        <div style="text-align: center;font-weight: bold;">
            <p>User Summary Report</p>
        </div>
        <ul>
            <li>Below are the list of Users together with its respective Roles and Permissions</li>
        </ul>

        <table border="1" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:30%">Name</th>
                    <th style="width:15%">Username</th>
                    <th style="width:25%">Email</th>
                    <th style="width:20%">Roles</th>
                    <th style="width:30%">Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($query as $data)
                    <tr>
                        <td style="">{{ strtoupper($data->last_name) }}, {{ strtoupper($data->first_name) }}
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

    </div>
    <div>
        <div style="text-align:center; font-weight: bold;">
            <p>Gender Per User</p>
        </div>
        <div class="pie-chart" style="text-align:center">
            <img src="{{ $chartBase64 }}" alt="" />
        </div>
    </div>

</main>
<footer>
    <h4>Sample Footer (C) 2025 Production</h4>
</footer>
