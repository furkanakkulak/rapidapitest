<?php

namespace Furkanakkulak\Rapidapitest\Http\RapidApi;

use Illuminate\Support\Facades\Http;

class MovieService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function searchMoviesDatabase($name)
    {
        $response = $this->search('https://moviesdatabase.p.rapidapi.com/titles/search/keyword/', $name);

        $movieSearchData = [];
        foreach ($response as $result) {
            $movieSearchData[] = [
                'title' => $result['titleText']['text'] ?? null,
                'image' => isset($result['primaryImage']['url']) ? $result['primaryImage']['url'] : null,
                'release_year' => $result['releaseYear']['year'] ?? null,
            ];
        }

        return $movieSearchData;
    }

    public function searchAdvancedMovie($name)
    {
        $response = $this->search('https://advanced-movie-search.p.rapidapi.com/search/movie?query=', $name);

        $advancedMovieSearchData = [];
        foreach ($response as $result) {
            $advancedMovieSearchData[] = [
                'title' => $result['title'] ?? null,
                'image' => isset($result['poster_path']) ? $result['poster_path'] : null,
                'release_year' => intval(substr($result['release_date'], 0, 4)) ?? null,
            ];
        }

        return $advancedMovieSearchData;
    }

    protected function search($url, $name)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => parse_url($url, PHP_URL_HOST),
            'X-RapidAPI-Key' => $this->apiKey,
        ])->get($url . $name);

        return $response->json()['results'];
    }
}
