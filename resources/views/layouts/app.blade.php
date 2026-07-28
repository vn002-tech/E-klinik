<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Klinik</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-style.css?v=2.2') }}">
    
    <style>
        body {
            background-color: #F8FAFC;
        }
        .topbar {
            background: #ffffff;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 60px;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: #16A34A;
            text-decoration: none;
            font-size: 20px;
        }
        .topbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .wrapper {
            display: flex;
            align-items: stretch;
            min-height: calc(100vh - 60px);
        }
        
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #fff;
            color: #fff;
            transition: all 0.3s;
            border-right: 1px solid #e3e6f0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            padding-top: 20px;
        }
        
        #sidebar .sidebar-heading {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            font-weight: 700;
            padding: 10px 20px;
            margin-top: 15px;
        }
        
        #sidebar ul.components {
            padding: 0;
        }
        
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            display: block;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            border-left: 4px solid transparent;
        }
        
        #sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: #9CA3AF;
            transition: all 0.3s ease-in-out;
        }
        
        #sidebar ul li a:hover, #sidebar ul li a.active {
            color: #16A34A;
            background: rgba(22, 163, 74, 0.05);
            border-left: 4px solid #16A34A;
            transform: translateX(4px);
        }

        #sidebar ul li a:hover i, #sidebar ul li a.active i {
            color: #16A34A;
            transform: scale(1.1); /* Slight bump to the icon */
        }
        
        #main-content {
            width: 100%;
            padding: 30px;
        }
    </style>
</head>
<body>
    
    <div class="topbar">
        <a href="/" class="topbar-brand">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            E-Klinik
        </a>
        <div class="user-menu">
            <span class="mr-3 font-weight-bold">Halo, {{ auth()->user()->nama ?? auth()->user()->username }}!</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-sign-out"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <ul class="list-unstyled components">
                
                @if(auth()->user()->user_role_id == 1)
                
                <div class="sidebar-heading">MONITORING UTAMA</div>
                <li>
                    <a href="/"><i class="fa fa-desktop"></i> Dashboard Utama</a>
                </li>
                
                <div class="sidebar-heading">PELAYANAN KLINIS</div>
                <li>
                    <a href="{{ route('rekam_medis.index') }}"><i class="fa fa-heartbeat"></i> Rekam Medis</a>
                </li>
                
                <div class="sidebar-heading">DATA MASTER MANAJEMEN</div>
                <li><a href="{{ route('dokter.index') }}"><i class="fa fa-user-md"></i> Data Dokter</a></li>
                <li><a href="{{ route('pasien.index') }}"><i class="fa fa-user"></i> Data Pasien</a></li>
                <li><a href="{{ route('obat.index') }}"><i class="fa fa-medkit"></i> Data Obat</a></li>
                <li><a href="{{ route('ruang.index') }}"><i class="fa fa-building"></i> Data Ruang</a></li>
                
                <div class="sidebar-heading">SISTEM & AKSES</div>
                <li><a href="{{ route('pengguna.index') }}"><i class="fa fa-users"></i> Manajemen Pengguna</a></li>
                <li><a href="#"><i class="fa fa-cogs"></i> Pengaturan Sistem</a></li>
                
                @elseif(auth()->user()->user_role_id == 3)
                
                <div class="sidebar-heading">MENU DOKTER</div>
                <li>
                    <a href="/"><i class="fa fa-desktop"></i> Dashboard Dokter</a>
                </li>
                <li>
                    <a href="{{ route('rekam_medis.index') }}"><i class="fa fa-heartbeat"></i> Kelola Rekam Medis</a>
                </li>
                <li>
                    <a href="{{ route('pasien.index') }}"><i class="fa fa-user"></i> Daftar Pasien</a>
                </li>
                <li>
                    <a href="{{ route('obat.index') }}"><i class="fa fa-medkit"></i> Stok Obat</a>
                </li>
                
                <div class="sidebar-heading">PENGATURAN</div>
                <li>
                    <a href="#"><i class="fa fa-user-md"></i> Profil Saya</a>
                </li>
                
                @else
                
                <div class="sidebar-heading">MENU PASIEN/UMUM</div>
                <li>
                    <a href="/"><i class="fa fa-home"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-user"></i> Profil Saya</a>
                </li>
                
                @endif
                
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="main-content">
            @yield('content')
        </div>
    </div>

</body>
</html>
