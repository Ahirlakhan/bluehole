<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Services\SearchService;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Artisan command to import categories from an Excel file,
 * generate embeddings for each category name using the SearchService,
 * and store them in the database.
 */
class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories from Excel and store embeddings';

    /**
     * The SearchService instance.
     *
     * @var \App\Services\SearchService
     */
    protected $searchService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\SearchService  $searchService
     * @return void
     */
    public function __construct(SearchService $searchService)
    {
        parent::__construct();
        $this->searchService = $searchService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Define the path to the Excel file.
        $path = storage_path('app/public/Lynx_Keyword_Enhanced_Services.xlsx');

        // Read the data from the Excel file.
        $rows = Excel::toArray([], $path)[0];

        // Iterate over each row from the Excel file.
        foreach ($rows as $index => $row) {
            // Skip the header row or any empty rows.
            if ($index === 0 || empty($row[0])) {
                continue;
            }

            // Trim the category name from the first column.
            $name = trim($row[0]);
            // Generate an embedding for the category name using the SearchService.
            //$embedding = $this->searchService->getQueryEmbedding($name);
            $embedding = null;
            // Create or update the category with its name and embedding.
            Category::updateOrCreate(['name' => $name], ['embedding' => $embedding]);
            // Output a message to the console indicating the import status.
            $this->info("Imported: $name");
        }
    }
}
