<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MassAnnouncement extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('SettingsModel');

		if ($this->session->userdata('logged_in') !== true) {
			redirect('login');
		}
	}

	public function settings()
	{
		if ($this->session->userdata('level') !== 'Super Admin') {
			$this->session->set_flashdata('danger', 'Only Super Admin can manage the mass email setup.');
			redirect('Settings/schoolInfo');
			return;
		}

		$returnTo = $this->sanitizeReturnTo($this->input->post('return_to', true));
		if (strtoupper((string) $this->input->method()) !== 'POST') {
			redirect($returnTo);
			return;
		}

		$postedTransport = trim((string) $this->input->post('transport', true));
		$postedSenderEmail = trim((string) $this->input->post('sender_email', true));
		$postedBrevoApiUrl = trim((string) $this->input->post('brevo_api_url', true));
		$postedBrevoApiKey = trim((string) $this->input->post('brevo_api_key', false));
		$postedSmtpHost = trim((string) $this->input->post('smtp_host', true));
		$postedSmtpPort = trim((string) $this->input->post('smtp_port', true));
		$postedSmtpCrypto = trim((string) $this->input->post('smtp_crypto', true));
		$postedSmtpUser = trim((string) $this->input->post('smtp_user', true));
		$postedSmtpPass = trim((string) $this->input->post('smtp_pass', false));

		$oldInput = [
			'transport' => $postedTransport,
			'sender_email' => $postedSenderEmail,
			'brevo_api_url' => $postedBrevoApiUrl,
			'brevo_api_key' => $postedBrevoApiKey,
			'smtp_host' => $postedSmtpHost,
			'smtp_port' => $postedSmtpPort,
			'smtp_crypto' => $postedSmtpCrypto,
			'smtp_user' => $postedSmtpUser,
			'smtp_pass' => $postedSmtpPass,
		];

		$errors = [];
		if (!in_array($postedTransport, ['brevo_api', 'smtp'], true)) {
			$errors[] = 'Choose a valid transport.';
		}

		if ($postedSenderEmail === '' || !filter_var($postedSenderEmail, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Sender email must be a valid email address.';
		}

		if ($postedTransport === 'brevo_api') {
			if ($postedBrevoApiUrl === '' || !filter_var($postedBrevoApiUrl, FILTER_VALIDATE_URL)) {
				$errors[] = 'Brevo API URL must be a valid URL.';
			}

			if ($postedBrevoApiKey === '') {
				$errors[] = 'Brevo API key is required when Brevo API transport is selected.';
			}
		}

		if ($postedTransport === 'smtp') {
			if ($postedSmtpHost === '') {
				$errors[] = 'SMTP host is required when SMTP transport is selected.';
			}

			if ($postedSmtpPort === '' || filter_var($postedSmtpPort, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
				$errors[] = 'SMTP port must be a valid positive number.';
			}

			if (!in_array($postedSmtpCrypto, ['tls', 'ssl', ''], true)) {
				$errors[] = 'SMTP crypto must be tls, ssl, or none.';
			}

			if ($postedSmtpUser === '') {
				$errors[] = 'SMTP user is required when SMTP transport is selected.';
			}

			if ($postedSmtpPass === '') {
				$errors[] = 'SMTP password is required when SMTP transport is selected.';
			}
		}

		if (!empty($errors)) {
			$this->session->set_flashdata('mass_email_settings_old', $oldInput);
			$this->session->set_flashdata('open_panel', 'mass_email');
			$this->session->set_flashdata('danger', implode('<br>', $errors));
			redirect($returnTo);
			return;
		}

		$schoolSettings = $this->SettingsModel->get_settings();
		$existingSettings = $this->SettingsModel->getMassAnnouncementEmailSettings();
		$senderName = trim((string) ($schoolSettings->SchoolName ?? 'School Records Management System'));

		$saveData = [
			'transport' => $postedTransport,
			'sender_email' => $postedSenderEmail,
			'sender_name' => $senderName,
			'brevo_api_url' => $postedBrevoApiUrl !== '' ? $postedBrevoApiUrl : 'https://api.brevo.com/v3/smtp/email',
			'brevo_api_key' => $postedBrevoApiKey,
			'smtp_host' => $postedSmtpHost !== '' ? $postedSmtpHost : 'smtp-relay.brevo.com',
			'smtp_port' => (int) ($postedSmtpPort !== '' ? $postedSmtpPort : 587),
			'smtp_crypto' => $postedSmtpCrypto,
			'smtp_user' => $postedSmtpUser,
			'smtp_pass' => $postedSmtpPass,
			'is_active' => 1,
		];

		$saved = $this->SettingsModel->saveMassAnnouncementEmailSettings($saveData, (int) ($existingSettings->id ?? 0));
		if ($saved) {
			$this->session->set_flashdata('success', 'Mass email setup saved successfully.');
		} else {
			$this->session->set_flashdata('mass_email_settings_old', $oldInput);
			$this->session->set_flashdata('open_panel', 'mass_email');
			$this->session->set_flashdata('danger', 'Unable to save the mass email setup.');
		}

		redirect($returnTo);
	}

	private function sanitizeReturnTo($returnTo)
	{
		$returnTo = trim((string) $returnTo);
		if ($returnTo === '') {
			return 'Settings/schoolInfo?panel=mass_email';
		}

		$parts = parse_url($returnTo);
		if ($parts === false || !empty($parts['scheme']) || !empty($parts['host'])) {
			return 'Settings/schoolInfo?panel=mass_email';
		}

		$path = trim((string) ($parts['path'] ?? ''), '/');
		if ($path === '') {
			$path = 'Settings/schoolInfo';
		}

		$query = isset($parts['query']) && $parts['query'] !== '' ? '?' . $parts['query'] : '';
		return $path . $query;
	}
}
