<!-- resources/views/upload.blade.php -->

<form action="/upload" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="pdf">
    <button type="submit">Upload PDF</button>
</form>
