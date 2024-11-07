<!DOCTYPE html>
<html>
<head>
    <title>Discover Movies</title>
</head>
<body>
    <h1>Popular Movies</h1>

    @if($movies->count())
        <ul style="list-style-type: none; padding: 0;">
            @foreach($movies as $movie)
                <li style="margin-bottom: 20px; display: flex; align-items: center;">
                    @if($movie['poster_path'])
                        <!-- Display movie poster -->
                        <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }} Poster" style="margin-right: 20px;">
                    @else
                        <!-- Placeholder image if poster not available -->
                        <img src="https://via.placeholder.com/200x300?text=No+Image" alt="No Image" style="margin-right: 20px;">
                    @endif
                    
                    <!-- Movie title, release date, and overview -->
                    <div>
                        <strong>{{ $movie['title'] }}</strong> - Released: {{ $movie['release_date'] }}
                        <p>{{ $movie['overview'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

        <!-- Display pagination links -->
        <div>
            {{ $movies->links() }}
        </div>
    @else
        <p>No movies found.</p>
    @endif
</body>
</html>
