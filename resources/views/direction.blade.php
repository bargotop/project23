<x-app-layout>
    <form action="{{ route('createFacultyWithDepartment') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 pt-10 font-bold">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <div class="relative shadow-sm" id="facultyInput">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="img/group.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название группы" id="faculty_name" name="faculty_name">
                </div>
                <div class="flex mt-3 ms-3">
                    <img src="img/person.svg">
                    <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="studentInput" placeholder="Ф. И. О. студента" name="department_name[]">
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
            <div class="my-3 p-4 border rounded-lg shadow">
                <div class="flex justify-between">
                    <div class="font-semibold text-gray-900 md:text-xl"></div>
                    <form method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                    <img class="cursor-pointer" src="img/delete.svg">
                </div>
                <div class="space-y-3 mt-3">
                    <div class="flex justify-between items-center">
                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('direction') }}'"></div>
                        <form method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                        <img class="cursor-pointer ps-2" src="img/delete.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#addStudentBtn').click(function() {
            $('#createBtn').before(`
                <div class="flex mt-3">
                    <div class="w-full flex ms-3">
                        <img src="img/person.svg">
                        <input class="w-full rounded-md ms-3 placeholder:text-gray-400" id="studentInput" placeholder="Ф. И. О. студента" name="department_name[]">
                    </div>
                    <img class="cursor-pointer px-2 py-auto deleteStudent" src="img/delete.svg">
                </div>
            `);
        });
        $(document).on('click', '.deleteStudent', function() {
            $(this).closest('.flex').remove();
        });

        function deleteFaculty(facultyId) {
            const form = document.getElementById('deleteFacultyForm' + facultyId);
            form.submit();
        }

        function deleteDepartment(departmentId) {
            const form = document.getElementById('deleteDepartmentForm' + departmentId);
            form.submit();
            ну я бы так не сказал да хуй его знает на самом то деле ахаха а еще
        }
    </script>
</x-app-layout>