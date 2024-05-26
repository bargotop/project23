<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <div class="mt-10">{{ $schedule->subject->name }} - {{ $schedule->group->name }}</div>
        <form id="attendance-form" action="{{ route('attendances.store', $schedule->id) }}" method="POST">
            @csrf
            <input type="hidden" name="today" value="{{ $today }}">
            <table class="w-full text-left shadow mt-3 sm:rounded">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3">Студенты</th>
                        @foreach($dates as $date)
                            <th class="text-[14px] p-3 {{ $date['date'] === $today ? 'today' : 'disabled' }}">
                                {{ $date['date'] }}<br>{{ $date['start_time'] }} - {{ $date['end_time'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr class="border-b text-[14px]">
                            <th class="p-2">{{ $student->full_name }}</th>
                            @foreach($dates as $date)
                                <td class="p-2 {{ $date['date'] === $today ? 'today' : 'disabled' }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][student_id]" value="{{ $student->id }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][date]" value="{{ \Carbon\Carbon::createFromFormat('d.m.y', $date['date'])->format('Y-m-d') }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['date'] }}][is_present]" value="0">
                                    <input class="w-5 h-5 border-gray-300 rounded" type="checkbox" name="attendances[{{ $student->id }}][{{ $date['date'] }}][is_present]" value="1"
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
            <div class="text-white float-end">
                <button class="bg-green-500 rounded mt-3 py-2 px-4 hover:bg-green-700 active:bg-green-900" type="submit">Сохранить</button>
            </div>
        </form>
    </div>
    <style>
        .today {
            background-color: #F3F4F6;
        }
        .disabled {
            background-color: #E5E7EB;
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