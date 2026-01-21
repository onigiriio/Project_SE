<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Catalogue</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600|space-mono:400,700" rel="stylesheet" />
    <style>
        :root{--bg:#050714;--accent-a:#00d4ff;--accent-b:#a855f7;--muted:#9aa6c7}
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family:'Instrument Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
            background:linear-gradient(180deg,var(--bg),#081026);
            color:#e6eef8;padding:26px;
        }
        .wrap{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:260px 1fr;gap:20px}
        .sidebar{background:rgba(255,255,255,0.03);border:1px solid rgba(0,212,255,0.06);padding:18px;border-radius:12px;height:calc(100vh - 52px);overflow:auto}
        .logo{display:flex;gap:12px;align-items:center;margin-bottom:14px}
        .mark{width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,var(--accent-a),var(--accent-b));display:flex;align-items:center;justify-content:center;color:#041029;font-weight:800}
        .nav{display:flex;flex-direction:column;gap:8px;margin-top:6px}
        .nav a{color:var(--muted);text-decoration:none;padding:10px;border-radius:8px;font-weight:700}
        .nav a.active,.nav a:hover{background:linear-gradient(90deg, rgba(0,212,255,0.04), rgba(168,85,247,0.03));color:#e6eef8;border:1px solid rgba(0,212,255,0.06)}

        .main{min-height:calc(100vh - 52px);overflow:auto}
        .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
        .heading{font-size:1.6rem;font-weight:800}
        .controls{display:flex;gap:12px;align-items:center}
        .btn{padding:10px 14px;border-radius:10px;font-weight:700;border:none;cursor:pointer}
        .btn-primary{background:linear-gradient(90deg,var(--accent-a),var(--accent-b));color:#041029}
        .btn-ghost{background:rgba(255,255,255,0.02);color:var(--muted);border:1px solid rgba(255,255,255,0.02)}

        .filters{display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap}
        .chip{padding:8px 12px;border-radius:999px;background:rgba(255,255,255,0.02);color:var(--muted);cursor:pointer;border:1px solid rgba(255,255,255,0.02)}
        .chip.active{background:linear-gradient(90deg,var(--accent-a),var(--accent-b));color:#041029}

        .columns{display:grid;grid-template-columns:2fr 340px;gap:18px}
        .list{display:grid;gap:12px}
        .book{display:flex;gap:12px;padding:12px;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.02)}
        .cover{width:72px;height:96px;border-radius:6px;background:linear-gradient(180deg,#0f1724,#111827);display:flex;align-items:center;justify-content:center;color:var(--muted)}
        .meta{flex:1}
        .title{font-weight:800}
        .subtitle{color:var(--muted);font-size:0.9rem}

        .side-card{background:rgba(255,255,255,0.02);padding:14px;border-radius:10px;border:1px solid rgba(255,255,255,0.02)}
        .section-title{font-weight:800;margin-bottom:10px}
        .small{color:var(--muted);font-size:0.9rem}

        @media (max-width:980px){.wrap{grid-template-columns:1fr}.columns{grid-template-columns:1fr}.sidebar{height:auto}}
    </style>
</head>
<body>
    <div class="wrap">
        <aside class="sidebar">
            <div class="logo">
                <div class="mark">LH</div>
                <div>
                    <div style="font-weight:800">IIUM Library Management System</div>
                    <div style="font-size:0.85rem;color:var(--muted)">Browse Catalogue</div>
                </div>
            </div>

            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('books.index') }}" class="active">View Catalogue</a>
                <a href="{{ route('dashboard') }}">Profile</a>
            </nav>

            <div style="margin-top:16px;border-top:1px dashed rgba(255,255,255,0.03);padding-top:12px">
                <div class="small">Signed in as</div>
                <div style="margin-top:8px;font-weight:800">{{ optional(auth()->user())->username ?? optional(auth()->user())->email }}</div>
                <div style="color:var(--muted);font-size:0.9rem">{{ ucfirst(optional(auth()->user())->user_type ?? 'user') }}</div>

                <form method="POST" action="{{ route('logout') }}" style="margin-top:12px">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Sign Out</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <div class="heading">Explore Books</div>
                    <div class="small">Find suggestions, trending titles, and browse by genre.</div>
                </div>

                <div class="controls">
                    @if(optional(auth()->user())->user_type === 'librarian')
                        <a href="{{ route('books.create') }}" class="btn btn-primary">+ Book</a>
                    @endif
                    <a href="#" class="btn btn-ghost">Search</a>
                </div>
            </div>

            <div class="filters">
                @php $genres = $genres ?? ['All','Fiction','Sci-Fi','Romance','History','Tech','Children']; @endphp
                @foreach($genres as $g)
                    <div class="chip {{ (request('genre') == $g || ($g=='All' && !request('genre'))) ? 'active' : '' }}">{{ $g }}</div>
                @endforeach
            </div>

            <div class="columns">
                <section class="list">
                    <div class="side-card" style="margin-bottom:12px">
                        <div class="section-title">Suggestions for you</div>
                        <div class="small">Based on your reading history</div>
                    </div>

                    @php
                        $suggestions = $suggestions ?? [
                            ['title'=>'Introduction to AI','author'=>'Jane Doe'],
                            ['title'=>'Design Patterns Explained','author'=>'E. Gamma'],
                            ['title'=>'Space Opera Compendium','author'=>'A. Star']
                        ];
                    @endphp

                    @foreach($suggestions as $s)
                        <div class="book">
                            <div class="cover">📘</div>
                            <div class="meta">
                                <div class="title">{{ $s['title'] }}</div>
                                <div class="subtitle">by {{ $s['author'] }}</div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end">
                                <a href="#" class="btn btn-ghost">View</a>
                                <a href="#" class="btn btn-ghost">Save</a>
                            </div>
                        </div>
                    @endforeach

                    <div class="side-card" style="margin-top:18px">
                        <div class="section-title">Trending Now</div>
                        <div class="small">Popular across the system</div>
                        @php
                            $trending = $trending ?? [
                                ['title'=>'Future Cities','author'=>'N. Architect'],
                                ['title'=>'Quantum Computing 101','author'=>'R. Qubit']
                            ];
                        @endphp
                        <div style="margin-top:10px;display:flex;flex-direction:column;gap:8px">
                            @foreach($trending as $t)
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;border-radius:8px;background:rgba(255,255,255,0.01)">
                                    <div>
                                        <div style="font-weight:700">{{ $t['title'] }}</div>
                                        <div class="small">{{ $t['author'] }}</div>
                                    </div>
                                    <div class="small">🔥</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <aside>
                    <div class="side-card" style="margin-bottom:12px">
                        <div class="section-title">Browse by Genre</div>
                        <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
                            @foreach($genres as $g)
                                <a href="?genre={{ urlencode($g) }}" class="chip">{{ $g }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="side-card">
                        <div class="section-title">Quick Links</div>
                        <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
                            <a href="{{ route('books.index') }}" class="chip">Full Catalogue</a>
                            <a href="{{ route('dashboard') }}" class="chip">Your Profile</a>
                            @if(optional(auth()->user())->user_type === 'librarian')
                                <a href="{{ route('books.create') }}" class="chip">Add New Book</a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</body>
</html>
