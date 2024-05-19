<div id="deleteStudent" class="hidden fixed inset-0 z-10">
    <div class="text-center bg-white rounded-lg shadow m-4 p-4">
        <svg class="w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        <div class="text-gray-500 mt-5">Вы уверены, что хотите удалить студента?</div>
        <div class="text-[14px] flex space-x-3 mt-5">
            <button class="w-full rounded-lg py-2 text-white bg-red-500 hover:bg-red-600 active:bg-red-700 deleteStudentConfirmBtn" data-modal-toggle="deleteStudent">Да</button>
            <button class="w-full rounded-lg py-2 bg-gray-100 hover:bg-gray-200 active:bg-gray-300" data-modal-toggle="deleteStudent">Нет</button>
        </div>
    </div>
</div>