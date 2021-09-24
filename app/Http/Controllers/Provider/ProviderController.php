<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Auth::user()->providers;
        return view('Provider.provider')->with('providers', $providers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $addresses = $user->addresses;
        return view('Provider.createProvider')->with('addresses', $addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'post_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'user_address' => ['required', 'int'],
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'int'],
            'color_code' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
        ]);

        $provider = new Provider();

        $provider->id_user = Auth::user()->id;
        $provider->id_provider_address = $provider->providerAddress()->create([
            'postal_code' => $request->input('post_code'),
            'city' => $request->input('city'),
            'street_name' => $request->input('street')
        ])->getKey();
        $provider->id_address = $request->input('user_address');
        $provider->name = $request->input('name');
        $provider->owner_name = $request->input('owner_name');
        $provider->number = $request->input('number');
        $provider->color_code = $request->input('color_code');
        $provider->label = $request->input('label');
        $provider->save();

        return redirect('/provider')->with('success', 'Sikeres szolgáltató felvétele');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data['provider'] = Provider::find($id)->load(['providerAddress']);
        $data['addresses'] = Auth::user()->addresses;
        return view('Provider.editProvider')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'post_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'user_address' => ['required', 'int'],
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'int'],
            'color_code' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
        ]);

        /**
         * @var Provider $provider
         */
        $provider = Provider::find($id)->load('providerAddress');
        $provider->providerAddress->postal_code = $request->input('post_code');
        $provider->providerAddress->city = $request->input('city');
        $provider->providerAddress->street_name = $request->input('street');
        $provider->providerAddress->save();


        $provider->id_address = $request->input('user_address');
        $provider->name = $request->input('name');
        $provider->owner_name = $request->input('owner_name');
        $provider->number = $request->input('number');
        $provider->color_code = $request->input('color_code');
        $provider->label = $request->input('label');
        $provider->save();

        return redirect('/provider')->with('success', 'Sikeres szolgáltató módosítás');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provider = Provider::query()->findOrFail($id);
        if($provider->bills && $provider->bills->count() > 0) {
            return redirect('/provider')->with('error', 'Előbb töröld a hozzátartozó számlákat');
        }
        $provider->delete();
        return redirect('/provider')->with('success', 'Szolgáltató eltávolítva');
    }
}
