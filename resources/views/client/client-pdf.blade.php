<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<h1>Client List</h1>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Adresse</th>
        <th>Sexe</th>
        <th>Telephone</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->nom }}</td>
            <td>{{ $client->adresse }}</td>
            <td>{{ $client->sexe }}</td>
            <td>{{ $client->telephone }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5">No Client Found!</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
