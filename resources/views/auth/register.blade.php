<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up — LibraryHub</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600|space-mono:400,700" rel="stylesheet" />
    <style>
        :root{--bg:#050714;--accent-a:#00d4ff;--accent-b:#a855f7;--muted:#9aa6c7}
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family:'Instrument Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
            background:linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1629 100%);
            color:#e6eef8;
            display:flex;align-items:center;justify-content:center;min-height:100vh;
            position:relative;overflow:hidden;padding:20px
        }

        /* Animated background elements */
        .bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:-1}
        .orb{position:absolute;border-radius:50%;opacity:0.1;animation:float 20s infinite ease-in-out}
        .orb-1{width:400px;height:400px;background:radial-gradient(circle, #00d4ff, transparent);top:-100px;left:-100px}
        .orb-2{width:300px;height:300px;background:radial-gradient(circle, #a855f7, transparent);bottom:-50px;right:-50px;animation-delay:5s}
        @keyframes float{0%, 100%{transform:translate(0, 0)}50%{transform:translate(30px, 30px)}}

        .container{max-width:520px;width:100%}
        .card{background:rgba(10, 14, 39, 0.7);border:2px solid rgba(0, 212, 255, 0.2);border-radius:16px;padding:40px;backdrop-filter:blur(10px);box-shadow:0 0 60px rgba(0,212,255,0.1);max-height:90vh;overflow-y:auto}

        .header{text-align:center;margin-bottom:30px}
        .logo{font-size:2.5rem;font-weight:800;background:linear-gradient(135deg,var(--accent-a),var(--accent-b));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px}
        .tagline{color:var(--muted);font-size:0.95rem;letter-spacing:1px}

        .form-group{margin-bottom:16px}
        label{display:block;font-weight:700;margin-bottom:6px;color:#e6eef8;font-size:0.95rem}
        input[type="text"], input[type="email"], input[type="password"], textarea{width:100%;padding:11px;border:1px solid rgba(0,212,255,0.2);border-radius:10px;background:rgba(0,212,255,0.05);color:#e6eef8;font-size:0.95rem;transition:all 0.3s;font-family:inherit}
        select{width:100%;padding:11px;border:1px solid rgba(0,212,255,0.2);border-radius:10px;background:rgba(0,212,255,0.05);color:#e6eef8;font-size:0.95rem;transition:all 0.3s;font-family:inherit}
        select option{color:#000;background:#fff;}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--accent-a);box-shadow:0 0 20px rgba(0,212,255,0.2)}
        input::placeholder{color:rgba(154,166,199,0.6)}
        select{background:rgba(0,212,255,0.05) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="none" stroke="%239aa6c7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 5l6 6 6-6"/></svg>') no-repeat right 10px center;background-size:18px;padding-right:30px;appearance:none}

        .radio-group{display:flex;flex-direction:column;gap:10px;margin-top:8px}
        .radio-item{display:flex;align-items:center;gap:8px;cursor:pointer}
        .radio-item input{width:16px;height:16px;margin:0}
        .radio-item label{margin:0;font-weight:600;cursor:pointer}

        .error-msg{color:#ff6363;font-size:0.85rem;margin-top:4px}
        .errors{background:rgba(255,99,99,0.1);border:1px solid rgba(255,99,99,0.3);border-radius:10px;padding:12px;margin-bottom:18px;color:#ff6363}
        .errors li{list-style:none;padding:3px 0}
        .errors li::before{content:'❌ '}

        .btn{width:100%;padding:13px;border-radius:10px;border:none;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.3s;text-transform:uppercase;letter-spacing:1px;margin-top:8px}
        .btn-primary{background:linear-gradient(135deg,var(--accent-a),var(--accent-b));color:#0a0e27;box-shadow:0 0 20px rgba(0,212,255,0.3)}
        .btn-primary:hover{transform:translateY(-3px);box-shadow:0 0 40px rgba(0,212,255,0.6)}

        .link{color:var(--accent-a);text-decoration:none;font-weight:700;transition:color 0.3s}
        .link:hover{color:var(--accent-b)}

        .footer{text-align:center;margin-top:18px;color:var(--muted);font-size:0.95rem}
        .footer p{margin:6px 0}

        @media (max-width:480px){
            .card{padding:24px;border-radius:12px}
            .header{margin-bottom:20px}
            .logo{font-size:2rem}
            .radio-group{gap:8px}
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">📚 LibraryHub</div>
                <div class="tagline">CREATE YOUR ACCOUNT</div>
            </div>

            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                    @error('email')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="3-50 characters" value="{{ old('username') }}" required>
                    @error('username')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
                    @error('password')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
                </div>

                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select id="user_type" name="user_type" required onchange="toggleMembershipSection()">
                        <option value="">Select user type</option>
                        <option value="user" @selected(old('user_type') === 'user')>Regular User</option>
                        <option value="librarian" @selected(old('user_type') === 'librarian')>Librarian</option>
                    </select>
                    @error('user_type')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <!-- Membership Section (hidden for librarians) -->
                <div id="membershipSection" style="display: {{ old('user_type') === 'librarian' ? 'none' : 'block' }};">
                    <div class="form-group">
                        <label>Membership</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="membership_yes" name="membership" value="yes" @checked(old('membership') === 'yes') onchange="toggleMembershipOptions()">
                                <label for="membership_yes">Yes, I want a membership</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="membership_no" name="membership" value="no" @checked(old('membership') === 'no' || old('membership') === null) onchange="toggleMembershipOptions()">
                                <label for="membership_no">No, I don't want membership</label>
                            </div>
                        </div>
                        @error('membership')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>

                    <!-- Membership Duration Options (shown when "Yes" is selected) -->
                    <div id="membershipOptions" class="form-group" style="display: {{ old('membership') === 'yes' ? 'block' : 'none' }};">
                        <label>Select Membership Duration</label>
                        <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="duration_1" name="membership_duration" value="1" @checked(old('membership_duration') === '1')>
                            <label for="duration_1">1 Month — RM 15.00</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="duration_2" name="membership_duration" value="2" @checked(old('membership_duration') === '2')>
                            <label for="duration_2">2 Months — RM 27.50</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="duration_3" name="membership_duration" value="3" @checked(old('membership_duration') === '3')>
                            <label for="duration_3">3 Months — RM 40.00</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="duration_6" name="membership_duration" value="6" @checked(old('membership_duration') === '6')>
                            <label for="duration_6">6 Months — RM 60.00</label>
                        </div>
                    </div>
                </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>

            <div class="footer">
                <p>Already have an account? <a href="{{ route('login') }}" class="link">Sign in here</a></p>
                <p style="font-size:0.85rem;margin-top:12px">
                    <a href="{{ route('home') }}" class="link">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function toggleMembershipSection() {
            const userType = document.getElementById('user_type').value;
            const membershipSection = document.getElementById('membershipSection');
            const membershipYes = document.getElementById('membership_yes');
            const membershipNo = document.getElementById('membership_no');
            
            if (userType === 'librarian') {
                membershipSection.style.display = 'none';
                // Clear membership values for librarians
                membershipYes.checked = false;
                membershipNo.checked = false;
            } else {
                membershipSection.style.display = 'block';
                // Set default value for regular users
                if (!membershipYes.checked && !membershipNo.checked) {
                    membershipNo.checked = true;
                }
            }
        }

        function toggleMembershipOptions() {
            const membershipYes = document.getElementById('membership_yes');
            const membershipOptions = document.getElementById('membershipOptions');
            
            if (membershipYes.checked) {
                membershipOptions.style.display = 'block';
            } else {
                membershipOptions.style.display = 'none';
            }
        }
    </script>
