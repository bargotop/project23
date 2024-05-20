<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <form id="createStudentForm" action="{{ route('students.create', ['groupId' => $group->id]) }}" method="POST">
            @csrf
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-10 p-5">
                <div>{{$group->name}}</div>
                <div class="relative shadow-sm mt-3">
                    <div class="pointer-events-none absolute inset-y-0 flex items-center pl-3">
                        <img src="/img/import-contacts.svg">
                    </div>
                    <input class="w-full rounded-md pl-12 placeholder:text-gray-400" id="subject_id" placeholder="Название предмета" name="subject_id">
                </div>
                <div class="float-right" id="createBtn">
                    <button class="text-white bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded mt-3" type="submit">Создать предмет</button>
                </div>
            </div>
        </form>
        <div class="pt-5 pb-10">
            <div class="flex justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5 p-5">
                <div>Amirbok</div>
                <img class="cursor-pointer ms-2 deleteSubjectBtn" src="/img/delete.svg" data-modal-toggle="deleteSubject" data-id="{{ $group->id }}" data-delete-url="{{ route('groups.delete', ['groupId' => $group->id]) }}">
            </div>
        </div>
        @include("pop-ups.deleteSubject")
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
        $('.deleteSubjectBtn').on('click', function() {
            const btn = $(this)
            const id = btn.data("id")
            const deleteUrl = btn.data("delete-url")
            $('.deleteSubjectConfirmBtn').off('click')
            $('.deleteSubjectConfirmBtn').on('click', function() {
                deleteSubject(btn, id, deleteUrl)
            });
        });
    </script>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</x-app-layout>