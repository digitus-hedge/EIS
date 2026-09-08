<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #1e1e2d;
            color: #fff;
            padding: 20px 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 25px;
            color: #cfcfe0;
            text-decoration: none;
            font-size: 15px;
            cursor: pointer;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #3b3b58;
            color: #fff;
        }

        /* Submenu */
        .has-submenu>a .chevron {
            transition: transform 0.2s ease;
            font-size: 12px;
        }

        .has-submenu.open>a .chevron {
            transform: rotate(90deg);
        }

        .submenu {
            list-style: none;
            max-height: 0;
            overflow: hidden;
            background: #17171f;
            transition: max-height 0.3s ease;
        }

        .has-submenu.open .submenu {
            max-height: 400px;
        }

        .submenu li a {
            padding: 10px 25px 10px 50px;
            font-size: 14px;
            color: #a9a9c2;
        }

        .submenu li a:hover,
        .submenu li a.active {
            background: #2b2b42;
            color: #fff;
        }

        /* Main content */
        .main {
            flex: 1;
            padding: 30px;
            background: #f4f6f9;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .welcome-text {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 15px;
            color: #333;
        }

        .welcome-text i {
            font-size: 18px;
            color: #3b3b58;
        }

        .logout-form {
            display: inline;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .logout-btn i {
            font-size: 16px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
        }

        .sidebar-logo {
            width: 70px;
            /* height: 36px; */
            object-fit: contain;
            border-radius: 6px;
        }

        .sidebar-brand h2 {
            font-size: 18px;
            margin: 0;
            color: #fff;
        }

    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo.webp') }}" alt="EGTS Logo" class="sidebar-logo">
            <h2>EGTS Admin</h2>
        </div>

        <ul>
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>

            <li
                class="has-submenu {{ request()->routeIs('admin.home') || request()->routeIs('admin.home.*') ? 'open' : '' }}">
                <a onclick="toggleSubmenu(this)">
                    Home
                    <i class="bi bi-chevron-right chevron"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.home.banner') }}"
                            class="{{ request()->routeIs('admin.home.banner*') ? 'active' : '' }}">Banner Section</a>
                    </li>
                    <li><a href="{{ route('admin.home.about') }}"
                            class="{{ request()->routeIs('admin.home.about*') ? 'active' : '' }}">About Section</a></li>
                    <li><a href="{{ route('admin.home.stats') }}"
                            class="{{ request()->routeIs('admin.home.stats*') ? 'active' : '' }}">Stats Section</a></li>
                    <li><a href="{{ route('admin.home.services.section') }}"
                            class="{{ request()->routeIs('admin.home.services.section*') ? 'active' : '' }}">Service
                            Section</a></li>
                    <li><a href="{{ route('admin.home.clients') }}"
                            class="{{ request()->routeIs('admin.home.clients*') ? 'active' : '' }}">Client Section</a>
                    </li>
                    <li><a href="{{ route('admin.home.why-choose-us') }}"
                            class="{{ request()->routeIs('admin.home.why-choose-us*') ? 'active' : '' }}">Why Choose
                            Us</a>
                    </li>
                </ul>



            <li><a href="{{ route('admin.about') }}"
                    class="{{ request()->routeIs('admin.about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('admin.home.services') }}"
                    class="{{ request()->routeIs('admin.home.services') || (request()->routeIs('admin.home.services.*') && !request()->routeIs('admin.home.services.section*')) ? 'active' : '' }}">Services</a>
            </li>
            <li><a href="{{ route('admin.contacts') }}"
                    class="{{ request()->routeIs('admin.contacts') ? 'active' : '' }}">Contacts</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h3>@yield('title')</h3>
            <div class="topbar-right">
                <span class="welcome-text">
                    <i class="bi bi-person-circle"></i> Welcome, {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSubmenu(el) {
            const parentLi = el.closest('.has-submenu');
            parentLi.classList.toggle('open');
        }

    </script>

</body>

</html>
