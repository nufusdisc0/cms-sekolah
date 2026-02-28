<?php

namespace App\Mail;

use App\Models\Registrant;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Registrant $registrant;

    /**
     * Create a new message instance.
     */
    public function __construct(Registrant $registrant)
    {
        $this->registrant = $registrant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $schoolName = Setting::where('setting_group', 'general')
            ->where('setting_variable', 'school_name')
            ->value('setting_value') ?? 'School CMS';

        return new Envelope(
            from: new Address(config('mail.from.address'), $schoolName),
            subject: "New Admission Form Submitted - {$this->registrant->registration_number}"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.admission-form-submitted',
            with: [
                'registrant' => $this->registrant,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Optionally attach uploaded documents
        $includeDocuments = Setting::where('setting_group', 'security')
            ->where('setting_variable', 'include_documents_in_email')
            ->value('setting_value');

        if ($includeDocuments === '1' && $this->registrant->documents) {
            $documents = is_array($this->registrant->documents)
                ? $this->registrant->documents
                : json_decode($this->registrant->documents, true);

            if (is_array($documents)) {
                foreach ($documents as $document) {
                    if (isset($document['path']) && file_exists(storage_path('app/' . $document['path']))) {
                        $attachments[] = Attachment::fromPath(storage_path('app/' . $document['path']))
                            ->as($document['name'] ?? basename($document['path']));
                    }
                }
            }
        }

        return $attachments;
    }
}
