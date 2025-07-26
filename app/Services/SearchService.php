<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Config;
use OpenAI;

class SearchService
{
    protected $openai;

    public function __construct()
    {
        $this->openai = OpenAI::client(Config::get('services.openai.key'));
    }

    /**
     * Find categories similar to a given query.
     *
     * @param string $query
     * @return \Illuminate\Support\Collection
     */
    public function findSimilarCategories(string $query)
    {
        // Create an embedding for the search query.
        $embedding = $this->getQueryEmbedding($query);

        // Retrieve all categories and calculate the cosine similarity.
        // Consider using a vector database or a database with vector search capabilities (e.g., pgvector).
        $results = Category::all()->map(function ($category) use ($embedding) {
            $score = $this->cosineSimilarity($embedding, $category->embedding);
            return ['name' => $category->name, 'score' => $score];
        });

        // Sort by score and take the top 5 results.
        return $results->sortByDesc('score')->take(5)->values();
    }

    /**
     * Get the embedding for a given text from OpenAI.
     *
     * @param string $text
     * @return array
     */
    public function getQueryEmbedding(string $text): array
    {
        $response = $this->openai->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $text,
        ]);

        return $response['data'][0]['embedding'];
    }

    /**
     * Calculate the cosine similarity between two vectors.
     *
     * @param array $vec1
     * @param array $vec2
     * @return float
     */
    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dot   = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($vec1 as $i => $value) {
            $dot += $value * $vec2[$i];
            $normA += $value ** 2;
            $normB += $vec2[$i] ** 2;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
