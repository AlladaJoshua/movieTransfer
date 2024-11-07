<!DOCTYPE html>
<html>
<head>
    <title>Movie Search</title>
</head>
<body>
    <h1>Search for Movies</h1>

    <form action="{{ url('/movies/search') }}" method="GET">
        <input type="text" name="query" value="{{ $query }}" placeholder="Enter movie title..." required>
        <button type="submit">Search</button>
    </form>

    <h2>Search Results for "{{ $query }}"</h2>

    @if($movies->count())
        <ul style="list-style-type: none; padding: 0;">
            @foreach($movies as $movie)
                <li style="margin-bottom: 20px; display: flex; align-items: center;">
                    @if($movie['poster_path'])
                        <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }} Poster" style="margin-right: 20px;">
                    @else
                        <img src="https://via.placeholder.com/200x300?text=No+Image" alt="No Image" style="margin-right: 20px;">
                    @endif
                    
                    <div>
                        <strong>{{ $movie['title'] }}</strong> - Released: {{ $movie['release_date'] }}
                        <p>{{ $movie['overview'] }}</p>

                        <form action="{{ route('movies.save') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="title" value="{{ $movie['title'] }}">
                            <input type="hidden" name="release_date" value="{{ $movie['release_date'] }}">
                            <input type="hidden" name="overview" value="{{ $movie['overview'] }}">
                            <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
                            <button type="submit">Save</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <div>
            {{ $movies->appends(['query' => $query])->links() }}
        </div>
    @else
        <p>No movies found for "{{ $query }}".</p>
    @endif
</body>
</html>
