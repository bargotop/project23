<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <form action="{{ route('departments.groups-with-students.create', ['departmentId' => $department->id]) }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 pt-10 font-bold">
            <h1>Направление: {{$department->name}}</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <div class="relative shadow-sm" id="facultyInput">
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
        </div>
    </form>
    <div class="max-w-7xl mx-auto sm:px-6 py-10 font-bold">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            @foreach ($department->groups as $group)
                <div class="my-3 p-4 border rounded-lg shadow">
                    <div class="flex justify-between">
                        <div class="font-semibold text-gray-900 md:text-xl">Группа {{ $group->id }}: {{ $group->name }}</div>
                        <img class="cursor-pointer deleteGroupBtn" data-group-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['groupId' => $group->id]) }}" src="/img/delete.svg">

                    </div>
                    <div class="space-y-3 mt-3">
                        @foreach ($group->students as $student)
                        <div class="flex justify-between items-center">
                            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow">{{ $student->full_name }}</div>
                            <img class="cursor-pointer ps-2 deleteStudentBtn" data-student-id="{{ $student->id }}" data-delete-url="{{ route('students.delete', ['studentId' => $student->id]) }}" src="/img/delete.svg">
                        </div>
                        @endforeach
                    </div>
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
                    <img class="cursor-pointer px-2 py-auto deleteStudent" src="/img/delete.svg">
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
</x-app-layout>