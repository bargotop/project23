<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <form id="createScheduleForm" action="{{ route('schedule.store') }}" method="POST">
            <input value="tuesday" name="day_of_week" hidden>
            @csrf
            <div class="bg-white overflow-hidden shadow sm:rounded mt-10 p-5 space-y-3">
                <div>Вторник</div>
                <div class="flex space-x-3">
                    <div class="w-1/2">
                        <select class="w-full text-[14px] rounded" id="group" name="group_id">
                            <option selected disabled>Выберите группу</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-red-500"></span>
                    </div>
                    <div class="w-1/2">
                        <select class="w-full text-[14px] rounded" id="subjects" name="subject_id">
                            <option selected disabled>Выберите предмет</option>
                        </select>
                        <span class="text-red-500"></span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="w-1/2 flex space-x-3">
                        <div class="w-1/2">
                            <label class="text-[14px]" for="start-time">Время начала предмета:</label>
                            <input class="w-full text-[14px] bg-gray-50 border-gray-300 rounded mt-1 focus:border-blue-500" type="time" id="start-time" name="start_time">
                            <span class="text-red-500"></span>
                        </div>
                        <div class="w-1/2">
                            <label class="text-[14px]" for="end-time">Время окончания предмета:</label>
                            <input class="w-full text-[14px] bg-gray-50 border-gray-300 rounded mt-1 focus:border-blue-500" type="time" id="end-time" name="end_time">
                            <span class="text-red-500"></span>
                        </div>
                    </div>
                    <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" type="submit">Создать предмет</button>
                </div>
            </div>
        </form>
        <div class="py-10">
            <div class="bg-white space-y-3 shadow p-5 sm:rounded">
                @foreach($schedule as $item)
                    <div class="flex items-center justify-between schedule">
                        <div class="w-full flex justify-between p-3 rounded bg-gray-50">
                            <div>
                                <div>{{ $item->group->name }}</div>
                                <div>{{ $item->subject->name }}</div>
                            </div>
                            @if($item->start_time and $item->end_time)
                                {{ $item->start_time }} - {{ $item->end_time }} <br>
                            @endif
                        </div>
                        <img class="cursor-pointer ms-2 deleteScheduleBtn" src="/img/delete.svg" data-modal-toggle="deleteSchedule" data-id="{{ $item->id }}" data-delete-url="{{ route('schedule.delete', ['id' => $item->id]) }}">
                    </div>
                @endforeach
            </div>
        </div>
        @include("pop-ups.deleteSchedule")
    </div>
    <script>
        $('#group').change(function() {
            const groupId = $(this).val();
            axios.get(`{{ route('groups') }}/${groupId}/subjects`)
            .then(function (response) {
                const subjects = response.data.data.subjects;
                $('#subjects').find('option:not(:first)').remove();
                subjects.forEach(subject => {
                    $('#subjects').append(new Option(subject.name, subject.id));
                });
            })
            .catch(function (error) {
                console.error('Ошибка при отправке данных:', error);
            });
        });
        $('#createScheduleForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        function deleteSchedule(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const schedule = btn.closest(".schedule");
                    schedule.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteScheduleBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteScheduleConfirmBtn').off('click')
            $('.deleteScheduleConfirmBtn').on('click', function() {
                deleteSchedule(btn, id, deleteUrl)
            });
        });
    </script>
</x-app-layout>