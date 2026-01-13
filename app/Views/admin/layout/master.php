<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshSalads Admin | <?= $this->renderSection('title') ?></title>
    
    <!-- Google Fonts: Poppins (Premium feel) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Brand Colors */
            --primary: #05b46a; /* Fresh Salad Green */
            --primary-dark: #049055;
            --secondary: #2c3e50;
            
            /* Sidebar Colors */
            --sidebar-bg: #111c43; /* Deep Navy */
            --sidebar-text: #a3aed1;
            --sidebar-active: #ffffff;
            --sidebar-active-bg: rgba(255, 255, 255, 0.1);

            /* UI Colors */
            --bg-body: #f3f5f9;
            --card-shadow: 0 10px 30px 0 rgba(0,0,0,0.05);
            --card-radius: 16px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: #495057;
            overflow-x: hidden;
        }

        /* --- LAYOUT --- */
        .wrapper {
            display: flex;
            width: 100%;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            min-width: 280px;
            max-width: 280px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar .logo-area {
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.1);
        }

        #sidebar .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            box-shadow: 0 4px 15px rgba(5, 180, 106, 0.4);
        }

        #sidebar ul.components {
            padding: 20px 15px;
        }

        #sidebar .nav-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(163, 174, 209, 0.5);
            margin: 20px 15px 10px;
            font-weight: 600;
        }

        #sidebar ul li a {
            padding: 14px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        #sidebar ul li a i {
            margin-right: 15px;
            font-size: 1.2rem;
            opacity: 0.8;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
            transform: translateX(5px);
        }

        #sidebar ul li a.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(5, 180, 106, 0.3);
        }

        /* --- CONTENT AREA --- */
        #content {
            width: 100%;
            margin-left: 280px; /* Matches Sidebar Width */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content-padding {
            padding: 30px;
        }

        /* --- TOPBAR --- */
        .topbar-custom {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .search-box input {
            background: #f4f6f8;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            width: 300px;
            outline: none;
            transition: all 0.2s;
        }
        .search-box input:focus {
            background: #fff;
            box-shadow: 0 0 0 3px rgba(5, 180, 106, 0.1);
        }

        /* --- CARDS & WIDGETS --- */
        .stat-card {
            background: white;
            border-radius: var(--card-radius);
            padding: 25px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .icon-bubble {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .bubble-green { background: rgba(5, 180, 106, 0.1); color: var(--primary); }
        .bubble-orange { background: rgba(255, 159, 67, 0.1); color: #ff9f43; }
        .bubble-blue { background: rgba(84, 160, 255, 0.1); color: #54a0ff; }
        .bubble-red { background: rgba(238, 82, 83, 0.1); color: #ee5253; }

        /* --- TABLE --- */
        .table-card {
            background: white;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .table-modern thead th {
            background: #f8f9fa;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 25px;
            border-bottom: none;
            color: #8898aa;
            font-weight: 600;
        }

        .table-modern tbody td {
            padding: 20px 25px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-delivered { background: rgba(5, 180, 106, 0.1); color: var(--primary); }
        .status-pending { background: rgba(255, 159, 67, 0.1); color: #ff9f43; }
        .status-preparing { background: rgba(84, 160, 255, 0.1); color: #54a0ff; }

        @media (max-width: 991px) {
            #sidebar { margin-left: -280px; }
            #content { margin-left: 0; }
            #sidebar.active { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Include Sidebar -->
    <?= $this->include('admin/layout/sidebar') ?>

    <!-- Page Content -->
    <div id="content">
        <!-- Include Topbar -->
        <?= $this->include('admin/layout/topbar') ?>

        <!-- Main View -->
        <div class="main-content-padding">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Scripts for Toggling Sidebar (Mobile) -->
<script>
    document.getElementById('sidebarCollapse')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

</body>
</html>