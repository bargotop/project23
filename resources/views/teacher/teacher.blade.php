<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <div class="bg-white overflow-hidden shadow sm:rounded mt-10 p-5 space-y-3">
            <div class="flex">
                <div id="div"></div>
                <div class="w-full text-[14px] bg-gray-50" id="schedule-container">
                    {{-- тут будет список из предметов --}}
                </div>
            </div>
            <div class="text-white float-end">
                <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" onclick="location='{{ route('schedule.index') }}'">Изменить расписание</button>
            </div>
        </div>
    </div>
    <style>
        #div .air-datepicker {
          width: fit-content;
        }
        #div .air-datepicker-body--cells {
            grid-template-columns: repeat(7, 50px);
            grid-auto-rows: 50px;
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
                        <div class="flex justify-between p-3 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('attendances') }}/${item.id}'">
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

        function onSelectDate(date, formattedDate, datepicker) {
            $.ajax({
                url: "{{ route('schedule.subjects.by-date') }}",
                method: 'POST',
                data: {
                    date: formattedDate
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    updateSubjects(formattedDate, response.data)
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</x-app-layout>