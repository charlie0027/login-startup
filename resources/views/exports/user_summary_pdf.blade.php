<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>User Summary</title>

    <style>
        @page {
            margin-top: 0px;
            margin-bottom: 0px;
            margin-left: 1in;
            margin-right: 1in;
        }

        .header {
            position: fixed;
            top: 0px;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            text-align: center;
        }

        .header h2 {
            margin-bottom: 0%;
            padding-bottom: 0%;
            margin-top: 0%;
            padding-top: 0%;
            text-transform: uppercase;
        }

        .header h4 {
            margin-bottom: 0%;
            padding-bottom: 0%;
            margin-top: 0%;
            padding-top: 0%;
        }

        .report-title {
            text-transform: uppercase;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th {
            font-size: small;

        }

        td {
            padding-left: 5px;
            padding-right: 3px;
            font-size: xx-small;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            text-align: center;
        }

        body {
            margin-top: 2cm;
            margin-bottom: 2cm;
        }

        .page-break-after {
            page-break-after: always;
        }

        .page-break-inside {
            page-break-inside: avoid;
        }

        .page-break-before {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <header class="header">
        <h2>This is my App Project</h2>
        <h4>Generate Report System</h4>
        <h4>charlielomiwesd@gmail.com | 09777759113</h4>
    </header>
    <main class="section">
        {{-- <div class="page-break-before"></div> --}}
        <div>
            <div class="report-title">
                <p>User Summary Report</p>
            </div>
            <ul>
                <li>Below are the list of Users together with its respective Roles and Permissions</li>
            </ul>

            <table class="table-user-summary">
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
        <div class="container">
            <div class="report-title">
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
</body>


</html>
