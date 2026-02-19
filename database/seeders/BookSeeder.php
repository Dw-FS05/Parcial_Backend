<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('data/books_classic.csv');

        if (! file_exists($csvPath)) {
            throw new \RuntimeException("CSV file not found at {$csvPath}");
        }

        $handle = fopen($csvPath, 'r');

        if (! $handle) {
            throw new \RuntimeException("Unable to open CSV file at {$csvPath}");
        }

        $header = fgetcsv($handle);
        $manualCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if ($header && count($header) === count($row)) {
                $data = array_combine($header, $row);
            } else {
                $data = [
                    'title' => $row[0] ?? null,
                    'description' => $row[1] ?? null,
                    'isbn' => $row[2] ?? null,
                    'total_copies' => $row[3] ?? null,
                    'available_copies' => $row[4] ?? null,
                    'status' => $row[5] ?? null,
                ];
            }

            $totalCopies = isset($data['total_copies']) ? (int) $data['total_copies'] : 5;
            $availableCopies = isset($data['available_copies']) ? (int) $data['available_copies'] : $totalCopies;
            $availableCopies = min($availableCopies, $totalCopies);

            $statusValue = strtolower((string) ($data['status'] ?? 'disponible'));
            $isAvailable = in_array($statusValue, ['disponible', 'available', '1', 'true'], true);

            Book::create([
                'title' => $data['title'] ?? 'Untitled',
                'description' => $data['description'] ?? null,
                'isbn' => $data['isbn'] ?? null,
                'total_copies' => $totalCopies,
                'available_copies' => $availableCopies,
                'is_available' => $isAvailable,
            ]);

            $manualCount++;
        }

        fclose($handle);

        $remaining = max(0, 100 - $manualCount);

        if ($remaining > 0) {
            Book::factory($remaining)->create();
        }
    }
}

