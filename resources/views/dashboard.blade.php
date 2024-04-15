<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 py-10 font-bold">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <div class="relative shadow-sm" id="facultyInput">
                <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                    <img src="img/menu-book.svg">
                </div>
                <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название факультета">
            </div>
            <div class="relative mt-3 shadow-sm" id="directionInput">
                <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                    <img src="img/import-contacts.svg">
                </div>
                <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название направления">
            </div>
            <div class="flex justify-between text-white" id="createBtn">
                <button class="bg-blue-500 hover:bg-blue-700 active:bg-blue-900 py-2 px-4 rounded mt-3" id="addDirectionBtn">Добавить ещё направление</button>
                <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3">Создать факультет</button>
            </div>
            <h1>Faculties</h1>
        
                @foreach ($faculties as $faculty)
                    <h2>{{ $faculty->name }}</h2>
                    <ul>
                        @foreach ($faculty->departments as $department)
                            <li>{{ $department->name }}</li>
                        @endforeach
                    </ul>
                @endforeach
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
        </div>
    </div>

</x-app-layout>
