<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\DealerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the dealer profile.
     */
    public function show(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        return view('dealer.profile.show', compact('dealer'));
    }

    /**
     * Show the form for editing the dealer profile.
     */
    public function edit(): View
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to access this page.');
        }

        return view('dealer.profile.edit', compact('dealer'));
    }

    /**
     * Update the dealer profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $dealer = auth()->user()->dealerProfile;

        if (!$dealer) {
            abort(403, 'You must have a dealer profile to update.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'business_registration' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:2000',
            'bank_account' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_registration' => $request->business_registration,
            'tax_id' => $request->tax_id,
            'phone' => $request->phone,
            'website' => $request->website,
            'description' => $request->description,
            'bank_account' => $request->bank_account,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($dealer->logo) {
                \Storage::delete($dealer->logo);
            }
            $data['logo'] = $request->file('logo')->store('dealer-logos', 'public');
        }

        // Handle documents upload
        if ($request->hasFile('documents')) {
            $existingDocuments = $dealer->documents ?? [];
            $newDocuments = [];

            foreach ($request->file('documents') as $document) {
                $newDocuments[] = $document->store('dealer-documents', 'public');
            }

            $data['documents'] = array_merge($existingDocuments, $newDocuments);
        }

        $dealer->update($data);

        return redirect()->route('dealer.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
