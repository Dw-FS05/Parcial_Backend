<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Consulta de catálogo de libros.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->string('title')->toString() . '%');
        }

        if ($request->filled('isbn')) {
            $query->where('isbn', 'like', '%' . $request->string('isbn')->toString() . '%');
        }

        if ($request->filled('status')) {
            // Se acepta "1"/"0", "true"/"false", etc.
            $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

            if (! is_null($status)) {
                $query->where('is_available', $status);
            }
        }

        $books = $query->get();

        return BookResource::collection($books);
    }
}

