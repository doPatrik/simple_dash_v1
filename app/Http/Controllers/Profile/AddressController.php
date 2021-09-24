<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Profile.create_address');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'post_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street_name' => ['required', 'string', 'max:255'],
        ]);

        $address = new Address();
        $address->timestamps = false;
        $address->post_code = $request->input('post_code');
        $address->city = $request->input('city');
        $address->street_name = $request->input('street_name');
        $address->id_user = Auth::user()->id;

        $address->save();

        return redirect('/profile')->with('success', 'Sikeres lakcím felvétele');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = Address::find($id);

        return view('Profile.edit_address')->with('address', $address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'post_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street_name' => ['required', 'string', 'max:255'],
        ]);

        $address = Address::find($id);
        $address->timestamps = false;
        $address->post_code = $request->input('post_code');
        $address->city = $request->input('city');
        $address->street_name = $request->input('street_name');

        $address->save();

        return redirect('/profile')->with('success', 'Sikeres lakcím módosítás');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::query()->findOrFail($id);

        if($address->provider && $address->provider->count() > 0) {
            return redirect('/profile')->with('error', 'Előbb töröld a hozzátartozó számlákat');
        }
        $address->delete();
        return redirect('/profile')->with('success', 'Lakcím eltávolítva');
    }

    public function address()
    {
        if (request('id_address', '') !== '-1') {
            $address = Address::query()->findOrFail(request('id_address'));
            session(['address_id' => $address->id_address, 'address_name' => $address->city]);
        } else {
            session()->forget(['address_id', 'address_name']);
        }

        return back();
    }
}
