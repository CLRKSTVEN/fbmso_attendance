(function() {
  document.addEventListener('click', function(event) {
    var btn = event.target.closest('.toggle-pass[data-target]');
    if (!btn) return;

    var input = document.querySelector(btn.getAttribute('data-target'));
    if (!input) return;

    var show = input.type === 'password';
    input.type = show ? 'text' : 'password';

    var icon = btn.querySelector('i');
    if (icon) {
      icon.className = show ? 'fa fa-eye-slash' : 'fa fa-eye';
    }
  });
})();

(function() {
  var form = document.querySelector('form[action*="Login/auth"]');
  if (!form) return;

  form.addEventListener('submit', function() {
    var u = document.getElementById('username');
    var p = document.getElementById('password');

    if (u && typeof u.value === 'string') {
      u.value = u.value.replace(/\u00a0/g, ' ').replace(/[\u200B-\u200D\uFEFF\u00AD]/g, '').replace(/\s+/g, ' ').trim();
    }
    if (p && typeof p.value === 'string') {
      p.value = p.value.replace(/\u00a0/g, ' ').replace(/[\u200B-\u200D\uFEFF\u00AD]/g, '').trim();
    }
  });
})();

(function() {
  var state = window.homeLoginState || {};
  var forgotInfo = state.forgotInfo || '';

  if (!forgotInfo || !window.Swal) return;

  Swal.fire({
    icon: 'success',
    title: 'Password Updated',
    text: forgotInfo,
    confirmButtonColor: '#3b5fd4'
  });
})();

(function($) {
  var state = window.homeLoginState || {};
  var form = document.getElementById('resetPassword');
  if (!form) return;

  var emailInput = document.getElementById('reset-email');
  var identifierInput = document.getElementById('reset-identifier');
  var passwordFields = document.getElementById('reset-password-fields');
  var newPasswordInput = document.getElementById('reset-new-password');
  var confirmPasswordInput = document.getElementById('reset-confirm-password');
  var submitButton = document.getElementById('resetSubmit');
  var statusBox = document.getElementById('reset-status');
  var verified = !!state.forgotAccountVerified;
  var initialEmail = normalizeEmail(state.forgotEmail || (emailInput ? emailInput.value : ''));
  var initialIdentifier = normalizeIdentifier(state.forgotIdentifier || (identifierInput ? identifierInput.value : ''));
  var lastVerifiedKey = verified ? buildIdentityKey(initialEmail, initialIdentifier) : '';
  var lastEmailChecked = '';
  var lastEmailExists = null;
  var lastEmailActive = null;
  var lastEmailMessage = '';
  var checkTimer = null;
  var activeRequest = null;

  function normalizeEmail(value) {
    return String(value || '')
      .replace(/\u00a0/g, ' ')
      .replace(/[\u200B-\u200D\uFEFF\u00AD]/g, '')
      .replace(/\s+/g, '')
      .trim()
      .toLowerCase();
  }

  function normalizePassword(value) {
    return String(value || '')
      .replace(/\u00a0/g, ' ')
      .replace(/[\u200B-\u200D\uFEFF\u00AD]/g, '')
      .trim();
  }

  function normalizeIdentifier(value) {
    return String(value || '')
      .replace(/\u00a0/g, ' ')
      .replace(/[\u200B-\u200D\uFEFF\u00AD]/g, '')
      .replace(/\s+/g, ' ')
      .trim();
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function buildIdentityKey(email, identifier) {
    return normalizeEmail(email) + '|' + normalizeIdentifier(identifier).toLowerCase();
  }

  function setStatus(message, type) {
    if (!statusBox) return;

    statusBox.textContent = message || '';
    statusBox.classList.remove('is-error', 'is-success');

    if (message && type === 'success') {
      statusBox.classList.add('is-success');
    } else if (message && type === 'error') {
      statusBox.classList.add('is-error');
    }

    statusBox.hidden = !message;
  }

  function setPasswordFieldsVisible(show) {
    verified = !!show;

    if (passwordFields) {
      passwordFields.hidden = !show;
    }

    if (newPasswordInput) {
      newPasswordInput.required = !!show;
      if (!show) newPasswordInput.value = '';
    }

    if (confirmPasswordInput) {
      confirmPasswordInput.required = !!show;
      if (!show) confirmPasswordInput.value = '';
    }

    if (submitButton) {
      submitButton.hidden = !show;
      submitButton.disabled = !show;
    }
  }

  function normalizeIdentityInputs() {
    var email = normalizeEmail(emailInput ? emailInput.value : '');
    var identifier = normalizeIdentifier(identifierInput ? identifierInput.value : '');

    if (emailInput) {
      emailInput.value = email;
    }

    if (identifierInput) {
      identifierInput.value = identifier;
    }

    return {
      email: email,
      identifier: identifier
    };
  }

  function clearScheduledCheck() {
    if (checkTimer) {
      clearTimeout(checkTimer);
      checkTimer = null;
    }
  }

  function abortActiveCheck() {
    if (activeRequest && typeof activeRequest.abort === 'function') {
      activeRequest.abort();
    }
    activeRequest = null;
  }

  function resetVerificationState() {
    if (verified) {
      setPasswordFieldsVisible(false);
    }
  }

  function clearEmailCacheIfChanged(email) {
    if (email !== lastEmailChecked) {
      lastEmailChecked = '';
      lastEmailExists = null;
      lastEmailActive = null;
      lastEmailMessage = '';
      lastVerifiedKey = '';
    }
  }

  function performAccountCheck(identity, immediateFocusTarget) {
    var email = identity.email;
    var identifier = identity.identifier;
    var identityKey = buildIdentityKey(email, identifier);

    clearScheduledCheck();

    if (!email && !identifier) {
      abortActiveCheck();
      resetVerificationState();
      setStatus('', '');
      return;
    }

    if (!email) {
      abortActiveCheck();
      resetVerificationState();
      setStatus('', '');
      return;
    }

    clearEmailCacheIfChanged(email);

    if (!isValidEmail(email)) {
      abortActiveCheck();
      resetVerificationState();
      setStatus('Please enter a valid email address.', 'error');
      if (immediateFocusTarget && emailInput) emailInput.focus();
      return;
    }

    if (!identifier) {
      if (lastEmailChecked === email && lastEmailExists === true) {
        setStatus(lastEmailMessage || 'Email exists. Enter your username or student ID.', lastEmailActive ? 'success' : 'error');
        return;
      }

      if (lastEmailChecked === email && lastEmailExists === false) {
        setStatus(lastEmailMessage || 'Email does not exist.', 'error');
        return;
      }

      if (!$ || typeof $.ajax !== 'function') {
        setStatus('Email check is unavailable right now. Please try again.', 'error');
        return;
      }

      abortActiveCheck();
      resetVerificationState();
      setStatus('Checking email...', '');

      activeRequest = $.ajax({
        url: state.checkResetEmailUrl || form.getAttribute('data-check-url'),
        method: 'POST',
        dataType: 'json',
        data: { email: email }
      }).done(function(response) {
        lastEmailChecked = email;
        lastEmailExists = !!(response && response.email_exists);
        lastEmailActive = !!(response && response.account_active);
        lastEmailMessage = (response && response.message) || (lastEmailExists ? 'Email exists. Enter your username or student ID.' : 'Email does not exist.');
        setStatus(lastEmailMessage, (lastEmailExists && lastEmailActive) ? 'success' : 'error');
      }).fail(function(xhr, textStatus) {
        if (textStatus === 'abort') {
          return;
        }

        lastEmailChecked = '';
        lastEmailExists = null;
        lastEmailActive = null;
        lastEmailMessage = '';
        setStatus('Unable to check email right now. Please try again.', 'error');
      }).always(function() {
        activeRequest = null;
      });
      return;
    }

    if (lastVerifiedKey && identityKey === lastVerifiedKey) {
      setPasswordFieldsVisible(true);
      setStatus('Account verified. You can set a new password now.', 'success');
      return;
    }

    if (!$ || typeof $.ajax !== 'function') {
      resetVerificationState();
      setStatus('Account check is unavailable right now. Please try again.', 'error');
      return;
    }

    abortActiveCheck();
    resetVerificationState();
    setStatus('Checking account...', '');

    activeRequest = $.ajax({
      url: state.checkResetEmailUrl || form.getAttribute('data-check-url'),
      method: 'POST',
      dataType: 'json',
      data: { email: email, identifier: identifier }
    }).done(function(response) {
      lastEmailChecked = email;
      lastEmailExists = !!(response && response.email_exists);
       lastEmailActive = !!(response && response.account_active);
      lastEmailMessage = (response && response.message) || '';

      if (response && response.success) {
        initialEmail = email;
        initialIdentifier = identifier;
        lastVerifiedKey = identityKey;
        setPasswordFieldsVisible(true);
        setStatus(response.message || 'Account verified. You can set a new password now.', 'success');
        if (immediateFocusTarget && newPasswordInput) newPasswordInput.focus();
        return;
      }

      lastVerifiedKey = '';
      setPasswordFieldsVisible(false);
      setStatus((response && response.message) || 'Email exists, but it does not match that username or student ID.', 'error');
      if (immediateFocusTarget) {
        if (identifierInput && identifier) {
          identifierInput.focus();
        } else if (emailInput) {
          emailInput.focus();
        }
      }
    }).fail(function(xhr, textStatus) {
      if (textStatus === 'abort') {
        return;
      }

      lastVerifiedKey = '';
      setPasswordFieldsVisible(false);
      setStatus('Unable to check account right now. Please try again.', 'error');
    }).always(function() {
      activeRequest = null;
    });
  }

  function scheduleAccountCheck() {
    var identity = normalizeIdentityInputs();
    var identityKey = buildIdentityKey(identity.email, identity.identifier);

    if (!identity.email && !identity.identifier) {
      clearScheduledCheck();
      abortActiveCheck();
      resetVerificationState();
      setStatus('', '');
      return;
    }

    clearEmailCacheIfChanged(identity.email);

    if (identityKey !== lastVerifiedKey) {
      resetVerificationState();
    }

    if (!identity.email && identity.identifier) {
      clearScheduledCheck();
      abortActiveCheck();
      setStatus('Enter your registered email to continue.', 'error');
      return;
    }

    if (!identity.email) {
      clearScheduledCheck();
      abortActiveCheck();
      setStatus('', '');
      return;
    }

    if (!isValidEmail(identity.email)) {
      clearScheduledCheck();
      abortActiveCheck();
      setStatus('Please enter a valid email address.', 'error');
      return;
    }

    if (!identity.identifier) {
      clearScheduledCheck();
      abortActiveCheck();
      if (lastEmailChecked === identity.email && lastEmailExists === true) {
        setStatus(lastEmailMessage || 'Email exists. Enter your username or student ID.', lastEmailActive ? 'success' : 'error');
      } else if (lastEmailChecked === identity.email && lastEmailExists === false) {
        setStatus(lastEmailMessage || 'Email does not exist.', 'error');
      } else {
        setStatus('Checking email...', '');
      }

      checkTimer = setTimeout(function() {
        performAccountCheck(identity, false);
      }, 160);
      return;
    }

    clearScheduledCheck();
    checkTimer = setTimeout(function() {
      performAccountCheck(identity, false);
    }, 180);
  }

  if (emailInput) {
    emailInput.value = initialEmail;
  }

  if (identifierInput) {
    identifierInput.value = initialIdentifier;
  }

  setPasswordFieldsVisible(verified);

  if (state.forgotError) {
    setStatus(state.forgotError, 'error');
  }

  if (state.forgotModalOpen && window.jQuery) {
    window.jQuery('#forgotModal').modal('show');
  }

  [emailInput, identifierInput].forEach(function(input) {
    if (!input) return;

    input.addEventListener('input', scheduleAccountCheck);
    input.addEventListener('keyup', scheduleAccountCheck);
    input.addEventListener('change', scheduleAccountCheck);
    input.addEventListener('paste', function() {
      setTimeout(scheduleAccountCheck, 0);
    });
    input.addEventListener('blur', function() {
      performAccountCheck(normalizeIdentityInputs(), false);
    });
  });

  if (window.jQuery) {
    window.jQuery('#forgotModal').on('shown.bs.modal', function() {
      setTimeout(scheduleAccountCheck, 50);
      setTimeout(scheduleAccountCheck, 250);
    });
  }

  setTimeout(scheduleAccountCheck, 50);
  setTimeout(scheduleAccountCheck, 250);

  form.addEventListener('submit', function(event) {
    var identity = normalizeIdentityInputs();

    if (!verified) {
      event.preventDefault();
      performAccountCheck(identity, true);

      if (!identity.email) {
        setStatus('Please enter your registered email.', 'error');
        if (emailInput) emailInput.focus();
      } else if (!isValidEmail(identity.email)) {
        setStatus('Please enter a valid email address.', 'error');
        if (emailInput) emailInput.focus();
      } else if (!identity.identifier) {
        setStatus('Please enter your username or student ID.', 'error');
        if (identifierInput) identifierInput.focus();
      }

      return;
    }

    if (newPasswordInput) {
      newPasswordInput.value = normalizePassword(newPasswordInput.value);
    }

    if (confirmPasswordInput) {
      confirmPasswordInput.value = normalizePassword(confirmPasswordInput.value);
    }

    var newPassword = newPasswordInput ? newPasswordInput.value : '';
    var confirmPassword = confirmPasswordInput ? confirmPasswordInput.value : '';

    if (newPassword.length < 8) {
      event.preventDefault();
      setStatus('Password must be at least 8 characters.', 'error');
      if (newPasswordInput) newPasswordInput.focus();
      return;
    }

    if (newPassword !== confirmPassword) {
      event.preventDefault();
      setStatus('Passwords do not match.', 'error');
      if (confirmPasswordInput) confirmPasswordInput.focus();
    }
  });
})(window.jQuery);

(function() {
  var state = window.homeLoginState || {};
  var loginError = state.loginError || '';
  var infoMsg = state.infoMessage || '';

  if (!loginError && !infoMsg) return;

  var isErr = /invalid|incorrect|not active|failed|unauthorized|email not found/i.test(loginError || '');
  var opts = isErr ? {
    icon: 'error',
    title: 'Sign-in failed',
    text: loginError,
    confirmButtonColor: '#e74c3c'
  } : {
    icon: 'success',
    title: 'Done',
    text: infoMsg,
    confirmButtonColor: '#3b5fd4'
  };

  if (window.Swal) {
    Swal.fire(opts);
    var fb = document.getElementById('login-error-message');
    if (fb) fb.style.display = 'none';
  }
})();
