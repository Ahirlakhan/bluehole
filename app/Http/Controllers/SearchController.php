<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SearchService;

class SearchController extends Controller
{
    /**
     * Display the search view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search');
    }

    /**
     * Handle the search request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\SearchService  $searchService
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request, SearchService $searchService)
    {
        // Validate the request data.
        $validated = $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $validated['query'];

        // If the query consists only of whitespace, redirect back with an error.
        if (trim($query) === '') {
            return redirect()->back()->withInput()->withErrors(['query' => 'The search field cannot be empty.']);
        }

        // Use the SearchService to find similar categories.
        $results = $searchService->findSimilarCategories($query);
        
        // Return the search view with the results and the original query.
        return view('search', compact('results', 'query'));
    }
}
