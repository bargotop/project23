<!DOCTYPE html>
<html>
<head>
    <title>Schedule List</title>
</head>
<body>
    <h1>Schedule List</h1>

    <table>
        <thead>
            <tr>
                <th>Group</th>
                <th>Subject</th>
                <th>Day of the Week</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->group->name }}</td>
                    <td>{{ $schedule->subject->name }}</td>
                    <td>{{ ucfirst($schedule->day_of_week) }}</td>
                    <td>{{ $schedule->start_time }}</td>
                    <td>{{ $schedule->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
