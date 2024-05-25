<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 font-bold">
        <div class="mt-10 p-5 rounded-xl bg-white space-y-3">
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.monday')}}'">Понелельник</div>
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.tuesday')}}'">Вторник</div>
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.wednesday')}}'">Среда</div>
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.thursday')}}'">Четверг</div>
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.friday')}}'">Пятница</div>
            <div class="w-full p-3 font-bold text-gray-900 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 hover:shadow" onclick="location='{{ route('weekday.saturday')}}'">Суббота</div>
        </div>
    </div>
</x-app-layout>