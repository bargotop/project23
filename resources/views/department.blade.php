<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <form id="createGroupForm" action="{{ route('groups.create', ['departmentId' => $department->id]) }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow sm:rounded mt-10 p-5 space-y-3">
                <div>{{$department->name}}</div>
                <div class="relative">
                    <div class="pointer-events-none absolute mt-2.5 flex items-center pl-3">
                        <img src="/img/group.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" placeholder="Название группы" id="group_name" name="group_name">
                    <span class="text-red-500" id="group_name_error"></span>
                </div>
                <div class="float-end">
                    <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" type="submit">Создать группу</button>
                </div>
            </div>
        </form>
        <div class="py-10">
            <div class="bg-white space-y-3 shadow p-5 sm:rounded">
                @foreach ($department->groups as $group)
                    <div class="flex items-center justify-between group">
                        <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('groups.show', ['id' => $group->id]) }}'">{{ $group->name }}</div>
                        <img class="cursor-pointer ms-2 deleteGroupBtn" src="/img/delete.svg" data-modal-toggle="deleteGroup" data-group-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['id' => $group->id]) }}">
                    </div>
                @endforeach
            </div>
        </div>
        @include("pop-ups.deleteGroup")
    </div>
    <script>
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
    </script>
</x-app-layout>