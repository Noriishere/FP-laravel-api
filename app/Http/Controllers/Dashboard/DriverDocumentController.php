<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DriverDocument;

class DriverDocumentController extends Controller
{
    public function index()
    {
        $title = 'Driver Documents || Admin Gassin!';
        $navtitle = 'Driver Documents';
        $documents = DriverDocument::with('driver.user')
            ->latest()
            ->paginate(10);

        return view('pages.driver.index', compact('title', 'navtitle', 'documents'));
    }

    public function show($id)
    {
        $title = 'Driver Document Detail || Admin Gassin!';
        $navtitle = 'Driver Document Detail';
        $document = DriverDocument::with('driver.user')->findOrFail($id);

        return view('pages.driver.show', compact('title', 'navtitle', 'document'));
    }

    public function approve($id)
    {
        $doc = DriverDocument::findOrFail($id);
        $doc->update(['status' => 'approved', 'note' => null]);

        return back()->with('success', 'Dokumen disetujui');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ]);

        $doc = DriverDocument::findOrFail($id);
        $doc->update([
            'status' => 'rejected',
            'note' => $request->note
        ]);

        return back()->with('success', 'Dokumen ditolak');
    }
}
