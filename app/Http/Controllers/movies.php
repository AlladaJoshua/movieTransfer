<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class movies extends Controller
{
    public function discoverMovies(Request $request)
    {
        $client = new Client();
        $page = $request->query('page', 1); // Get the page number from the query string, default to 1
        $response = $client->request('GET', "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&sort_by=popularity.desc&page={$page}", [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI4YjE5YTE3NTliZDYxMjgzZTdkMTEzYTFhNTZlNTMxMiIsIm5iZiI6MTczMDkzODA0OS4xMTUyMzk0LCJzdWIiOiI2NzAxNzIzZmU0ODAxNDkxNDY4NTZkOTIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.l2Ygzr07ca5uK3yZns54tuP-QuNcyeDIFo6AwYJcAAI',
                'accept' => 'application/json',
            ],
        ]);

        // Decode the JSON response
        $movies = json_decode($response->getBody(), true);

        // Create a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $movies['results'], // Items
            $movies['total_results'], // Total items
            20, // Per page
            $page, // Current page
            ['path' => url('/movies/discover')] // Set the base URL for pagination links
        );

        // Pass the paginator to the Blade view
        return view('discover-movies', ['movies' => $paginator]);
    }
}
