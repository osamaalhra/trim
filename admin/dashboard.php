<?php
session_start();
require_once '../config.php';

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// جلب جميع المهام
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب المهام الموزعة اليوم
$today = date('Y-m-d');
$stmt = $pdo->prepare("
    SELECT dt.*, t.title, t.estimated_time, t.difficulty 
    FROM daily_tasks dt 
    JOIN tasks t ON dt.task_id = t.id 
    WHERE dt.assigned_date = ? 
    ORDER BY t.estimated_time DESC
");
$stmt->execute([$today]);
$dailyTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// حساب إجمالي الوقت
$totalTime = 0;
$completedTime = 0;
foreach($dailyTasks as $task) {
    $totalTime += $task['estimated_time'];
    if($task['completed']) {
        $completedTime += $task['estimated_time'];
    }
}

// إحصائيات الأسبوع
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));

$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_tasks,
        SUM(t.estimated_time) as total_time,
        SUM(CASE WHEN dt.completed = 1 THEN t.estimated_time ELSE 0 END) as completed_time,
        AVG(CASE WHEN dt.completed = 1 THEN 1 ELSE 0 END) as completion_rate
    FROM daily_tasks dt 
    JOIN tasks t ON dt.task_id = t.id 
    WHERE dt.assigned_date BETWEEN ? AND ?
");
$stmt->execute([$weekStart, $weekEnd]);
$weekStats = $stmt->fetch(PDO::FETCH_ASSOC);

// المهام الأكثر استخداماً
$stmt = $pdo->prepare("
    SELECT t.title, COUNT(dt.id) as usage_count, AVG(t.estimated_time) as avg_time
    FROM daily_tasks dt 
    JOIN tasks t ON dt.task_id = t.id 
    GROUP BY t.id 
    ORDER BY usage_count DESC 
    LIMIT 5
");
$stmt->execute();
$popularTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تحويل الوقت إلى تنسيق مقروء
function formatTime($minutes) {
    if($minutes < 60) {
        return $minutes . " دقيقة";
    }
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours . " ساعة " . ($mins > 0 ? "و " . $mins . " دقيقة" : "");
}

// حساب نسبة الإنجاز
$completionRate = $totalTime > 0 ? ($completedTime / $totalTime) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الوقت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: #e9ecef;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(-5px);
        }
        .main-content {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: none;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .progress {
            height: 8px;
            border-radius: 10px;
        }
        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .time-indicator {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- الشريط الجانبي -->
            <div class="col-md-2 sidebar">
                <div class="text-center mb-4">
                    <i class="fas fa-clock fa-2x mb-3"></i>
                    <h4>نظام الوقت</h4>
                </div>
                <a href="dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> لوحة التحكم
                </a>
                <a href="tasks.php">
                    <i class="fas fa-tasks"></i> إدارة المهام
                </a>
                <a href="daily_tasks.php">
                    <i class="fas fa-calendar-day"></i> توزيع المهام اليومية
                </a>
                <a href="reports.php">
                    <i class="fas fa-chart-bar"></i> التقارير والإحصائيات
                </a>
                <a href="../index.php" target="_blank">
                    <i class="fas fa-eye"></i> معاينة الموقع
                </a>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
            </div>
            
            <!-- المحتوى الرئيسي -->
            <div class="col-md-10 main-content">
                <!-- رأس الصفحة -->
                <div class="dashboard-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">لوحة التحكم</h2>
                            <p class="text-muted mb-0">مرحباً بك، <?php echo $_SESSION['username']; ?> - <?php echo date('Y-m-d'); ?></p>
                        </div>
                        <div class="col-md-4 text-start">
                            <div class="time-indicator text-primary">
                                <i class="fas fa-clock"></i> 
                                الوقت المتاح اليوم: <strong><?php echo formatTime(DAILY_TIME_LIMIT - $totalTime); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- بطاقات الإحصائيات -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">الوقت الكلي اليوم</p>
                                    <h3 class="stat-number"><?php echo formatTime($totalTime); ?></h3>
                                </div>
                                <i class="fas fa-clock fa-2x opacity-50"></i>
                            </div>
                            <div class="progress bg-white bg-opacity-25 mt-2">
                                <div class="progress-bar bg-white" style="width: <?php echo min(($totalTime/DAILY_TIME_LIMIT)*100, 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">الوقت المكتمل</p>
                                    <h3 class="stat-number"><?php echo formatTime($completedTime); ?></h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                            <div class="progress bg-white bg-opacity-25 mt-2">
                                <div class="progress-bar bg-white" style="width: <?php echo $completionRate; ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">معدل الإنجاز</p>
                                    <h3 class="stat-number"><?php echo number_format($completionRate, 1); ?>%</h3>
                                </div>
                                <i class="fas fa-chart-line fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">المهام اليوم</p>
                                    <h3 class="stat-number"><?php echo count($dailyTasks); ?></h3>
                                </div>
                                <i class="fas fa-tasks fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <!-- مخطط توزيع الوقت
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-4"><i class="fas fa-chart-pie"></i> توزيع الوقت اليومي</h5>
                            <canvas id="timeDistributionChart" height="250"></canvas>
                        </div>
                    </div> -->
                    
                    <!-- إحصائيات الأسبوع -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-week"></i> إحصائيات الأسبوع</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary"><?php echo $weekStats['total_tasks'] ?? 0; ?></h4>
                                            <small class="text-muted">إجمالي المهام</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-success"><?php echo formatTime($weekStats['total_time'] ?? 0); ?></h4>
                                            <small class="text-muted">إجمالي الوقت</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-info"><?php echo formatTime($weekStats['completed_time'] ?? 0); ?></h4>
                                            <small class="text-muted">الوقت المكتمل</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-warning"><?php echo number_format(($weekStats['completion_rate'] ?? 0) * 100, 1); ?>%</h4>
                                            <small class="text-muted">معدل الإنجاز</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- المهام الأكثر استخداماً -->
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-star"></i> المهام الأكثر استخداماً</h5>
                            </div>
                            <div class="card-body">
                                <?php if(empty($popularTasks)): ?>
                                    <p class="text-muted text-center">لا توجد بيانات كافية</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach($popularTasks as $index => $task): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-primary me-2"><?php echo $index + 1; ?></span>
                                                    <?php echo htmlspecialchars($task['title']); ?>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block"><?php echo formatTime($task['avg_time']); ?></small>
                                                    <span class="badge bg-secondary"><?php echo $task['usage_count']; ?> مرة</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- المهام اليومية -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list-check"></i> مهام اليوم</h5>
                                <a href="daily_tasks.php" class="btn btn-light btn-sm">إدارة المهام اليومية</a>
                            </div>
                            <div class="card-body">
                                <?php if(empty($dailyTasks)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد مهام مخصصة لهذا اليوم</p>
                                        <a href="daily_tasks.php" class="btn btn-primary">توزيع المهام</a>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>المهمة</th>
                                                    <th>الوقت المقدر</th>
                                                    <th>الصعوبة</th>
                                                    <th>الحالة</th>
                                                    <th>التقدم</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($dailyTasks as $task): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($task['title']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary"><?php echo formatTime($task['estimated_time']); ?></span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php 
                                                                if($task['difficulty'] == 'سهلة') echo 'success';
                                                                elseif($task['difficulty'] == 'متوسطة') echo 'warning';
                                                                else echo 'danger';
                                                            ?>"><?php echo $task['difficulty']; ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if($task['completed']): ?>
                                                                <span class="badge bg-success"><i class="fas fa-check"></i> مكتملة</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning"><i class="fas fa-clock"></i> قيد التنفيذ</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 200px;">
                                                            <div class="progress">
                                                                <div class="progress-bar <?php echo $task['completed'] ? 'bg-success' : 'bg-primary'; ?>" 
                                                                     style="width: <?php echo $task['completed'] ? '100%' : '50%'; ?>">
                                                                    <?php echo $task['completed'] ? '100%' : '50%'; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // مخطط توزيع الوقت
        const timeCtx = document.getElementById('timeDistributionChart').getContext('2d');
        const timeChart = new Chart(timeCtx, {
            type: 'doughnut',
            data: {
                labels: ['مكتمل', 'متبقي', 'غير مكتمل'],
                datasets: [{
                    data: [
                        <?php echo $completedTime; ?>,
                        <?php echo DAILY_TIME_LIMIT - $totalTime; ?>,
                        <?php echo $totalTime - $completedTime; ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#17a2b8',
                        '#ffc107'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = <?php echo DAILY_TIME_LIMIT; ?>;
                                const percentage = ((value / total) * 100).toFixed(1);
                                const hours = Math.floor(value / 60);
                                const minutes = value % 60;
                                let timeText = '';
                                if (hours > 0) {
                                    timeText += hours + ' ساعة ';
                                }
                                if (minutes > 0) {
                                    timeText += minutes + ' دقيقة';
                                }
                                return `${context.label}: ${timeText} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // تحديث البيانات كل 30 ثانية
        setInterval(() => {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>