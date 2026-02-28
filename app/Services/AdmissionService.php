<?php

namespace App\Services;

use App\Models\Registrant;
use App\Models\SchoolProfile;
use Carbon\Carbon;
use TCPDF;
use Illuminate\Http\Response;

class AdmissionService
{
    /**
     * Get school profile information
     *
     * @return array
     */
    protected function getSchoolInfo(): array
    {
        // In a real implementation, fetch from settings
        return [
            'name' => config('app.school_name', 'School Name'),
            'npsn' => config('app.school_npsn', 'NPSN'),
            'address' => config('app.school_address', ''),
            'phone' => config('app.school_phone', ''),
            'email' => config('app.school_email', ''),
            'website' => config('app.school_website', ''),
            'logo_path' => public_path('assets/images/school-logo.png'),
            'letterhead_path' => public_path('assets/images/letterhead.png'),
        ];
    }

    /**
     * Generate filled PDF admission form for a registrant
     *
     * @param Registrant $registrant
     * @param bool $download
     * @return Response|string
     */
    public function generateFilledPDF(Registrant $registrant, bool $download = true)
    {
        $schoolInfo = $this->getSchoolInfo();

        // Ensure registration number is generated
        if (!$registrant->registration_number) {
            RegistrationNumberService::generateForRegistrant($registrant);
        }

        // Create PDF object
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolInfo['name']);
        $pdf->SetTitle('Admission Form - ' . $registrant->registration_number);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set margins
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);

        // Set font
        $pdf->SetFont('helvetica', '', 11);

        // Add page
        $pdf->AddPage();

        // Header with school logo
        $this->addPDFHeader($pdf, $schoolInfo);

        // Add title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->ln(10);
        $pdf->Cell(0, 10, 'FORMULIR PENDAFTARAN SISWA BARU', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Tahun Ajaran ' . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');

        // Registration number and date
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->Cell(50, 8, 'No. Pendaftaran:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 8, $registrant->registration_number, 0, 1);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(50, 8, 'Tanggal Pendaftaran:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 8, $registrant->registration_date?->format('d/m/Y') ?? '-', 0, 1);

        // Personal Information Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(8);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI PRIBADI', 0, 1, 'L', true);

        $this->addFormField($pdf, 'Nama Lengkap:', $registrant->full_name);
        $this->addFormField($pdf, 'NISN:', $registrant->nisn);
        $this->addFormField($pdf, 'NIK:', $registrant->nik);
        $this->addFormField($pdf, 'Jenis Kelamin:', $registrant->gender === 'M' ? 'Laki-laki' : 'Perempuan');
        $this->addFormField($pdf, 'Tempat Lahir:', $registrant->birth_place);
        $this->addFormField($pdf, 'Tanggal Lahir:', $registrant->birth_date?->format('d/m/Y'));

        // Contact Information Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI KONTAK', 0, 1, 'L', true);

        $this->addFormField($pdf, 'Email:', $registrant->email);
        $this->addFormField($pdf, 'No. Telepon:', $registrant->phone);
        $this->addFormField($pdf, 'Alamat:', $registrant->address);
        $this->addFormField($pdf, 'Kota/Kabupaten:', $registrant->city);
        $this->addFormField($pdf, 'Provinsi:', $registrant->province);
        $this->addFormField($pdf, 'Kode Pos:', $registrant->postal_code);

        // Parent Information Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI ORANG TUA/WALI', 0, 1, 'L', true);

        $this->addFormField($pdf, 'Nama:', $registrant->parent_name);
        $this->addFormField($pdf, 'Email:', $registrant->parent_email);
        $this->addFormField($pdf, 'No. Telepon:', $registrant->parent_phone);

        // Academic Information Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI AKADEMIK', 0, 1, 'L', true);

        $this->addFormField($pdf, 'Jurusan/Program:', $registrant->major);
        $this->addFormField($pdf, 'Sekolah Asal:', $registrant->previous_school);
        $this->addFormField($pdf, 'No. Induk Siswa:', $registrant->nisn);
        if ($registrant->previous_gpa) {
            $this->addFormField($pdf, 'Nilai Rata-rata:', number_format($registrant->previous_gpa, 2));
        }

        // Status Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'STATUS PENDAFTARAN', 0, 1, 'L', true);

        $this->addFormField($pdf, 'Status Aplikasi:', $this->translateApplicationStatus($registrant->application_status));
        $this->addFormField($pdf, 'Status Seleksi:', $registrant->selection_status ? $this->translateSelectionStatus($registrant->selection_status) : '-');

        // Footer
        $pdf->ln(10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 8, 'Dokumen ini diproduksi secara otomatis oleh sistem pendaftaran online.', 0, 1, 'C');
        $pdf->Cell(0, 8, 'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i:s'), 0, 1, 'C');

        // Save PDF to path
        $pdfPath = $this->savePDFToStorage($pdf, $registrant);
        $registrant->update([
            'pdf_path' => $pdfPath,
            'pdf_generated_at' => now(),
        ]);

        if ($download) {
            $filename = sprintf('Admission_Form_%s.pdf', $registrant->registration_number);
            return response($pdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        return $pdf->Output('', 'S'); // Return PDF content as string
    }

    /**
     * Generate blank admission form template
     *
     * @param bool $download
     * @return Response|string
     */
    public function generateBlankPDF(bool $download = true)
    {
        $schoolInfo = $this->getSchoolInfo();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolInfo['name']);
        $pdf->SetTitle('Blank Admission Form');
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->AddPage();

        // Header
        $this->addPDFHeader($pdf, $schoolInfo);

        // Title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->ln(10);
        $pdf->Cell(0, 10, 'FORMULIR PENDAFTARAN SISWA BARU (TEMPLATE KOSONG)', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 8, 'Tahun Ajaran ' . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');

        // Blank form fields
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI PRIBADI', 0, 1, 'L', true);

        $this->addBlankFormField($pdf, 'Nama Lengkap:');
        $this->addBlankFormField($pdf, 'NISN:');
        $this->addBlankFormField($pdf, 'NIK:');
        $this->addBlankFormField($pdf, 'Jenis Kelamin:');
        $this->addBlankFormField($pdf, 'Tempat Lahir:');
        $this->addBlankFormField($pdf, 'Tanggal Lahir:');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI KONTAK', 0, 1, 'L', true);

        $this->addBlankFormField($pdf, 'Email:');
        $this->addBlankFormField($pdf, 'No. Telepon:');
        $this->addBlankFormField($pdf, 'Alamat:');
        $this->addBlankFormField($pdf, 'Kota/Kabupaten:');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->ln(5);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(0, 8, 'INFORMASI ORANG TUA/WALI', 0, 1, 'L', true);

        $this->addBlankFormField($pdf, 'Nama:');
        $this->addBlankFormField($pdf, 'Email:');
        $this->addBlankFormField($pdf, 'No. Telepon:');

        // Footer
        $pdf->ln(10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 8, 'Catatan: Cetak dan isi formulir ini secara lengkap sebelum mendaftar secara online.', 0, 1, 'C');

        if ($download) {
            $filename = 'Admission_Form_Template.pdf';
            return response($pdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        return $pdf->Output('', 'S');
    }

    /**
     * Stream PDF to browser for viewing
     *
     * @param Registrant $registrant
     * @return Response
     */
    public function streamFilledPDF(Registrant $registrant)
    {
        return $this->generateFilledPDF($registrant, false);
    }

    /**
     * Helper: Add form fields to PDF
     */
    protected function addFormField(TCPDF $pdf, string $label, ?string $value = null): void
    {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 7, $label, 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, $value ?? '-', 0, 1);
    }

    /**
     * Helper: Add blank form fields to PDF
     */
    protected function addBlankFormField(TCPDF $pdf, string $label): void
    {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 7, $label, 0, 0);
        $pdf->SetFont('helvetica', '', 10);

        // Draw a line for writing space
        $pdf->SetDrawColor(100, 100, 100);
        $x = $pdf->GetX();
        $y = $pdf->GetY() + 3;
        $pdf->Line($x, $y, $pdf->GetPageWidth() - 15, $y);
        $pdf->ln(8);
    }

    /**
     * Helper: Add PDF header with school info and logo
     */
    protected function addPDFHeader(TCPDF $pdf, array $schoolInfo): void
    {
        // School logo (if exists)
        if (file_exists($schoolInfo['logo_path'])) {
            $pdf->Image($schoolInfo['logo_path'], 15, 10, 15);
        }

        // School information
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->ln(5);
        $pdf->Cell(0, 8, strtoupper($schoolInfo['name']), 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'NPSN: ' . $schoolInfo['npsn'], 0, 1, 'C');
        $pdf->Cell(0, 6, $schoolInfo['address'], 0, 1, 'C');
        $pdf->Cell(0, 6, 'Telp: ' . $schoolInfo['phone'] . ' | Email: ' . $schoolInfo['email'], 0, 1, 'C');

        // Divider line
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->ln(2);
        $pdf->Line(15, $pdf->GetY(), $pdf->GetPageWidth() - 15, $pdf->GetY());
        $pdf->ln(3);
    }

    /**
     * Translate application status to Indonesian
     */
    protected function translateApplicationStatus(string $status): string
    {
        $translations = [
            'draft' => 'Draft (Belum Selesai)',
            'submitted' => 'Dikirim',
            'under_review' => 'Sedang Diproses',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
            'confirmed' => 'Dikonfirmasi',
            'enrolled' => 'Terdaftar',
        ];

        return $translations[$status] ?? $status;
    }

    /**
     * Translate selection status to Indonesian
     */
    protected function translateSelectionStatus(string $status): string
    {
        $translations = [
            'pending' => 'Menunggu',
            'passed' => 'Lulus Seleksi',
            'failed' => 'Tidak Lulus Seleksi',
        ];

        return $translations[$status] ?? $status;
    }

    /**
     * Save PDF to storage
     */
    protected function savePDFToStorage(TCPDF $pdf, Registrant $registrant): string
    {
        $filename = sprintf('admission_%s_%s.pdf',
            $registrant->registration_number,
            now()->timestamp
        );

        $path = storage_path('app/admission-forms/' . $filename);

        // Create directory if it doesn't exist
        @mkdir(dirname($path), 0755, true);

        $pdf->Output($path, 'F');

        return 'admission-forms/' . $filename;
    }
}
