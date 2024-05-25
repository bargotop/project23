<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <form id="createDepartmentForm" action="{{ route('departments.create', ['facultyId' => $faculty->id]) }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-10 p-5 space-y-3">
                <div>{{ $faculty->name }}</div>
                <div class="relative">
                    <div class="pointer-events-none absolute mt-2.5 flex items-center pl-3">
                        <img src="/img/menu-book.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название направления" id="department_name" name="department_name">
                    <span class="text-red-500" id="department_name_error"></span>
                </div>
                <div class="text-white float-end">
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" type="submit">Создать направление</button>
                </div>
            </div>
        </form>
        <div class="py-10">
            <div class="p-5 rounded-xl bg-white space-y-3">
                @foreach ($faculty->departments as $department)
                    <div class="flex items-center justify-between department">
                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('departments.show', ['id' => $department->id]) }}'">{{ $department->name }}</div>
                        <img class="cursor-pointer ms-2 deleteDepartmentBtn" src="/img/delete.svg" data-modal-toggle="deleteDepartment" data-id="{{ $department->id }}" data-delete-url="{{ route('departments.delete', ['id' => $department->id]) }}">
                    </div>
                @endforeach
            </div>
        </div>
        @include("pop-ups.deleteDepartment")
    </div>
    <script>
        function deleteDepartment(btn, id, deleteUrl) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const department = btn.closest(".department");
                    department.remove();
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
            $('.deleteDepartmentConfirmBtn').off('click')
            $('.deleteDepartmentConfirmBtn').on('click', function() {
                deleteDepartment(btn, id, deleteUrl)
            });
        });
        $('#createDepartmentForm').on('submit', function(event) {
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