<?php


namespace App\Http\Controllers\Calendar;


use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarResource;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $bills = Auth::user()->bills()->where('is_paid', false)->with('provider')->get();
        return view('Calendar.calendar', ['bills' => $bills]);
    }

    public function getEvents()
    {
        return CalendarResource::collection(Auth::user()->bills()->where('is_paid', false)->with('provider')->get());
    }
}
