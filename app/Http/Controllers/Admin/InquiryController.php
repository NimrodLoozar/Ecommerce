<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View
    {
        $inquiries = Inquiry::with(['user', 'car'])
            ->latest()
            ->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry): View
    {
        $inquiry->load(['user', 'car']);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,converted',
        ]);

        $inquiry->update(['status' => $request->status]);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Inquiry status updated successfully!');
    }
}
