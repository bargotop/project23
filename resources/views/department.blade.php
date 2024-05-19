<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <form action="{{ route('departments.groups-with-students.create', ['departmentId' => $department->id]) }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-10 p-5">
                <div>{{$department->name}}</div>
                <div class="relative shadow-sm mt-3" id="facultyInput">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="/img/group.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название группы" id="group_name" name="group_name">
                </div>
                <div class="flex mt-3 ms-3">
                    <img src="/img/person.svg">
                    <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="studentInput" placeholder="Ф. И. О. студента" name="student_name[]">
                </div>
                <div class="flex justify-between text-white" id="createBtn">
                    <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addStudentBtn" type="button">Добавить ещё студента</button>
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать группу</button>
                </div>
            </div>
        </form>
        <div class="pt-5 pb-10">
            @foreach ($department->groups as $group)
                <div class="flex items-start">
                    <div class="w-full mt-5" id="accordion-open" data-accordion="open">
                        <div class="flex items-center justify-between w-full p-5 text-gray-500 border-b rounded-t-xl bg-white cursor-pointer hover:bg-gray-200" data-accordion-target="#accordion{{ $group->id }}">
                            <div>{{ $group->name }}<span class="text-[10px]"> {{ $group->id }}</span></div>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </div>
                        <div id="accordion{{ $group->id }}" class="hidden">
                            <div class="p-5 rounded-b-xl bg-white space-y-3">
                                @foreach ($group->students as $student)
                                    <div class="flex items-center justify-between">
                                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50">{{ $student->full_name }}</div>
                                        <img class="cursor-pointer ms-2 deleteStudentBtn" data-student-id="{{ $student->id }}" data-delete-url="{{ route('students.delete', ['studentId' => $student->id]) }}" src="/img/delete.svg">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button class="text-white bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-8 ms-3" id="addStudentBtn" type="button">Войти</button>
                    <img class="cursor-pointer mt-10 mx-2 deleteGroupBtn" data-group-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['groupId' => $group->id]) }}" src="/img/delete.svg">
                </div>
            @endforeach
        </div>
    </div>
    <script>
        $('#addStudentBtn').click(function() {
            $('#createBtn').before(`
                <div class="flex mt-3">
                    <div class="w-full flex ms-3">
                        <img src="/img/person.svg">
                        <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="studentInput" placeholder="Ф. И. О. студента" name="student_name[]">
                    </div>
                    <img class="cursor-pointer mx-2 py-auto deleteStudent" src="/img/delete.svg">
                </div>
            `);
        });
        $(document).on('click', '.deleteStudent', function() {
            $(this).closest('.flex').remove();
        });

        $(document).ready(function() {
            $('.deleteGroupBtn').on('click', function() {
                const groupId = $(this).data('group-id');
                const deleteUrl = $(this).data('delete-url');
                // if (confirm('Are you sure you want to delete this group?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            location.reload(); // Перезагрузка страницы после успешного удаления
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                // }
            });

            $('.deleteStudentBtn').on('click', function() {
                const studentId = $(this).data('student-id');
                const deleteUrl = $(this).data('delete-url');
                // if (confirm('Are you sure you want to delete this Student?')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            location.reload(); // Перезагрузка страницы после успешного удаления
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                // }
            });
        });
    </script>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</x-app-layout>