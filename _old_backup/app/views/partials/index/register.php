<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <style>
        /* === RESET & OVERRIDE BOOTSTRAP === */
        #c-register-root {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: -20px -15px -60px; /* Counteract default padding from PHPRad */
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(37, 99, 235, 0.95) 100%), url('../images/bg.jpg') center/cover fixed;
            position: relative;
            z-index: 10;
        }

        .c-card {
            background-color: #FFFFFF !important;
            width: 100% !important;
            max-width: 460px !important;
            border-radius: 16px !important;
            padding: 40px 32px !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
            animation: c-slideUp 0.5s ease-out forwards;
        }

        @keyframes c-slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .c-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .c-header h1 {
            color: #1E3A8A !important;
            font-size: 26px !important;
            font-weight: 800 !important;
            margin: 0 0 8px 0 !important;
            line-height: 1.2 !important;
        }

        .c-header p {
            color: #6B7280 !important;
            font-size: 14px !important;
            margin: 0 !important;
        }

        .c-header a {
            color: #2563EB !important;
            text-decoration: none !important;
            font-weight: 700 !important;
        }

        .c-header a:hover {
            text-decoration: underline !important;
        }

        .c-field {
            margin-bottom: 20px !important;
            text-align: left !important;
            position: relative;
        }

        .c-field label {
            display: block !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            color: #374151 !important;
            margin-bottom: 8px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .c-field label span {
            color: #DC2626 !important;
        }

        .c-input {
            width: 100% !important;
            font-size: 15px !important;
            padding: 12px 16px !important;
            border: 2px solid #E5E7EB !important;
            border-radius: 8px !important;
            outline: none !important;
            color: #111827 !important;
            background: #F9FAFB !important;
            transition: all 0.25s ease !important;
            box-sizing: border-box !important;
            height: 48px !important;
        }

        .c-input:focus {
            border-color: #2563EB !important;
            background: #FFFFFF !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
        }

        .c-input.c-error {
            border-color: #EF4444 !important;
            background: #FEF2F2 !important;
        }

        .c-input.c-success {
            border-color: #10B981 !important;
        }

        .c-msg {
            color: #EF4444 !important;
            font-size: 12px !important;
            margin-top: 6px !important;
            display: none;
            font-weight: 600 !important;
        }
        
        .c-input.c-error ~ .c-msg {
            display: block !important;
        }

        .c-icon {
            position: absolute !important;
            right: 14px !important;
            top: 41px !important; /* Adjusted for label */
            background: none !important;
            border: none !important;
            cursor: pointer !important;
            color: #9CA3AF !important;
            padding: 0 !important;
            display: flex !important;
            outline: none !important;
        }

        .c-icon:hover {
            color: #4B5563 !important;
        }

        .c-check {
            position: absolute !important;
            right: 42px !important;
            top: 41px !important;
            color: #10B981 !important;
            display: none;
        }

        .c-pwd-meta {
            margin-top: 12px !important;
            display: none;
        }

        .c-chips {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 6px !important;
            margin-bottom: 10px !important;
        }

        .c-chip {
            background-color: #F3F4F6 !important;
            color: #9CA3AF !important;
            font-size: 11px !important;
            padding: 4px 10px !important;
            border-radius: 100px !important;
            font-weight: 700 !important;
            transition: all 0.3s ease !important;
        }

        .c-chip.c-active {
            background-color: #10B981 !important;
            color: #FFFFFF !important;
        }

        .c-prog-bg {
            height: 4px !important;
            background-color: #F3F4F6 !important;
            border-radius: 4px !important;
            overflow: hidden !important;
        }

        .c-prog-bar {
            height: 100% !important;
            width: 0% !important;
            background-color: #EF4444 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .c-btn {
            width: 100% !important;
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
            color: #FFFFFF !important;
            font-size: 16px !important;
            font-weight: 800 !important;
            border: none !important;
            border-radius: 8px !important;
            height: 52px !important;
            cursor: pointer !important;
            margin-top: 16px !important;
            transition: all 0.25s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important;
        }

        .c-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4) !important;
        }

        .c-btn.c-disabled {
            background: #D1D5DB !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            transform: none !important;
        }
    </style>

    <div id="c-register-root">
        <div class="c-card">
            <?php $this :: display_page_errors(); ?>
            <div class="c-header">
                <h1>Buat Akun Baru</h1>
                <p>Sudah punya akun? <a href="<?php print_link('') ?>">Login di sini</a></p>
            </div>
            
            <form id="pengguna-userregister-form" role="form" action="<?php print_link("index/register?csrf_token=$csrf_token") ?>" method="post">
                <!-- Username -->
                <div class="c-field">
                    <label>Username <span>*</span></label>
                    <input id="ctrl-username" class="c-input" type="text" name="username" placeholder="Enter Username" required value="<?php echo $this->set_field_value('username',""); ?>" autocomplete="off">
                    <div class="c-msg">Username minimal 4 karakter tanpa spasi.</div>
                </div>

                <!-- Nama Lengkap -->
                <div class="c-field">
                    <label>Nama Lengkap <span>*</span></label>
                    <input id="ctrl-nama" class="c-input" type="text" name="nama" placeholder="Masukkan Nama Lengkap Sesuai KTP" required value="<?php echo $this->set_field_value('nama',""); ?>" autocomplete="off">
                    <div class="c-msg">Nama hanya boleh berisi huruf dan spasi.</div>
                </div>

                <!-- Email -->
                <div class="c-field">
                    <label>Email <span>*</span></label>
                    <input id="ctrl-email" class="c-input" type="email" name="email" placeholder="Enter Email" required value="<?php echo $this->set_field_value('email',""); ?>" autocomplete="off">
                    <div class="c-msg">Format email tidak valid.</div>
                </div>

                <!-- Password -->
                <div class="c-field">
                    <label>Password <span>*</span></label>
                    <input id="ctrl-password" class="c-input" type="password" name="password" placeholder="Enter Password" required value="<?php echo $this->set_field_value('password',""); ?>">
                    <button type="button" class="c-icon" id="toggle-pwd"><i class="fa fa-eye"></i></button>
                    <div class="c-msg" id="err-pwd">Password belum memenuhi syarat.</div>
                    
                    <div class="c-pwd-meta" id="pwd-container">
                        <div class="c-chips">
                            <span class="c-chip" id="req-len">6+ Karakter</span>
                            <span class="c-chip" id="req-upp">Huruf Kapital</span>
                            <span class="c-chip" id="req-num">Angka</span>
                            <span class="c-chip" id="req-sym">Simbol</span>
                        </div>
                        <div class="c-prog-bg">
                            <div class="c-prog-bar" id="pwd-bar"></div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="c-field">
                    <label>Confirm Password <span>*</span></label>
                    <input id="ctrl-confirm" class="c-input" type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fa fa-check c-check" id="match-check"></i>
                    <button type="button" class="c-icon" id="toggle-confirm"><i class="fa fa-eye"></i></button>
                    <div class="c-msg" id="err-confirm">Password tidak cocok.</div>
                </div>

                <button type="submit" class="c-btn c-disabled" id="btn-submit" disabled>
                    <span id="btn-text">Daftar Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputs = {
        username: document.getElementById('ctrl-username'),
        nama: document.getElementById('ctrl-nama'),
        email: document.getElementById('ctrl-email'),
        password: document.getElementById('ctrl-password'),
        confirm: document.getElementById('ctrl-confirm')
    };

    const touched = {
        username: false, nama: false, email: false, password: false, confirm: false
    };

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexNama = /^[a-zA-Z\s]+$/;
    
    const pwdReqs = {
        len: val => val.length >= 6,
        upp: val => /[A-Z]/.test(val),
        num: val => /[0-9]/.test(val),
        sym: val => /[!@#$%^&*(),.?":{}|<>]/.test(val)
    };

    const pwdContainer = document.getElementById('pwd-container');
    const progressBar = document.getElementById('pwd-bar');
    const matchCheck = document.getElementById('match-check');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');

    const validateField = (field, value) => {
        switch(field) {
            case 'username': return value.length >= 4 && !/\s/.test(value);
            case 'nama': return value.length > 0 && regexNama.test(value);
            case 'email': return regexEmail.test(value);
            case 'password': return pwdReqs.len(value) && pwdReqs.upp(value) && pwdReqs.num(value) && pwdReqs.sym(value);
            case 'confirm': return value.length > 0 && value === inputs.password.value;
            default: return false;
        }
    };

    const checkFormValidity = () => {
        const isFormValid = Object.keys(inputs).every(key => validateField(key, inputs[key].value));
        if(isFormValid) {
            btnSubmit.classList.remove('c-disabled');
            btnSubmit.removeAttribute('disabled');
        } else {
            btnSubmit.classList.add('c-disabled');
            btnSubmit.setAttribute('disabled', 'disabled');
        }
    };

    const updateUI = (field) => {
        const el = inputs[field];
        const isValid = validateField(field, el.value);
        
        if (touched[field]) {
            if (!isValid) {
                el.classList.add('c-error');
                el.classList.remove('c-success');
            } else {
                el.classList.remove('c-error');
                el.classList.add('c-success');
            }
        }

        if (field === 'confirm' || field === 'password') {
            if (inputs.confirm.value.length > 0) {
                if (inputs.confirm.value === inputs.password.value) {
                    matchCheck.style.display = 'block';
                } else {
                    matchCheck.style.display = 'none';
                    if(touched.confirm) {
                        inputs.confirm.classList.add('c-error');
                        inputs.confirm.classList.remove('c-success');
                    }
                }
            } else {
                matchCheck.style.display = 'none';
            }
        }
        
        checkFormValidity();
    };

    Object.keys(inputs).forEach(key => {
        const el = inputs[key];
        el.addEventListener('blur', () => {
            touched[key] = true;
            updateUI(key);
        });

        el.addEventListener('input', () => {
            if (touched[key]) updateUI(key);
            
            if (key === 'password') {
                handlePasswordStrength(el.value);
                if(inputs.confirm.value.length > 0) updateUI('confirm'); 
            }
        });
    });

    const handlePasswordStrength = (val) => {
        if (val.length > 0) pwdContainer.style.display = 'block';
        else pwdContainer.style.display = 'none';

        let score = 0;
        Object.keys(pwdReqs).forEach(req => {
            const chip = document.getElementById('req-' + req);
            if (pwdReqs[req](val)) {
                chip.classList.add('c-active');
                score++;
            } else {
                chip.classList.remove('c-active');
            }
        });

        progressBar.style.width = (score / 4) * 100 + '%';
        if (score <= 1) progressBar.style.backgroundColor = '#EF4444';
        else if (score === 2 || score === 3) progressBar.style.backgroundColor = '#F59E0B';
        else if (score === 4) progressBar.style.backgroundColor = '#10B981';
    };

    const setupToggle = (btnId, inputId) => {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        if(btn && input) {
            btn.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    btn.querySelector('i').classList.remove('fa-eye');
                    btn.querySelector('i').classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    btn.querySelector('i').classList.remove('fa-eye-slash');
                    btn.querySelector('i').classList.add('fa-eye');
                }
            });
        }
    }
    
    setupToggle('toggle-pwd', 'ctrl-password');
    setupToggle('toggle-confirm', 'ctrl-confirm');

    const form = document.getElementById('pengguna-userregister-form');
    form.addEventListener('submit', function(e) {
        const isFormValid = Object.keys(inputs).every(key => validateField(key, inputs[key].value));
        if(isFormValid) {
            btnText.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
            btnSubmit.classList.add('c-disabled');
            // Allow natural submit
        } else {
            e.preventDefault();
        }
    });
});
</script>
                        
