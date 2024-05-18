<x-app-layout>
    <form action="{{ route('createFaculty') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 pt-10 font-bold">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                <div class="relative shadow-sm" id="facultyInput">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="img/menu-book.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название факультета" id="name" name="name">
                </div>
                <div class="relative mt-3 shadow-sm" id="directionInput">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="img/import-contacts.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название направления">
                </div>
                <div class="flex justify-between text-white" id="createBtn">
                    <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addDirectionBtn" type="button">Добавить ещё направление</button>
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать факультет</button>
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
                        <div class="font-semibold text-gray-900 md:text-xl">{{ $faculty->name }}</div>
                        <img class="delete-direction cursor-pointer px-2 mt-3 py-auto" src="img/delete.svg">
                    </div>
                    <div class="space-y-3">
                        @foreach ($faculty->departments as $department)
                            <div class="p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='www.google.com'">{{ $department->name }}</div>
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
                    <div class="relative shadow-sm w-full mt-3">
                        <div class="pointer-events-none absolute inset-y-0 flex items-center left-3">
                            <img src="img/import-contacts.svg">
                        </div>
                        <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название направления">
                    </div>
                    <img class="delete-direction cursor-pointer px-2 mt-3 py-auto" src="img/delete.svg">
                </div>
            `);
        });
        $(document).on('click', '.delete-direction', function() {
            $(this).closest('.flex').remove();
        });
    </script>
</x-app-layout>
