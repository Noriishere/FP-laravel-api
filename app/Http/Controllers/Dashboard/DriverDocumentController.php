<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DriverDocumentApproved;
use App\Mail\DriverDocumentRejected;

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
        $doc = DriverDocument::with(
            'driver.user'
        )->findOrFail($id);

        $doc->update([
            'status' => 'approved',
            'note' => null,
        ]);

        $this->syncDriverStatus(
            $doc->driver_id
        );

        Mail::to(
            $doc->driver->user->email
        )->send(
            new DriverDocumentApproved(
                $doc
            )
        );

        return redirect()
            ->route(
                'driver-documents.show',
                $id
            )
            ->with(
                'success',
                'Dokumen disetujui'
            );
    }

    public function reject(
        Request $request,
        $id
    ) {

        $request->validate([
            'note' => 'required',
        ]);

        $doc = DriverDocument::with(
            'driver.user'
        )->findOrFail($id);

        $doc->update([

            'status' => 'rejected',

            'note' => $request->note,
        ]);

        $this->syncDriverStatus(
            $doc->driver_id
        );

        Mail::to(
            $doc->driver->user->email
        )->send(
            new DriverDocumentRejected(
                $doc
            )
        );

        return back()->with(
            'success',
            'Dokumen ditolak'
        );
    }

    private function syncDriverStatus($driverId)
    {
        $driver = Driver::with('documents')->find($driverId);
        if (! $driver) {
            return;
        }

        $docs = $driver->documents;

        if ($docs->count() < 3) {
            $driver->update(['verification_status' => 'pending']);

            return;
        }

        if ($docs->contains('status', 'rejected')) {
            $driver->update(['verification_status' => 'rejected']);

            return;
        }

        if ($docs->every(fn ($d) => $d->status === 'approved')) {
            $driver->update([
                'verification_status' => 'approved',
                'status' => 'offline',
            ]);

            return;
        }

        // ⏳ sisanya pending
        $driver->update(['verification_status' => 'pending']);
    }
}
