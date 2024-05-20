<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <form id="createGroupForm" action="{{ route('groups.create', ['departmentId' => $department->id]) }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-10 p-5">
                <div>{{$department->name}}</div>
                <div class="relative shadow-sm mt-3">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="/img/group.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название группы" id="group_name" name="group_name">
                    <span class="text-red-500" id="group_name_error"></span>
                </div>
                <div class="flex mt-3 ms-3">
                    <img src="/img/person.svg">
                    <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="studentInput" placeholder="Ф. И. О. студента" name="student_name[]">
                    <span class="text-red-500" id="student_name"></span>
                </div>
                <div class="flex justify-between text-white" id="createBtn">
                    <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addStudentBtn" type="button">Добавить ещё студента</button>
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать группу</button>
                </div>
            </div>
        </form>
        <div class="pt-5 pb-10">
            @foreach ($department->groups as $group)
                <div class="flex items-start group">
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
                                    <div class="flex items-center justify-between student">
                                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50">{{ $student->full_name }}</div>
                                        <img class="cursor-pointer ms-2 deleteStudentBtn" src="/img/delete.svg" data-modal-toggle="deleteStudent" data-student-id="{{ $student->id }}" data-delete-url="{{ route('students.delete', ['studentId' => $student->id]) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button class="text-white bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-8 ms-3" onclick="location='{{ route('groups.show', ['groupId' => $group->id]) }}'">Войти</button>
                    <img class="cursor-pointer mt-10 mx-2 deleteGroupBtn" src="/img/delete.svg" data-modal-toggle="deleteGroup" data-group-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['groupId' => $group->id]) }}">
                </div>
            @endforeach
        </div>
        @include("pop-ups.deleteGroup")
        @include("pop-ups.deleteStudent")
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

        function handleValidationErrors(errors) {
            // Clear all previous errors
            $('input').removeClass('border-red-500');
            $('.text-red-500').text('');

            // Set new errors
            $.each(errors, function(field, messages) {
                var input = $('[name="' + field + '"]');
                input.addClass('border-red-500');
                input.next('.text-red-500').text(messages[0]);
            });
        }

        function deleteGroup(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const group = btn.closest(".group");
                    group.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteGroupBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteGroupConfirmBtn').off('click')
            $('.deleteGroupConfirmBtn').on('click', function() {
                deleteGroup(btn, id, deleteUrl)
            });
        });
        function deleteStudent(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const student = btn.closest(".student");
                    const group = student.closest(".group");
                    student.remove();
                    if (group.find(".student").length === 0) {
                        group.remove();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteStudentBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteStudentConfirmBtn').off('click');
            $('.deleteStudentConfirmBtn').on('click', function() {
                deleteStudent(btn, id, deleteUrl)
            });
        });

        $('#createGroupForm').on('submit', function(event) {
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
    </script>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</x-app-layout>