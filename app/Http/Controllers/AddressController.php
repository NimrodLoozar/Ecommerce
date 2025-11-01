<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index(): View
    {
        $addresses = auth()->user()->addresses;

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create(): View
    {
        $return = request('return');

        return view('addresses.create', compact('return'));
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other default addresses
        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($request->all());

        $return = $request->input('return');

        if ($return === 'checkout') {
            return redirect()->route('checkout.index')
                ->with('success', 'Address added successfully!');
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Address added successfully!');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address): View
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, Address $address): RedirectResponse
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other default addresses
        if ($request->boolean('is_default')) {
            auth()->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return redirect()->route('addresses.index')
            ->with('success', 'Address updated successfully!');
    }

    /**
     * Remove the specified address.
     */
    public function destroy(Address $address): RedirectResponse
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address deleted.');
    }

    /**
     * Set the specified address as default.
     */
    public function setDefault(Address $address): RedirectResponse
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Unset all other default addresses
        auth()->user()->addresses()->update(['is_default' => false]);

        // Set this address as default
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }
}
