<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <h1>Attendance Journal for {{ $schedule->subject->name }} - {{ $schedule->group->name }}</h1>
        <form id="attendance-form" action="{{ route('attendances.store', $schedule->id) }}" method="POST">
        @csrf
        <input type="hidden" name="today" value="{{ $today }}">
        <div class="shadow mt-10 sm:rounded">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3">Студенты</th>
                        @foreach($dates as $date)
                            <th class="p-3 {{ $date['date'] === $today ? 'today' : 'disabled' }}">
                                {{ $date['date'] }}<br>{{ $date['start_time'] }} - {{ $date['end_time'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-b">
                            <th class="p-3">{{ $student->full_name }}</th>
                            @foreach($dates as $date)
                                <td class="p-3 {{ $date['date'] === $today ? 'today' : 'disabled' }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][student_id]" value="{{ $student->id }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][date]" value="{{ \Carbon\Carbon::createFromFormat('d.m.y', $date['date'])->format('Y-m-d') }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][is_present]" value="0">
                                    <input class="border-gray-300 rounded" type="checkbox" name="attendances[{{ $student->id }}][{{ $date['date'] }}][is_present]" value="1"
                                        @if(isset($attendances[$date['date']]) && $attendances[$date['date']]->where('student_id', $student->id)->first()?->is_present)
                                            checked
                                        @endif
                                        @if($date['date'] !== $today)
                                            disabled
                                        @endif
                                    >
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit">Save Attendance</button>
    </form>
    </div>
    <style>
        #div .air-datepicker {
          width: fit-content;
        }
        #div .air-datepicker-body--cells {
            grid-template-columns: repeat(7, 50px);
            grid-auto-rows: 50px;
        }

        .today {
            background-color: yellow;
        }
        .disabled {
            background-color: lightgray;
        }

    </style>
    <script>
        var datePicker = new AirDatepicker('#div', {
            onSelect({date, formattedDate, datepicker}) {
                console.log(date, formattedDate, datepicker);
                onSelectDate(date, formattedDate, datepicker);
            }
        });

        function updateSubjects(forDate, scheduleData) {
            let scheduleContainer = $('#schedule-container');
            scheduleContainer.empty();
            let convertedDate = forDate.split('.').reverse().join('-');

            // Получаем день недели
            let dayOfWeek = new Date(convertedDate).toLocaleDateString('ru-RU', { weekday: 'long' });
 
            let headerText = `${dayOfWeek}, ${forDate}`;
            if (scheduleData.length > 0) {
                headerText += ` (${scheduleData.length} предмета(ов))`;
            }
            let scheduleHeader = $('<div class="p-3">').text(headerText);
            scheduleContainer.prepend(scheduleHeader);

            // Если нет доступных занятий, добавляем сообщение об этом
            if (scheduleData.length === 0) {
                scheduleContainer.append('<div class="p-3">Нет доступных занятий</div>');
            } else {
                // Добавляем занятия из массива scheduleData

                $.each(scheduleData, function(index, item) {
                    const subjectName = item.subject.name;
                    const groupName = item.group.name;
                    const startTime = item.start_time;
                    const endTime = item.end_time;

                    const scheduleItemHTML = `
                        <div class="flex justify-between p-3 cursor-pointer hover:bg-gray-100 hover:shadow">
                            <div>
                                <div>${groupName}</div>
                                <div>${subjectName}</div>
                            </div>
                            <div>${startTime} - ${endTime}</div>
                        </div>
                    `;
                    scheduleContainer.append(scheduleItemHTML);
                });
            }
        }

        $('#attendance-form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        });
    </script>
</x-app-layout>