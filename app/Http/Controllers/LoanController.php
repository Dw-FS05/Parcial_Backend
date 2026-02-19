<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    /**
     * Registrar un nuevo préstamo de libro.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var \App\Models\Book $book */
        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies <= 0) {
            return response()->json([
                'message' => 'No hay copias disponibles para este libro.',
            ], 422);
        }

        $loan = Loan::create([
            'book_id' => $book->id,
            'requester_name' => $validated['requester_name'],
            // loaned_at se define con default en la BD, pero lo dejamos explícito
            'loaned_at' => now(),
        ]);

        $book->available_copies -= 1;

        if ($book->available_copies <= 0) {
            $book->is_available = false;
        }

        $book->save();

        return response()->json([
            'message' => 'Préstamo registrado correctamente.',
            'data' => $loan,
        ], 201);
    }

    /**
     * Registrar la devolución de un libro.
     */
    public function return(int $loanId): JsonResponse
    {
        /** @var \App\Models\Loan $loan */
        $loan = Loan::with('book')->findOrFail($loanId);

        if ($loan->returned_at !== null) {
            return response()->json([
                'message' => 'Este préstamo ya fue devuelto previamente.',
            ], 422);
        }

        $loan->returned_at = now();
        $loan->save();

        $book = $loan->book;
        $book->available_copies += 1;

        if (! $book->is_available) {
            $book->is_available = true;
        }

        $book->save();

        return response()->json([
            'message' => 'Devolución registrada correctamente.',
            'data' => $loan->fresh(),
        ], 200);
    }
}

