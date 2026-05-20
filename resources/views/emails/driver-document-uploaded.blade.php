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
    Click button below to review document:
</p>

<a href="{{ route('driver-documents.show', $document->id) }}"
   style="
        display:inline-block;
        padding:12px 20px;
        background:#2563eb;
        color:white;
        text-decoration:none;
        border-radius:8px;
        font-weight:bold;
   ">

    Review Document

</a>