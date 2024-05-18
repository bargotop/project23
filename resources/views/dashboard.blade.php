<x-app-layout>
    <form action="{{ route('createFacultyWithDepartment') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 pt-10 font-bold">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <div class="relative shadow-sm" id="facultyInput">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="img/menu-book.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название факультета" id="faculty_name" name="faculty_name">
                </div>
                <div class="flex mt-3 ms-3">
                    <img src="img/import-contacts.svg">
                    <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="directionInput" placeholder="Название направления" name="department_name[]">
                </div>
                <div class="flex justify-between text-white" id="createBtn">
                    <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addDirectionBtn" type="button">Добавить ещё направление</button>
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать факультет и направление</button>
                </div>
            </div>
        </div>
    </form>

    <div class="max-w-7xl mx-auto sm:px-6 py-10 font-bold">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            {{-- @include('faculty.form') --}}
            @foreach ($faculties as $faculty)
                <div class="my-3 p-4 border rounded-lg shadow">
                    <div class="flex justify-between">
                        <div class="font-semibold text-gray-900 md:text-xl">{{ $loop->iteration }}) {{ $faculty->name }}</div>
                        <form id="deleteFacultyForm{{ $faculty->id }}" action="{{ route('deleteFaculty', ['facultyId' => $faculty->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                        <img class="cursor-pointer" src="img/delete.svg" onclick="deleteFaculty({{ $faculty->id }})">
                    </div>
                    <div class="space-y-3">
                        @foreach ($faculty->departments as $department)
                        <div class="flex justify-between items-center">
                            <div class="p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='www.google.com'">{{ $department->name }}</div>
                            <form id="deleteDepartmentForm{{ $department->id }}" action="{{ route('deleteDepartment', ['departmentId' => $department->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                            <img class="cursor-pointer" src="img/delete.svg" onclick="deleteDepartment({{ $department->id }})">
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        $('#addDirectionBtn').click(function() {
            $('#createBtn').before(`
                <div class="flex">
                    <div class="w-full flex mt-3 ms-3">
                        <img src="img/import-contacts.svg">
                        <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="directionInput" placeholder="Название направления" name="department_name[]">
                    </div>
                    <img class="delete-direction cursor-pointer px-2 mt-3 py-auto" src="img/delete.svg">
                </div>
            `);
        });
        $(document).on('click', '.delete-direction', function() {
            $(this).closest('.flex').remove();
        });

        function deleteFaculty(facultyId) {
            const form = document.getElementById('deleteFacultyForm' + facultyId);
            form.submit();
        }

        function deleteDepartment(departmentId) {
            const form = document.getElementById('deleteDepartmentForm' + departmentId);
            form.submit();
        }
    </script>
</x-app-layout>
