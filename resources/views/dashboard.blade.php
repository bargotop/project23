<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <form action="{{ route('faculties.with-department.create') }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-10 p-5">
                <div class="relative shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="/img/menu-book.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название факультета" id="faculty_name" name="faculty_name">
                </div>
                <div class="flex mt-3 ms-3">
                    <img src="/img/import-contacts.svg">
                    <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="directionInput" placeholder="Название направления" name="department_name[]">
                </div>
                <div class="flex justify-between text-white" id="createBtn">
                    <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addDirectionBtn" type="button">Добавить ещё направление</button>
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать факультет</button>
                </div>
            </div>
        </form>
        <div class="pt-5 pb-10">
            @foreach ($faculties as $faculty)
                <div class="flex items-start faculty">
                    <div class="w-full mt-5" id="accordion-open" data-accordion="open">
                        <div class="flex items-center justify-between w-full p-5 text-gray-500 border-b rounded-t-xl bg-white cursor-pointer hover:bg-gray-200" data-accordion-target="#accordion{{ $faculty->id }}">
                            <div>{{ $faculty->name }}</div>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </div>
                        <div id="accordion{{ $faculty->id }}" class="hidden">
                            <div class="p-5 rounded-b-xl bg-white space-y-3">
                                @foreach ($faculty->departments as $department)
                                    <div class="flex items-center justify-between department">
                                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('departments.groups-and-students.show', ['departmentId' => $department->id]) }}'">{{ $department->name }}</div>
                                        <img class="cursor-pointer ms-2 deleteDepartmentBtn" src="/img/delete.svg" data-modal-toggle="deleteDepartment" data-id="{{ $department->id }}" data-delete-url="{{ route('departments.delete', ['departmentId' => $department->id]) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <img class="cursor-pointer mt-10 mx-2 deleteFacultyBtn" src="/img/delete.svg" data-modal-toggle="deleteFaculty" data-id="{{ $faculty->id }}" data-delete-url="{{ route('faculties.delete', ['facultyId' => $faculty->id]) }}">
                </div>
            @endforeach
        </div>
        @include("pop-ups.deleteFaculty")
        @include("pop-ups.deleteDepartment")
    </div>
    <script>
        $('#addDirectionBtn').click(function() {
            $('#createBtn').before(`
                <div class="flex mt-3">
                    <div class="w-full flex ms-3">
                        <img src="/img/import-contacts.svg">
                        <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="directionInput" placeholder="Название направления" name="department_name[]">
                    </div>
                    <img class="cursor-pointer mx-2 py-auto deleteDirection" src="/img/delete.svg">
                </div>
            `);
        });
        $(document).on('click', '.deleteDirection', function() {
            $(this).closest('.flex').remove();
        });
        function deleteFaculty(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const faculty = btn.closest(".faculty");
                    faculty.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteFacultyBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteFacultyConfirmBtn').off('click')
            $('.deleteFacultyConfirmBtn').on('click', function() {
                deleteFaculty(btn, id, deleteUrl)
            });
        });
        function deleteDepartment(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const department = btn.closest(".department");
                    const faculty = department.closest(".faculty");
                    department.remove();
                    if (faculty.find(".department").length === 0) {
                        faculty.remove();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        };
        $('.deleteDepartmentBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteDepartmentConfirmBtn').off('click');
            $('.deleteDepartmentConfirmBtn').on('click', function() {
                deleteDepartment(btn, id, deleteUrl)
            });
        });
    </script>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</x-app-layout>
