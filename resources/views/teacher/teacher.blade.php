<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <div class="bg-white overflow-hidden shadow sm:rounded mt-10 p-5 space-y-3">
            <div>Amirbok</div>
            <div class="text-white float-end">
                <button class="bg-green-500 hover:bg-green-700 active:bg-green-900 py-2 px-4 rounded" onclick="location='{{ route('schedule.index') }}'">Создать расписание</button>
            </div>
        </div>
    </div>
</x-app-layout>