<h2>
    New Driver Document Uploaded
</h2>

<p>
    A driver has uploaded
    a new document.
</p>

<ul>
    <li>
        Driver ID:
        {{ $document->driver_id }}
    </li>

    <li>
        Type:
        {{ strtoupper($document->type) }}
    </li>

    <li>
        Status:
        {{ strtoupper($document->status) }}
    </li>
</ul>

<p>
    Please verify the document
    from admin dashboard.
</p>