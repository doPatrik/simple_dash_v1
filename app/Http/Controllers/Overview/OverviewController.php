<?php

namespace App\Http\Controllers\Overview;

use App\Http\Controllers\Controller;
use App\Http\Services\BillService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $bills = (new BillService())->getBills();
        $providers = $user->providers()->get()->pluck('name', 'id_provider');
        return view('Overview.overview', compact('bills', 'providers'));
    }
}
