<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6">
        <div class="bg-white space-y-3 shadow mt-10 p-5 sm:rounded">
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.monday')}}'">Понедельник</div>
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.tuesday')}}'">Вторник</div>
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.wednesday')}}'">Среда</div>
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.thursday')}}'">Четверг</div>
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.friday')}}'">Пятница</div>
            <div class="w-full p-3 rounded bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('schedule.saturday')}}'">Суббота</div>
        </div>
    </div>
</x-app-layout>