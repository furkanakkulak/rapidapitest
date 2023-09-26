<?php

namespace Furkanakkulak\Rapidapitest\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Furkanakkulak\Rapidapitest\Http\RapidApi\MovieService;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    private function validateName($name)
    {
        if (empty($name)) {
            return response()->json(['error' => 'Name parameter is required'], 400);
        }

        return null;
    }

    public function index(Request $request)
    {
        $name = $request->input('name');
        $validationResult = $this->validateName($name);

        if ($validationResult) {
            return $validationResult;
        }

        $movieSearchData = $this->movieService->searchMoviesDatabase($name);
        $advancedMovieSearchData = $this->movieService->searchAdvancedMovie($name);

        $combinedResponse = array_merge($movieSearchData, $advancedMovieSearchData);

        return response()->json($combinedResponse);
    }

    public function movieSearch(Request $request)
    {
        $name = $request->input('name');
        $validationResult = $this->validateName($name);

        if ($validationResult) {
            return $validationResult;
        }

        $movieSearchData = $this->movieService->searchMoviesDatabase($name);

        return response()->json($movieSearchData);
    }

    public function advancedMovieSearch(Request $request)
    {
        $name = $request->input('name');
        $validationResult = $this->validateName($name);

        if ($validationResult) {
            return $validationResult;
        }

        $advancedMovieSearchData = $this->movieService->searchAdvancedMovie($name);

        return response()->json($advancedMovieSearchData);
    }
}
