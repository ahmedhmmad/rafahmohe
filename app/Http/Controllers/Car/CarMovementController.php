<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Models\CarMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarMovementController extends Controller
{
    public function index()
    {
        return view('car.select-month-year-plan');
    }

    public function create($month, $year)
    {
        $errors = collect([]);

        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $errors->push('اختر الشهر المطلوب.');
        }

        if (!is_numeric($year) || $year < 2020 || $year > 2099) {
            $errors->push('اختر السنة المطلوبة.');
        }

        return view('car.create-plan', compact('month', 'year', 'errors'));
    }

    public function store(Request $request)
    {
        // Data validation
        $data = $request->validate([
            'days.*.date' => 'required|date',
            'days.*.directions' => 'required|array',
            'days.*.directions.*' => 'in:east,west,home,far,special,free',  // validate direction value
        ]);

        foreach ($data['days'] as $day => $info) {
            foreach ($info['directions'] as $direction) {
                CarMovement::create([
                    'date' => $day,
                    'direction' => $direction,
                ]);
            }
        }

        return redirect()->route('home')->with('success', 'Car movement plan saved successfully!');
    }



}
