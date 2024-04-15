<form action="{{ route('createFaculty') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
    </div>
    <button type="submit">Create Faculty</button>
</form>