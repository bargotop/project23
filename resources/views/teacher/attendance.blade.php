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
                        @foreach($dates->groupBy('date') as $date => $dateGroup)
                            <th class="text-[14px] p-3" colspan="{{ $dateGroup->count() }}">
                                {{ $date }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="p-3"></th>
                        @foreach($dates as $date)
                            <th class="text-[14px] p-3 {{ $date['date'] === $today ? 'today' : 'disabled' }}">
                                {{ $date['start_time'] }} - {{ $date['end_time'] }}
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
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['schedule_id'] }}][student_id]" value="{{ $student->id }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['schedule_id'] }}][date]" value="{{ \Carbon\Carbon::createFromFormat('d.m.y', $date['date'])->format('Y-m-d') }}">
                                    <input type="hidden" name="attendances[{{ $student->id }}][{{ $date['schedule_id'] }}][is_present]" value="0">
                                    <input class="w-5 h-5 border-gray-300 rounded attendance-checkbox" type="checkbox" name="attendances[{{ $student->id }}][{{ $date['schedule_id'] }}][is_present]" value="1"
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
        </form>
    </div>
    <style>
        .today {
            background-color: #F3F4F6;
        }
        .disabled {
            background-color: #D1D5DB;
        }
        th, td {
            border: 1px solid #E5E7EB;
        }
    </style>
    <script type="text/javascript">
        $('.attendance-checkbox').on('change', function() {
            // Собираем данные формы
            const $form = $('#attendance-form');
            const data = $form.serialize();

            // Отправка данных через AJAX
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Attendance updated successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    handleValidationErrors(xhr.responseJSON.errors);
                }
            });
        });
    </script>
</x-app-layout>