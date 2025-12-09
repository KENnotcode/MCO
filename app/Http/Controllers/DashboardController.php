<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $citations = $user->citations()->with('violation')->get();

        $totalUnpaidFees = $citations->where('status', 'unpaid')->reduce(function ($carry, $citation) {
            $fee = 0;
            if ($citation->violation) {
                switch ($citation->offense_level) {
                    case 1:
                        $fee = $citation->violation->first_offense;
                        break;
                    case 2:
                        $fee = $citation->violation->second_offense;
                        break;
                    case 3:
                        $fee = $citation->violation->third_offense;
                        break;
                    case 4:
                        $fee = $citation->violation->fourth_offense;
                        break;
                }
            }
            return $carry + (float) $fee;
        }, 0);

        return view('dashboard', compact('citations', 'totalUnpaidFees'));
    }
}
