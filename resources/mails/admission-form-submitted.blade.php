# New Admission Form Submitted

Hello Admin,

A new admission form has been submitted and requires your attention.

## Submission Details

- **Registration Number**: {{ $registrant->registration_number }}
- **Status**: {{ ucfirst(str_replace('_', ' ', $registrant->status)) }}
- **Submitted On**: {{ $registrant->created_at->format('d F Y \a\t H:i') }}

## Applicant Information

**Name**: {{ $registrant->full_name }}
**Email**: {{ $registrant->email }}
**Phone**: {{ $registrant->phone }}

## Academic Information

**Admission Phase**: {{ $registrant->admissionPhase->phase_name ?? 'N/A' }}
**Applied Major**: {{ $registrant->major->major_name ?? 'N/A' }}
**Address**: {{ $registrant->address ?? 'N/A' }}

## Action Required

Review and process this application in the admin panel:

[@Review Application]({{ route('backend.registrants.show', $registrant->id) }})

---

Thank you,
{{ config('app.name', 'CMS Sekolah') }} System

---

*This is an automated notification. Please do not reply to this email.*
