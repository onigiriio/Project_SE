<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System — Full View</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600|space-mono:400,700" rel="stylesheet" />
    <style>
        :root{
            --bg-1: #050714;
            --panel: rgba(255,255,255,0.04);
            --accent-a: #00d4ff;
            --accent-b: #a855f7;
            --muted: #9aa6c7;
            --glass-border: rgba(0,212,255,0.12);
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family: 'Instrument Sans', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            background: radial-gradient(1200px 600px at 10% 10%, rgba(0,212,255,0.06), transparent),
                        radial-gradient(800px 400px at 90% 80%, rgba(168,85,247,0.05), transparent),
                        linear-gradient(180deg,var(--bg-1), #081026);
            color:#e6eef8;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            min-height:100vh;
            padding:30px;
        }

        /* Animated grid */
        .grid-canvas{position:fixed;inset:0;pointer-events:none;opacity:0.08;background-image:linear-gradient(90deg, rgba(0,212,255,0.06) 1px, transparent 1px), linear-gradient(rgba(168,85,247,0.04) 1px, transparent 1px);background-size:120px 120px, 120px 120px;transform:translateZ(0);}

        /* Layout */
        .app{
            display:grid;
            grid-template-columns:260px 1fr;
            gap:24px;
            max-width:1400px;
            margin:0 auto;
        }

        /* Sidebar */
        .sidebar{
            background:var(--panel);
            border:1px solid var(--glass-border);
            border-radius:12px;
            padding:20px;
            backdrop-filter: blur(8px) saturate(120%);
            height:calc(100vh - 60px);
            overflow:auto;
        }

        .logo{font-weight:700;display:flex;align-items:center;gap:12px;margin-bottom:18px}
        .logo .mark{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--accent-a),var(--accent-b));display:flex;align-items:center;justify-content:center;color:#041029;font-weight:800}
        .nav{margin-top:10px;display:flex;flex-direction:column;gap:8px}
        .nav a{color:var(--muted);text-decoration:none;padding:10px;border-radius:8px;display:flex;align-items:center;gap:12px;font-weight:600}
        .nav a.active,.nav a:hover{background:linear-gradient(90deg, rgba(0,212,255,0.06), rgba(168,85,247,0.03));color:#e6eef8;border:1px solid rgba(0,212,255,0.08)}

        /* Main */
        .main{
            min-height:calc(100vh - 60px);
            overflow:auto;
        }

        .header{
            display:flex;align-items:center;justify-content:space-between;margin-bottom:22px
        }

        .welcome{
            display:flex;gap:18px;align-items:center
        }

        .user-card{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);border:1px solid rgba(255,255,255,0.03);padding:12px 16px;border-radius:12px;display:flex;gap:12px;align-items:center}

        .panels{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:22px}
        .panel{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);border:1px solid rgba(255,255,255,0.03);padding:18px;border-radius:12px}
        .panel .num{font-size:1.8rem;font-weight:800;background:linear-gradient(90deg,var(--accent-a),var(--accent-b));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .panel .label{color:var(--muted);font-size:0.85rem;margin-top:6px}

        .cards{display:grid;grid-template-columns:2fr 1fr;gap:18px}
        .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);border:1px solid rgba(255,255,255,0.03);padding:16px;border-radius:12px}

        .activity-list{display:flex;flex-direction:column;gap:12px}
        .activity-item{display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(255,255,255,0.02);border-radius:8px}

        .logout-btn{background:linear-gradient(90deg,#ff6b6b,#ff3b3b);color:#081026;padding:8px 12px;border-radius:8px;border:none;font-weight:700;cursor:pointer}

        @media (max-width: 880px){
            .app{grid-template-columns:1fr;}
            .panels{grid-template-columns:repeat(2,1fr)}
            .cards{grid-template-columns:1fr}
            .sidebar{height:auto}
        }
    </style>
</head>
<body>
    <div class="grid-canvas"></div>
    <div class="app">
        <aside class="sidebar">
            <div class="logo">
                <div class="mark">LH</div>
                <div>
                    <div style="font-size:1rem">LibraryHub</div>
                    <div style="font-size:0.75rem;color:var(--muted)">System — Full View</div>
                </div>
            </div>

            <nav class="nav">
                <a href="#" class="active">Overview</a>
                <a href="#">Catalog</a>
                <a href="#">Users</a>
                <a href="#">Analytics</a>
                <a href="#">Settings</a>
            </nav>

            <div style="margin-top:18px;border-top:1px dashed rgba(255,255,255,0.03);padding-top:14px">
                <div style="font-size:0.85rem;color:var(--muted)">Logged in as</div>
                <div style="margin-top:8px" class="user-card">
                    <div style="width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,var(--accent-a),var(--accent-b));display:flex;align-items:center;justify-content:center;color:#041029;font-weight:800">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'U',0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700">{{ optional(auth()->user())->username ?? optional(auth()->user())->email }}</div>
                        <div style="font-size:0.8rem;color:var(--muted)">{{ ucfirst(optional(auth()->user())->user_type ?? 'user') }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin-top:12px">
                    @csrf
                    <button type="submit" class="logout-btn">Sign Out</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <div class="header">
                <div class="welcome">
                    <div>
                        <h2 style="font-size:1.4rem;margin-bottom:6px">Welcome back, {{ optional(auth()->user())->username ?? optional(auth()->user())->name ?? 'User' }}</h2>
                        <div style="color:var(--muted);font-size:0.95rem">Here's a quick overview of system health and activity.</div>
                    </div>
                </div>
                <div style="display:flex;gap:12px;align-items:center">
                    <div style="text-align:right">
                        <div style="font-size:0.9rem;color:var(--muted)">Membership</div>
                        <div style="font-weight:800">{{ auth()->user()->membership ? 'Active' : 'None' }}</div>
                    </div>
                </div>
            </div>

            <section class="panels">
                <div class="panel">
                    <div class="num">10,482</div>
                    <div class="label">Total Resources</div>
                </div>
                <div class="panel">
                    <div class="num">5,210</div>
                    <div class="label">Registered Users</div>
                </div>
                <div class="panel">
                    <div class="num">99.97%</div>
                    <div class="label">Uptime</div>
                </div>
            </section>

            <section class="cards">
                <div class="card">
                    <h3 style="margin-bottom:12px">Recent Activity</h3>
                    <div class="activity-list">
                        <div class="activity-item"><div>📚 User John borrowed "Introduction to AI"</div><div style="color:var(--muted)">2m ago</div></div>
                        <div class="activity-item"><div>🔁 Catalog sync completed</div><div style="color:var(--muted)">10m ago</div></div>
                        <div class="activity-item"><div>🛠️ Backup finished</div><div style="color:var(--muted)">1h ago</div></div>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-bottom:12px">Quick Actions</h3>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <a href="#" style="padding:10px;background:linear-gradient(90deg,var(--accent-a),var(--accent-b));color:#041029;text-decoration:none;border-radius:8px;font-weight:700;text-align:center">Add Resource</a>
                        <a href="#" style="padding:10px;border-radius:8px;background:rgba(255,255,255,0.02);color:var(--muted);text-align:center;text-decoration:none">Manage Users</a>
                        <a href="#" style="padding:10px;border-radius:8px;background:rgba(255,255,255,0.02);color:var(--muted);text-align:center;text-decoration:none">View Analytics</a>
                    </div>
                </div>
            </section>

            <section style="margin-top:18px">
                <div class="card">
                    <h3 style="margin-bottom:12px">System Notes</h3>
                    <p style="color:var(--muted)">All systems nominal. If you notice any irregularities, go to Settings → Diagnostics.</p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
