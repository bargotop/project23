<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <div class="mt-5">{{$group->name}}</div>
        <div class="flex space-x-3">
            <div class="w-1/2">
                <form id="createSubjectForm" action="{{ route('subjects.create', ['groupId' => $group->id]) }}" method="POST">
                    @csrf
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5">
                        <div class="relative shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                                <img src="/img/import-contacts.svg">
                            </div>
                            <input class="w-full rounded-md pl-12 placeholder:text-gray-400" id="subject_name" placeholder="Название предмета" name="subject_name">
                            <span class="text-red-500" id="subject_name_error"></span>
                        </div>
                        <div class="float-right" id="createBtn">
                            <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать предмет</button>
                        </div>
                    </div>
                </form>
                <div class="pt-5 pb-10">
                    @foreach ($group->subjects as $subject)
                        <div class="flex justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5 subject">
                            <div>{{ $subject->name }}</div>
                            <img class="cursor-pointer ms-2 deleteSubjectBtn" src="/img/delete.svg" data-modal-toggle="deleteSubject" data-id="{{ $subject->id }}" data-delete-url="{{ route('subjects.delete', ['id' => $subject->id]) }}">
                        </div>
                    @endforeach
                </div>
                @include("pop-ups.deleteSubject")
            </div>
            <div class="w-1/2">
                <form id="createStudentForm" action="{{ route('students.create', ['groupId' => $group->id]) }}" method="POST">
                    @csrf
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5">
                        <div class="relative shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                                <img src="/img/person.svg">
                            </div>
                            <input class="w-full rounded-md pl-12 placeholder:text-gray-400" id="student_name" placeholder="Ф. И. О. студента" name="student_name">
                            <span class="text-red-500" id="student_name_error"></span>
                        </div>
                        <div class="float-right" id="createBtn">
                            <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать студента</button>
                        </div>
                    </div>
                </form>
                <div class="pt-5 pb-10">
                    @foreach ($group->students as $student)
                        <div class="flex justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5 student">
                            <div>{{ $student->full_name }}</div>
                            <img class="cursor-pointer ms-2 deleteStudentBtn" src="/img/delete.svg" data-modal-toggle="deleteStudent" data-id="{{ $student->id }}" data-delete-url="{{ route('students.delete', ['id' => $student->id]) }}">
                        </div>
                    @endforeach
                </div>
                @include("pop-ups.deleteStudent")
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.deleteStudent', function() {
            $(this).closest('.flex').remove();
        });
        function deleteSubject(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const subject = btn.closest(".subject");
                    subject.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        function deleteStudent(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const subject = btn.closest(".student");
                    subject.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteSubjectBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteSubjectConfirmBtn').off('click')
            $('.deleteSubjectConfirmBtn').on('click', function() {
                deleteSubject(btn, id, deleteUrl)
            });
        });

        $('.deleteStudentBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteStudentConfirmBtn').off('click')
            $('.deleteStudentConfirmBtn').on('click', function() {
                deleteStudent(btn, id, deleteUrl)
            });
        });

        $('#createSubjectForm').on('submit', function(event) {
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

        $('#createStudentForm').on('submit', function(event) {
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