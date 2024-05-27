<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <form id="createFacultyForm" action="{{ route('faculties.create') }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow sm:rounded mt-10 p-5 space-y-3">
                <div class="relative">
                    <div class="pointer-events-none absolute mt-2.5 flex items-center pl-3">
                        <img src="/img/menu-book.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название факультета" id="faculty_name" name="faculty_name">
                    <span class="text-red-500" id="faculty_name_error"></span>
                </div>
                <div class="text-white float-end">
                    <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" type="submit">Создать факультет</button>
                </div>
            </div>
        </form>
        <div class="py-10">
            <div class="bg-white space-y-3 shadow p-5 sm:rounded">
                @foreach ($faculties as $faculty)
                    <div class="flex items-center justify-between faculty">
                        <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('faculties.show', ['id' => $faculty->id]) }}'">{{ $faculty->name }}</div>
                        <img class="cursor-pointer ms-2 deleteFacultyBtn" src="/img/delete.svg" data-modal-toggle="deleteFaculty" data-id="{{ $faculty->id }}" data-delete-url="{{ route('faculties.delete', ['id' => $faculty->id]) }}">
                    </div>
                @endforeach
            </div>
        </div>
        @include("pop-ups.deleteFaculty")
    </div>
    <script>
        $('#createFacultyForm').on('submit', function(event) {
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
    </script>
</x-app-layout>