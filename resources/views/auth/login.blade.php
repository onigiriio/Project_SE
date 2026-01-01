<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — LibraryHub</title>
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
            position:relative;overflow:hidden;
        }

        /* Animated background elements */
        .bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:-1}
        .orb{position:absolute;border-radius:50%;opacity:0.1;animation:float 20s infinite ease-in-out}
        .orb-1{width:400px;height:400px;background:radial-gradient(circle, #00d4ff, transparent);top:-100px;left:-100px}
        .orb-2{width:300px;height:300px;background:radial-gradient(circle, #a855f7, transparent);bottom:-50px;right:-50px;animation-delay:5s}
        @keyframes float{0%, 100%{transform:translate(0, 0)}50%{transform:translate(30px, 30px)}}

        .container{max-width:420px;width:100%;padding:20px}
        .card{background:rgba(10, 14, 39, 0.7);border:2px solid rgba(0, 212, 255, 0.2);border-radius:16px;padding:40px;backdrop-filter:blur(10px);box-shadow:0 0 60px rgba(0,212,255,0.1)}

        .header{text-align:center;margin-bottom:30px}
        .logo{font-size:2.5rem;font-weight:800;background:linear-gradient(135deg,var(--accent-a),var(--accent-b));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px}
        .tagline{color:var(--muted);font-size:0.95rem;letter-spacing:1px}

        .form-group{margin-bottom:18px}
        label{display:block;font-weight:700;margin-bottom:8px;color:#e6eef8}
        input{width:100%;padding:12px;border:1px solid rgba(0,212,255,0.2);border-radius:10px;background:rgba(0,212,255,0.05);color:#e6eef8;font-size:1rem;transition:all 0.3s}
        input:focus{outline:none;border-color:var(--accent-a);box-shadow:0 0 20px rgba(0,212,255,0.2)}
        input::placeholder{color:rgba(154,166,199,0.6)}

        .checkbox-group{display:flex;align-items:center;gap:8px}
        input[type="checkbox"]{width:18px;height:18px;cursor:pointer}

        .btn{width:100%;padding:14px;border-radius:10px;border:none;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.3s;text-transform:uppercase;letter-spacing:1px}
        .btn-primary{background:linear-gradient(135deg,var(--accent-a),var(--accent-b));color:#0a0e27;box-shadow:0 0 20px rgba(0,212,255,0.3)}
        .btn-primary:hover{transform:translateY(-3px);box-shadow:0 0 40px rgba(0,212,255,0.6)}

        .divider{display:flex;align-items:center;gap:12px;margin:24px 0;color:var(--muted)}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:rgba(0,212,255,0.1)}

        .link{color:var(--accent-a);text-decoration:none;font-weight:700;transition:color 0.3s}
        .link:hover{color:var(--accent-b)}

        .footer{text-align:center;margin-top:20px}
        .footer p{color:var(--muted);font-size:0.95rem}

        .errors{background:rgba(255,99,99,0.1);border:1px solid rgba(255,99,99,0.3);border-radius:10px;padding:12px;margin-bottom:18px;color:#ff6363}
        .errors li{list-style:none;padding:4px 0}
        .errors li::before{content:'❌ '}

        @media (max-width:480px){
            .card{padding:30px;border-radius:12px}
            .header{margin-bottom:24px}
            .logo{font-size:2rem}
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
                <div class="tagline">SIGN IN TO YOUR ACCOUNT</div>
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

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember" style="margin:0;font-weight:600">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary">Enter the System</button>
            </form>

            <div class="divider">or</div>

            <div class="footer">
                <p>Don't have an account? <a href="{{ route('register') }}" class="link">Create one now</a></p>
                <p style="margin-top:12px;font-size:0.85rem">
                    <a href="{{ route('home') }}" class="link">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
