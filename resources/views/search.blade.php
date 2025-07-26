<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Search</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            text-align: center;
        }
        .search-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        .search-form button {
            padding: 0.75rem 1.5rem;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-form button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: -0.75rem;
            margin-bottom: 1rem;
        }
        .results-container {
            margin-top: 2rem;
        }
        h2 {
            font-size: 1.5rem;
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .results-list {
            list-style: none;
            padding: 0;
        }
        .result-item {
            background-color: #ecf0f1;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .result-item .name {
            font-weight: 500;
        }
        .result-item .score {
            font-size: 0.9rem;
            color: #7f8c8d;
            background-color: #fff;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
        }
        .no-results {
            background-color: #fdfaf4;
            border: 1px solid #f8e7c9;
            color: #d19c3a;
            padding: 1rem;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Category</h1>
        <form method="POST" action="{{ route('search.perform') }}" class="search-form">
            @csrf
            <input type="text" name="query" value="{{ old('query', $query ?? '') }}" placeholder="Search for a category..." required>
            <button type="submit">Search</button>
        </form>

        @error('query')
            <p class="error-message">{{ $message }}</p>
        @enderror

        @if(isset($results))
            <div class="results-container">
                <h2>Results:</h2>
                @if(!empty($results) && count($results) > 0)
                    <ul class="results-list">
                        @foreach($results as $result)
                            <li class="result-item">
                                <span class="name">{{ $result['name'] }}</span>
                                <span class="score">Score: {{ number_format($result['score'], 4) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="no-results">No results found for your query.</p>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
