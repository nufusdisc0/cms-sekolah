@extends('layouts.backend')

@section('content')
<div class="container">
    <h2 class="mb-4">Security Settings</h2>

    <form action="{{ route('settings.update', 'security') }}" method="POST" class="card">
        @csrf
        @method('POST')

        <div class="card-body">
            <!-- reCAPTCHA Section -->
            <div class="mb-5 pb-5 border-bottom">
                <h4 class="mb-4"><i class="fas fa-shield-alt me-2"></i>reCAPTCHA v3 Configuration</h4>

                <!-- Enable reCAPTCHA -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled"
                               value="1" {{ old('recaptcha_enabled', $settings['recaptcha_enabled']->setting_value ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="recaptcha_enabled">
                            Enable reCAPTCHA v3 Protection
                        </label>
                        <small class="d-block text-muted mt-2">
                            When enabled, all admission form submissions will be protected by invisible reCAPTCHA v3 verification.
                        </small>
                    </div>
                </div>

                <!-- Site Key -->
                <div class="mb-4">
                    <label for="recaptcha_site_key" class="form-label">reCAPTCHA Site Key</label>
                    <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key"
                           value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key']->setting_value ?? '') }}"
                           placeholder="6Lc...">
                    <small class="text-muted d-block mt-2">
                        Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Console</a>
                    </small>
                </div>

                <!-- Secret Key -->
                <div class="mb-4">
                    <label for="recaptcha_secret_key" class="form-label">reCAPTCHA Secret Key</label>
                    <input type="password" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key"
                           value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key']->setting_value ?? '') }}"
                           placeholder="6Lc...">
                    <small class="text-muted d-block mt-2">
                        Keep this key secure. Never share it publicly.
                    </small>
                </div>

                <!-- Score Threshold -->
                <div class="mb-4">
                    <label for="recaptcha_score_threshold" class="form-label">
                        Score Threshold (0.0 - 1.0)
                        <span class="badge bg-secondary" id="threshold_value">
                            {{ old('recaptcha_score_threshold', $settings['recaptcha_score_threshold']->setting_value ?? 0.5) }}
                        </span>
                    </label>
                    <input type="range" class="form-range" id="recaptcha_score_threshold" name="recaptcha_score_threshold"
                           min="0.0" max="1.0" step="0.1"
                           value="{{ old('recaptcha_score_threshold', $settings['recaptcha_score_threshold']->setting_value ?? 0.5) }}"
                           onchange="document.getElementById('threshold_value').textContent = this.value">
                    <small class="text-muted d-block mt-2">
                        Higher values = stricter verification. 0.5 is recommended (0 = always fail, 1 = always pass).
                    </small>
                </div>
            </div>

            <!-- Email Notifications Section -->
            <div class="mb-5 pb-5 border-bottom">
                <h4 class="mb-4"><i class="fas fa-envelope me-2"></i>Email Notifications</h4>

                <!-- Enable Notifications -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="notify_admission_submissions"
                               name="notify_admission_submissions" value="1"
                               {{ old('notify_admission_submissions', $settings['notify_admission_submissions']->setting_value ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="notify_admission_submissions">
                            Send Email on Admission Form Submission
                        </label>
                        <small class="d-block text-muted mt-2">
                            Automatically send email notification to admin when a new admission form is submitted.
                        </small>
                    </div>
                </div>

                <!-- Admin Email -->
                <div class="mb-4">
                    <label for="admin_notification_email" class="form-label">Admin Notification Email</label>
                    <input type="email" class="form-control" id="admin_notification_email" name="admin_notification_email"
                           value="{{ old('admin_notification_email', $settings['admin_notification_email']->setting_value ?? '') }}"
                           placeholder="admin@school.edu">
                    <small class="text-muted d-block mt-2">
                        Email address where form submission notifications will be sent.
                    </small>
                </div>

                <!-- Include Documents -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="include_documents_in_email"
                               name="include_documents_in_email" value="1"
                               {{ old('include_documents_in_email', $settings['include_documents_in_email']->setting_value ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="include_documents_in_email">
                            Include Uploaded Documents in Email
                        </label>
                        <small class="d-block text-muted mt-2">
                            Attach applicant's uploaded documents to the notification email (may increase email size).
                        </small>
                    </div>
                </div>
            </div>

            <!-- IP Ban Settings Section -->
            <div class="mb-5 pb-5 border-bottom">
                <h4 class="mb-4"><i class="fas fa-ban me-2"></i>IP Ban & Login Security</h4>

                <!-- Enable IP Bans -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="ip_ban_enabled" name="ip_ban_enabled"
                               value="1" {{ old('ip_ban_enabled', $settings['ip_ban_enabled']->setting_value ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ip_ban_enabled">
                            Enable IP-Based Login Ban System
                        </label>
                        <small class="d-block text-muted mt-2">
                            Automatically ban IP addresses after multiple failed login attempts.
                        </small>
                    </div>
                </div>

                <!-- Attempt Limit -->
                <div class="mb-4">
                    <label for="login_attempt_limit" class="form-label">Failed Login Attempts Before Ban</label>
                    <input type="number" class="form-control" id="login_attempt_limit" name="login_attempt_limit"
                           min="1" max="20" step="1"
                           value="{{ old('login_attempt_limit', $settings['login_attempt_limit']->setting_value ?? 3) }}">
                    <small class="text-muted d-block mt-2">
                        Number of failed login attempts allowed before IP is banned (recommended: 3).
                    </small>
                </div>

                <!-- Ban Duration -->
                <div class="mb-4">
                    <label for="login_ban_duration_hours" class="form-label">Ban Duration (hours)</label>
                    <input type="number" class="form-control" id="login_ban_duration_hours" name="login_ban_duration_hours"
                           min="1" max="720" step="1"
                           value="{{ old('login_ban_duration_hours', $settings['login_ban_duration_hours']->setting_value ?? 24) }}">
                    <small class="text-muted d-block mt-2">
                        How long to ban an IP after exceeding attempt limit (recommended: 24 hours).
                    </small>
                </div>

                <!-- Email on Ban -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="email_on_ip_ban" name="email_on_ip_ban"
                               value="1" {{ old('email_on_ip_ban', $settings['email_on_ip_ban']->setting_value ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="email_on_ip_ban">
                            Email Admin When IP is Banned
                        </label>
                        <small class="d-block text-muted mt-2">
                            Send notification to admin when an IP address is automatically banned.
                        </small>
                    </div>
                </div>

                <!-- Manage Bans Link -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <a href="{{ route('backend.security.ip-bans') }}" class="alert-link">View and manage active IP bans</a>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Save Security Settings
            </button>
            <a href="{{ route('settings.index', 'general') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Update threshold display when slider moves
    document.getElementById('recaptcha_score_threshold').addEventListener('change', function() {
        document.getElementById('threshold_value').textContent = this.value;
    });
</script>
@endsection
