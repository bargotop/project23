<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        {{-- <form id="createSubjectForm" action="{{ route('subjects.create', ['groupId' => $group->id]) }}" method="POST"> --}}
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5 space-y-3">
                <div>Понедельник</div>
                <div class="flex space-x-3">
                    <select class="w-1/2 sm:rounded-lg" id="group">
                        <option selected disabled>Выберите группу</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    <select class="w-1/2 sm:rounded-lg" id="subjects">
                        <option selected disabled>Выберите предмет</option>
                    </select>
                </div>
                <div class="float-end">
                    <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" type="submit">Создать предмет</button>
                </div>
            </div>
        {{-- </form> --}}
        <div class="py-10">
            <div class="p-5 rounded-xl bg-white space-y-3">
                <ul>
                    @foreach($schedule as $item)
                        <li>
                            Group: {{ $item->group->name }} <br>
                            Subject: {{ $item->subject->name }} <br>
                            @if($item->start_time)
                                Start Time: {{ $item->start_time }} <br>
                            @endif
                            @if($item->end_time)
                                End Time: {{ $item->end_time }} <br>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @include("pop-ups.deleteSubject")
    </div>
    <script>
        $('#group').change(function() {
            const groupId = $(this).val();
            axios.get(`/groups/${groupId}/subjects`)
            .then(function (response) {
                const subjects = response.data.data.subjects; // Предположим, что это массив объектов, например [{ id: 1, name: 'Math' }, { id: 2, name: 'Science' }]
            
                // Очистка всех опций кроме первой
                $('#subjects').find('option:not(:first)').remove();
                
                // Дополнение новыми опциями
                subjects.forEach(subject => {
                    $('#subjects').append(new Option(subject.name, subject.id));
                });
            })
            .catch(function (error) {
                console.error('Ошибка при отправке данных:', error);
            });
        });
    </script>
</x-app-layout>