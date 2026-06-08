<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Auth Portal</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #f1f5f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { text-align: center; padding: 2rem; }
        h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; background: linear-gradient(135deg, #6366f1, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        p.subtitle { color: #94a3b8; margin-bottom: 3rem; font-size: 1.1rem; }
        .cards { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 1rem; padding: 2rem; width: 280px; transition: transform 0.2s, border-color 0.2s; }
        .card:hover { transform: translateY(-4px); }
        .card.admin { border-color: #6366f1; }
        .card.provider { border-color: #10b981; }
        .card .icon { font-size: 3rem; margin-bottom: 1rem; }
        .card h2 { font-size: 1.4rem; margin-bottom: 0.5rem; }
        .card p { color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .btn { display: inline-block; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; }
        .btn-admin { background: #6366f1; color: #fff; }
        .btn-provider { background: #10b981; color: #fff; }
        .creds { margin-top: 3rem; background: #1e293b; border: 1px solid #334155; border-radius: 0.75rem; padding: 1.5rem; max-width: 560px; margin-left: auto; margin-right: auto; text-align: left; }
        .creds h3 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 1rem; }
        .cred-row { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0; border-bottom: 1px solid #0f172a; font-size: 0.9rem; }
        .cred-row:last-child { border-bottom: none; }
        .badge { font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 99px; font-weight: 600; }
        .badge-admin { background: #312e81; color: #a5b4fc; }
        .badge-provider { background: #064e3b; color: #6ee7b7; }
        code { color: #94a3b8; font-family: monospace; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Multi-Auth Portal</h1>
        <p class="subtitle">Laravel · Filament · Spatie Permissions · PostgreSQL</p>

        <div class="cards">
            <div class="card admin">
                <div class="icon">🛡️</div>
                <h2>Admin Portal</h2>
                <p>Manage admins, roles, permissions, providers, and the full catalog.</p>
                <a href="/admin" class="btn btn-admin">Go to Admin Panel</a>
            </div>
            <div class="card provider">
                <div class="icon">🏢</div>
                <h2>Provider Portal</h2>
                <p>Providers manage their own profile, services, and products.</p>
                <a href="/provider" class="btn btn-provider">Go to Provider Panel</a>
            </div>
        </div>

        <div class="creds">
            <h3>🔑 Demo Credentials (password: <code>password</code>)</h3>
            <div class="cred-row">
                <span><span class="badge badge-admin">Admin</span> <code>superadmin@example.com</code></span>
                <span style="color:#a5b4fc">Super Admin</span>
            </div>
            <div class="cred-row">
                <span><span class="badge badge-admin">Admin</span> <code>catalog@example.com</code></span>
                <span style="color:#a5b4fc">Catalog Manager</span>
            </div>
            <div class="cred-row">
                <span><span class="badge badge-admin">Admin</span> <code>provideradmin@example.com</code></span>
                <span style="color:#a5b4fc">Provider Manager</span>
            </div>
            <div class="cred-row">
                <span><span class="badge badge-provider">Provider</span> <code>provider1@example.com</code></span>
                <span style="color:#6ee7b7">TechSolutions MX</span>
            </div>
            <div class="cred-row">
                <span><span class="badge badge-provider">Provider</span> <code>provider2@example.com</code></span>
                <span style="color:#6ee7b7">Creativa Digital</span>
            </div>
            <div class="cred-row">
                <span><span class="badge badge-provider">Provider</span> <code>provider3@example.com</code></span>
                <span style="color:#6ee7b7">Capacita Pro</span>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Daniel\Documents\projects\php\multi-auth-portal\resources\views/welcome.blade.php ENDPATH**/ ?>