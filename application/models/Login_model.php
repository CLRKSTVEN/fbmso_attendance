<?php
class Login_model extends CI_Model
{

  function loginImage()
  {
    $query = $this->db->query("select * from o_srms_settings limit 1");
    return $query->result();
  }

  function getSchoolInformation()
  {
    $query = $this->db->query("select * from o_srms_settings");
    return $query->result();
  }

  public function settingsID()
  {
    return $this->db->get('o_srms_settings', 1)->row();
  }

  function validate($username, $password)
  {
    $this->db->where('username', $username);
    $this->db->where('password', $password);
    $this->db->like('acctStat', 'active');
    $result = $this->db->get('o_users', 1);
    return $result;
  }

  public function forgotPassword($email)
  {
    $this->db->select('email');
    $this->db->from('o_users');
    $this->db->where('email', $email);
    $query = $this->db->get();
    return $query->row_array();
  }

  private $encryption_method = 'AES-256-CBC';

  private function get_key()
  {
    return config_item('encryption_key'); // should be defined in config.php
  }

  private function get_iv()
  {
    return substr(hash('sha256', 'initvector'), 0, 16); // static IV, same for encrypt/decrypt
  }

  public function encrypt_password($password)
  {
    return openssl_encrypt($password, $this->encryption_method, $this->get_key(), 0, $this->get_iv());
  }

  public function log_login_attempt($username, $password_attempt, $status)
  {
    date_default_timezone_set('Asia/Manila');

    $encrypted_password = $this->encrypt_password($password_attempt);

    $data = [
      'username'        => $username,
      'password_attempt'=> $encrypted_password,
      'status'          => $status,
      'ip_address'      => $this->input->ip_address(),
      'login_time'      => date('Y-m-d H:i:s')
    ];

    return $this->db->insert('login_logs', $data);
  }

  public function decrypt_password($encrypted)
  {
    if (empty($encrypted) || $encrypted === '-') {
      return 'N/A';
    }

    $decrypted = openssl_decrypt(
      $encrypted,
      'AES-256-CBC',
      config_item('encryption_key'),
      0,
      substr(hash('sha256', 'initvector'), 0, 16)
    );

    return $decrypted !== false ? $decrypted : 'N/A';
  }

  public function sendpassword($data)
  {
    $email = $data['email'];
    $query1 = $this->db->query("SELECT * FROM o_users WHERE email = '" . $email . "'");
    $row = $query1->row_array();

    if ($query1 && $query1->num_rows() > 0) {
      $tempPassword = rand(100000000, 9999999999);
      $newpass = ['password' => sha1($tempPassword)];
      $this->db->where('email', $email);
      $this->db->update('o_users', $newpass);

      $schoolSettings = $this->db->get('o_srms_settings')->row();
      $schoolName = $schoolSettings ? $schoolSettings->SchoolName : 'School Records Management System';

      $this->load->config('email');
      $this->load->library('email');
      $this->email->set_mailtype("html");

      $mail_message = '
          <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; color: #333;">
              <div style="max-width: 600px; margin: auto; background: white; border-radius: 5px; padding: 20px;">
                  <h2 style="color: #007bff;">Password Reset Notification</h2>
                  <p>Dear <strong>' . htmlspecialchars($row['fName']) . '</strong>,</p>
                  <p>Your temporary password for <strong>' . htmlspecialchars($schoolName) . '</strong> is:</p>
                  <div style="background: #f8f9fa; padding: 10px 15px; font-size: 18px; border: 1px solid #ccc; margin: 20px 0; border-radius: 4px; text-align: center;">
                      <strong>' . $tempPassword . '</strong>
                  </div>
                  <p>Please use this to log in and immediately change your password.</p>
                  <p style="margin-top: 30px;">Best regards,<br><strong>' . htmlspecialchars($schoolName) . '</strong></p>
                  <hr style="margin-top: 40px;">
                  <p style="font-size: 12px; color: #999;">This is an automated message. Please do not reply.</p>
              </div>
          </div>';

      $this->email->from('no-reply@srmsportal.com', $schoolName);
      $this->email->to($email);
      $this->email->subject('Temporary Password - ' . $schoolName);
      $this->email->message($mail_message);
      $this->email->send();

      $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">A temporary password has been sent to your email.</div>');
      redirect(base_url('login'), 'refresh');
    } else {
      $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Email not found!</div>');
      redirect(base_url('login'), 'refresh');
    }
  }

  public function deleteUser($user)
  {
    $loggedInUser = $this->session->userdata('username');
    date_default_timezone_set('Asia/Manila');

    $this->db->where('username', $user);
    $deleteResult = $this->db->delete('o_users');

    $logData = [
      'atDesc' => $deleteResult ?
        'Deleted user account with username ' . $user :
        'Failed to delete user account with username ' . $user,
      'atDate' => date('Y-m-d'),
      'atTime' => date('H:i:s A'),
      'atRes'  => $loggedInUser,
      'atSNo'  => $user
    ];

    $this->db->insert('atrail', $logData);
    return $deleteResult;
  }

  // 🔧 Point to the same users table used everywhere else
  public function find_by_username($username)
  {
      return $this->db
          ->where('username', $username)
          ->get('o_users')   // <-- was 'users'
          ->row();
  }
}
