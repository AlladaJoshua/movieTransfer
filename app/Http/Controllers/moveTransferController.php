<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\movieTransfer;
use Illuminate\Support\Facades\Storage;

class moveTransferController extends Controller
{   
    public function movieTransfer(Request $request) {
        $client = new Client();
        $page = $request->query('page', 1);
        $query = $request->query('query'); // Search query
        
        if ($query) {
            $response = $client->request('GET', "https://api.themoviedb.org/3/search/movie?query=". urlencode($query) ."&include_adult=false&language=en-US&page={$page}", [
                'headers' => [
                    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI4YjE5YTE3NTliZDYxMjgzZTdkMTEzYTFhNTZlNTMxMiIsIm5iZiI6MTczMDkzODA0OS4xMTUyMzk0LCJzdWIiOiI2NzAxNzIzZmU0ODAxNDkxNDY4NTZkOTIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.l2Ygzr07ca5uK3yZns54tuP-QuNcyeDIFo6AwYJcAAI',
                    'accept' => 'application/json',
                ],
            ]);
        } else {
            $response = $client->request('GET', "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&sort_by=popularity.desc&page={$page}", [
                'headers' => [
                    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI4YjE5YTE3NTliZDYxMjgzZTdkMTEzYTFhNTZlNTMxMiIsIm5iZiI6MTczMDkzODA0OS4xMTUyMzk0LCJzdWIiOiI2NzAxNzIzZmU0ODAxNDkxNDY4NTZkOTIiLCJzY29wZXMiOlsiYXBpX3JlYWQiXSwidmVyc2lvbiI6MX0.l2Ygzr07ca5uK3yZns54tuP-QuNcyeDIFo6AwYJcAAI',
                    'accept' => 'application/json',
                ],
            ]);
        }

          // Decode the JSON response to get movies
          $movies = json_decode($response->getBody(), true);

          $paginator = new LengthAwarePaginator(
            $movies['results'], // Items
            $movies['total_results'], // Total items
            20, // Per page
            $page, // Current page
            ['path' => url('/movies/search'), 'query' => ['query' => $query]] // URL and query for pagination links
        );

        return view('movie-transfer', ['movies' => $paginator, 'query' => $query]);
    }

    public function saveMovie(Request $request)
    {
        // Validate and save movie data to the database
        // Get the poster image URL
        $posterUrl = 'https://image.tmdb.org/t/p/w500' . $request->input('poster_path');
        
        // Get the image content using Guzzle
        $client = new Client();
        $imageResponse = $client->get($posterUrl);
        $imageContent = $imageResponse->getBody();

        // Generate a filename (you can modify this as needed)
        $filename = basename($posterUrl);

        // Save the image to the 'public/movies' directory
        $path = Storage::disk('public')->put('movies/' . $filename, $imageContent);

        // Validate and save movie data to the database, including the image path
        $movie = MovieTransfer::create([
            'title' => $request->input('title'),
            'release_date' => $request->input('release_date'),
            'overview' => $request->input('overview'),
            'poster_path' => 'movies/' . $filename, // Store the relative path to the saved image
        ]);

        return redirect()->back()->with('success', 'Movie has been saved to the database.');
    }
}
