<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Display a listing of the user's inquiries.
     */
    public function index(): View
    {
        $inquiries = auth()->user()
            ->inquiries()
            ->with(['car.brand', 'car.carModel'])
            ->latest()
            ->paginate(10);

        return view('inquiries.index', compact('inquiries'));
    }

    /**
     * Store a newly created inquiry.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
            'phone' => 'nullable|string|max:20',
        ]);

        $car = Car::findOrFail($request->car_id);

        auth()->user()->inquiries()->create([
            'car_id' => $car->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'phone' => $request->phone,
            'status' => 'new',
        ]);

        // TODO: Send notification email to dealer/admin

        return back()->with('success', 'Inquiry sent! We will contact you soon.');
    }
}
