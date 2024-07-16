<!DOCTYPE html>
<html>
<head>
    <title>Books List</title>
</head>
<body>
    <h1>Books List</h1>

    @if ($books->isEmpty())
        <p>No books available.</p>
    @else
        <ul>
            @foreach ($books as $book)
                <li>{{ $book->name }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>

