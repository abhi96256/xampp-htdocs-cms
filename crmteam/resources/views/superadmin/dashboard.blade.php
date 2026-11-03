<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f1117; min-height: 100vh; color: #e2e8f0; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1d2e 0%, #16213e 100%);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        .header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header h1 i { -webkit-text-fill-color: #667eea; }
        .logout-btn {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .logout-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(229,62,62,0.4); }

        /* Stats bar */
        .stats-bar {
            background: #1a1d2e;
            padding: 15px 30px;
            display: flex;
            gap: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .stat-chip {
            background: rgba(102,126,234,0.15);
            border: 1px solid rgba(102,126,234,0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #667eea;
        }

        /* Table container */
        .table-wrap { padding: 25px 30px; }
        .table-card {
            background: #1a1d2e;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .table-card-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-card-header h3 { font-size: 1rem; font-weight: 600; color: #e2e8f0; }
        .table-card-header i { color: #667eea; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: rgba(102,126,234,0.08); }
        th {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            white-space: nowrap;
        }
        tbody tr {
            border-top: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        tbody tr:hover { background: rgba(255,255,255,0.03); }
        td { padding: 14px 20px; font-size: 0.88rem; color: #cbd5e1; vertical-align: middle; }
        .badge-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px; height: 30px;
            background: rgba(102,126,234,0.2);
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            color: #667eea;
        }
        .user-name { font-weight: 600; color: #e2e8f0; }
        .user-email { color: #94a3b8; font-size: 0.83rem; }
        .user-phone { font-family: monospace; color: #a0aec0; }

        /* Password button */
        .pwd-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .pwd-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
        .pwd-btn.hide-mode {
            background: linear-gradient(135deg, #2d3748, #4a5568);
        }

        /* Password reveal pill */
        .pwd-reveal {
            display: none;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 8px;
            padding: 8px 14px;
            animation: fadeSlide 0.25s ease;
        }
        .pwd-reveal.hash-only {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
        }
        .pwd-reveal i { font-size: 0.8rem; color: #10b981; }
        .pwd-reveal.hash-only i { color: #f59e0b; }
        .pwd-text-val { font-family: 'Courier New', monospace; font-size: 0.88rem; font-weight: 700; color: #10b981; letter-spacing: 0.05em; }
        .pwd-text-val.hash-only { color: #f59e0b; }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .actions-cell { min-width: 200px; }
    </style>
</head>
<body>

<div class="header">
    <h1><i class="fas fa-shield-halved"></i> Superadmin Control Panel</h1>
    <form action="{{ route('superadmin.logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>

<div class="stats-bar">
    <span class="stat-chip"><i class="fas fa-users"></i> Total Users: {{ count($users) }}</span>
    <span class="stat-chip"><i class="fas fa-eye"></i> Passwords Visible: {{ $users->whereNotNull('visible_password')->count() }}</span>
    <span class="stat-chip"><i class="fas fa-lock"></i> Hash Only: {{ $users->whereNull('visible_password')->count() }}</span>
</div>

<div class="table-wrap">
    <div class="table-card">
        <div class="table-card-header">
            <i class="fas fa-table"></i>
            <h3>All Registered Users</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><span class="badge-id">{{ $user->id }}</span></td>
                    <td><span class="user-name">{{ $user->name }}</span></td>
                    <td><span class="user-email">{{ $user->email }}</span></td>
                    <td><span class="user-phone">{{ $user->phone ?? 'N/A' }}</span></td>
                    <td class="actions-cell">
                        @if($user->visible_password)
                            <button class="pwd-btn" onclick="togglePwd(this, '{{ addslashes($user->visible_password) }}', false)">
                                <i class="fas fa-eye"></i> Show Password
                            </button>
                            <div class="pwd-reveal" style="display:none;">
                                <i class="fas fa-key"></i>
                                <span class="pwd-text-val"></span>
                            </div>
                        @else
                            <button class="pwd-btn" onclick="togglePwd(this, 'Hash Only — Old User', true)">
                                <i class="fas fa-eye"></i> Show Password
                            </button>
                            <div class="pwd-reveal hash-only" style="display:none;">
                                <i class="fas fa-triangle-exclamation"></i>
                                <span class="pwd-text-val hash-only">Hash Only — Old User</span>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach

                @if(count($users) === 0)
                <tr>
                    <td colspan="5" style="text-align:center; padding:40px; color:#64748b;">No users found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    function togglePwd(btn, pwd, isHash) {
        var reveal = btn.nextElementSibling;
        var pwdSpan = reveal.querySelector('.pwd-text-val');

        if (reveal.style.display === 'none' || reveal.style.display === '') {
            pwdSpan.textContent = pwd;
            reveal.style.display = 'flex';
            btn.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Password';
            btn.classList.add('hide-mode');
        } else {
            reveal.style.display = 'none';
            if (isHash) {
                btn.innerHTML = '<i class="fas fa-eye"></i> Show Password';
            } else {
                btn.innerHTML = '<i class="fas fa-eye"></i> Show Password';
            }
            btn.classList.remove('hide-mode');
        }
    }
</script>

</body>
</html>
