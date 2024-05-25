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
                <div class="float-end" id="createBtn">
                    <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать группу</button>
                </div>
            </div>
        </form>
        <div class="py-10">
            <div class="p-5 rounded-xl bg-white space-y-3">
                @foreach ($department->groups as $group)
                    <div class="flex items-center justify-between">
                        <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('groups.show', ['id' => $group->id]) }}'">{{ $group->name }}</div>
                        <img class="cursor-pointer ms-2 deleteGroupBtn" src="/img/delete.svg" data-modal-toggle="deleteGroup" data-group-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['id' => $group->id]) }}">
                    </div>
                @endforeach
            </div>
        </div>
        @include("pop-ups.deleteGroup")
    </div>
    <script>
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