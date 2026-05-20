<h2>
    Document Rejected
</h2>

<p>
    Your driver document has been rejected.
</p>

<ul>
    <li>
        Type:
        {{ strtoupper($document->type) }}
    </li>

    <li>
        Status:
        REJECTED
    </li>

    <li>
        Reason:
        {{ $document->note }}
    </li>
</ul>

<p>
    Please re-upload your document.
</p>