<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\JsonResponse;

class LoanHistoryController extends Controller
{
    public function index(): JsonResponse
    {
        $loans = Loan::with('book')->orderByDesc('loaned_at')->get()->map(function (Loan $loan) {
            return [
                'id' => $loan->id,
                'book_id' => $loan->book_id,
                'book_title' => $loan->book?->title,
                'requester_name' => $loan->requester_name,
                'loaned_at' => $loan->loaned_at,
                'returned_at' => $loan->returned_at,
                'status' => $loan->returned_at ? 'Devuelto' : 'Activo',
            ];
        });

        return response()->json($loans);
    }
}

