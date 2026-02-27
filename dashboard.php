<?php
require_once 'db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MediCare Hospital</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fc;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 0 25px;
            margin-bottom: 40px;
        }

        .sidebar-header h3 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-header h3 span {
            color: #ffd700;
        }

        .sidebar-header p {
            font-size: 14px;
            opacity: 0.8;
            margin: 5px 0 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #ffd700;
        }

        .sidebar-menu a i {
            width: 30px;
            font-size: 18px;
        }

        .sidebar-menu a span {
            font-size: 15px;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: all 0.3s ease;
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h2 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .page-title p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
        }

        .notification-badge i {
            font-size: 22px;
            color: #7f8c8d;
        }

        .badge-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            font-size: 11px;
            padding: 3px 6px;
            border-radius: 10px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .user-details {
            line-height: 1.4;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 15px;
        }

        .user-role {
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .stat-icon.doctors { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .stat-icon.patients { background: rgba(46, 204, 113, 0.1); color: #2ecc71; }
        .stat-icon.appointments { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
        .stat-icon.today { background: rgba(241, 196, 15, 0.1); color: #f1c40f; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-change {
            position: absolute;
            top: 25px;
            right: 25px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .stat-change.positive {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .chart-header select {
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            color: #2c3e50;
        }

        /* Appointments Table */
        .appointments-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            border-top: none;
            border-bottom: 2px solid #f0f0f0;
            color: #7f8c8d;
            font-weight: 600;
            font-size: 14px;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #2c3e50;
            border-bottom: 1px solid #f0f0f0;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-scheduled { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .status-completed { background: rgba(46, 204, 113, 0.1); color: #2ecc71; }
        .status-cancelled { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn.edit { background: #f1c40f; }
        .action-btn.view { background: #3498db; }
        .action-btn.delete { background: #e74c3c; }

        .action-btn:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .quick-action-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            display: block;
            color: inherit;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-color: transparent;
            color: inherit;
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 15px;
        }

        .quick-action-icon.blue { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .quick-action-icon.green { background: rgba(46, 204, 113, 0.1); color: #2ecc71; }
        .quick-action-icon.purple { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
        .quick-action-icon.orange { background: rgba(230, 126, 34, 0.1); color: #e67e22; }

        .quick-action-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .quick-action-desc {
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .charts-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Medi<span>Care</span></h3>
            <p>Hospital Management System</p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="/IM3_ACTIVITY/dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/IM3_ACTIVITY/view_doctors.php">
                    <i class="fas fa-user-md"></i>
                    <span>Doctors</span>
                </a>
            </li>
            <li>
                <a href="/IM3_ACTIVITY/view_patients.php">
                    <i class="fas fa-users"></i>
                    <span>Patients</span>
                </a>
            </li>
            <li>
                <a href="/IM3_ACTIVITY/view_appointments.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="page-title">
                <h2>Dashboard</h2>
                <p>Welcome back, Admin</p>
            </div>
            
            <div class="user-info">
                <!-- Home Button -->
                <a href="/IM3_ACTIVITY/index.html" class="btn-primary me-3">
                    <i class="fas fa-home me-1"></i>Home
                </a>

                <div class="notification-badge">
                    <i class="far fa-bell"></i>
                    <span class="badge-count">3</span>
                </div>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        <span>AD</span>
                    </div>
                    <div class="user-details">
                        <div class="user-name">Admin User</div>
                        <div class="user-role">Hospital Administrator</div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <?php
        // Get statistics from database
        try {
            // Total doctors
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM doctors");
            $totalDoctors = $stmt->fetch()['count'];
            
            // Total patients
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM patients");
            $totalPatients = $stmt->fetch()['count'];
            
            // Total appointments
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM appointments");
            $totalAppointments = $stmt->fetch()['count'];
            
            // Today's appointments
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE DATE(appointment_date) = CURDATE()");
            $stmt->execute();
            $todayAppointments = $stmt->fetch()['count'];
            
            // Upcoming appointments
            $stmt = $pdo->prepare("
                SELECT a.*, d.name as doctor_name, d.specialization, p.name as patient_name, p.phone 
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN patients p ON a.patient_id = p.id
                WHERE a.appointment_date >= CURDATE() 
                ORDER BY a.appointment_date ASC, a.appointment_time ASC
                LIMIT 10
            ");
            $stmt->execute();
            $upcomingAppointments = $stmt->fetchAll();
            
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
        ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon doctors">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-value"><?php echo $totalDoctors ?? 0; ?></div>
                <div class="stat-label">Total Doctors</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon patients">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value"><?php echo $totalPatients ?? 0; ?></div>
                <div class="stat-label">Total Patients</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 8%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon appointments">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value"><?php echo $totalAppointments ?? 0; ?></div>
                <div class="stat-label">Total Appointments</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 15%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value"><?php echo $todayAppointments ?? 0; ?></div>
                <div class="stat-label">Today's Appointments</div>
                <div class="stat-change">
                    <i class="fas fa-minus"></i> Today
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Appointment Statistics</h3>
                    <select>
                        <option>This Week</option>
                        <option>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <canvas id="appointmentsChart" style="width:100%; max-height:300px;"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3>Patient Demographics</h3>
                </div>
                <canvas id="demographicsChart" style="width:100%; max-height:300px;"></canvas>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="appointments-section">
            <div class="section-header">
                <h3>Upcoming Appointments</h3>
                <a href="/IM3_ACTIVITY/add_appointment.php" class="btn-primary" style="padding: 10px 20px;">
                    <i class="fas fa-plus-circle me-2"></i>New Appointment
                </a>
            </div>

            <?php if (empty($upcomingAppointments)): ?>
                <p class="text-muted text-center py-4">No upcoming appointments found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingAppointments as $appointment): ?>
                            <tr>
                                <td>
                                    <strong><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($appointment['doctor_name']); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo htmlspecialchars($appointment['specialization'] ?? 'General'); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['phone'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    $statusText = $appointment['status'] ?? 'Scheduled';
                                    
                                    if ($statusText == 'Scheduled') $statusClass = 'status-scheduled';
                                    elseif ($statusText == 'Completed') $statusClass = 'status-completed';
                                    elseif ($statusText == 'Cancelled') $statusClass = 'status-cancelled';
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/IM3_ACTIVITY/view_appointment.php?id=<?php echo $appointment['id']; ?>" class="action-btn view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/IM3_ACTIVITY/edit_appointment.php?id=<?php echo $appointment['id']; ?>" class="action-btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/IM3_ACTIVITY/delete_appointment.php?id=<?php echo $appointment['id']; ?>" 
                                       class="action-btn delete" title="Delete"
                                       onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="/IM3_ACTIVITY/add_doctor.php" class="quick-action-card">
                    <div class="quick-action-icon blue">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="quick-action-title">Add Doctor</div>
                    <div class="quick-action-desc">Register new doctor</div>
                </a>

                <a href="/IM3_ACTIVITY/add_patient.php" class="quick-action-card">
                    <div class="quick-action-icon green">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="quick-action-title">Add Patient</div>
                    <div class="quick-action-desc">Register new patient</div>
                </a>

                <a href="/IM3_ACTIVITY/add_appointment.php" class="quick-action-card">
                    <div class="quick-action-icon purple">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="quick-action-title">Schedule</div>
                    <div class="quick-action-desc">New appointment</div>
                </a>

                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="quick-action-title">Reports</div>
                    <div class="quick-action-desc">View analytics</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Appointments Chart
            const ctx1 = document.getElementById('appointmentsChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Appointments',
                        data: [12, 19, 15, 17, 14, 8, 5],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Demographics Chart
            const ctx2 = document.getElementById('demographicsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female', 'Children'],
                    datasets: [{
                        data: [45, 40, 15],
                        backgroundColor: ['#667eea', '#9b59b6', '#2ecc71'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>