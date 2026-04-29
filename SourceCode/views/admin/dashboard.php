<div class="dashboard-header pt-1 px-4">
    <h2 class="fw-bold mb-0" style="font-size: 1.6rem;">📊 Dashboard Overview</h2>
    <p class="text-muted mb-3 small">Báo cáo tình hình hệ thống E-Learning</p>
</div>

<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat {
        position: relative;
        background: #fff;
        padding: 22px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        transition: 0.3s ease;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .stat:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); }
    .icon-box {
        width: 46px; height: 46px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 20px; margin-bottom: 15px;
    }
    .stat h2 { font-size: 28px; font-weight: 800; margin: 0; color: #111827; }
    .stat p { margin: 2px 0 0; color: #6b7280; font-size: 14px; font-weight: 500; }

    /* Gradient Colors */
    .users .icon-box { background: linear-gradient(135deg,#60a5fa,#2563eb); }
    .courses .icon-box { background: linear-gradient(135deg,#34d399,#059669); }
    .orders .icon-box { background: linear-gradient(135deg,#f87171,#dc2626); }
    .revenue .icon-box { background: linear-gradient(135deg,#fbbf24,#d97706); }

    .stat::after {
        content: ""; position: absolute; top: -15px; right: -15px;
        width: 70px; height: 70px; border-radius: 50%; opacity: 0.08;
    }
    .users::after { background: #2563eb; }
    .courses::after { background: #059669; }
    .orders::after { background: #dc2626; }
    .revenue::after { background: #d97706; }

    .chart-section {
        background: white; padding: 25px;
        border-radius: 22px; box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
</style>

<div class="px-4">
    <div class="grid">
        <div class="stat users">
            <div class="icon-box"><i class="fa-solid fa-users"></i></div>
            <h2><?= number_format($data['totalUsers'] ?? 0) ?></h2>
            <p>Học viên</p>
        </div>
        <div class="stat courses">
            <div class="icon-box"><i class="fa-solid fa-graduation-cap"></i></div>
            <h2><?= number_format($data['totalCourses'] ?? 0) ?></h2>
            <p>Khóa học</p>
        </div>
        <div class="stat orders">
            <div class="icon-box"><i class="fa-solid fa-cart-shopping"></i></div>
            <h2><?= number_format($data['totalOrders'] ?? 0) ?></h2>
            <p>Đơn hàng</p>
        </div>
        <div class="stat revenue">
            <div class="icon-box"><i class="fa-solid fa-coins"></i></div>
            <h2><?= number_format($data['totalRevenue'] ?? 0) ?>đ</h2>
            <p>Doanh thu</p>
        </div>
    </div>

    <div class="chart-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">📈 Chỉ số Tăng trưởng Hệ thống</h5>
                <small class="text-muted">Theo dõi biến động đăng ký mới và giá trị đơn hàng thực tế</small>
            </div>
            <select id="timeFilter" class="form-select border-0 bg-light fw-bold" style="width:140px; border-radius: 10px; cursor: pointer;">
                <option value="month">Theo tháng</option>
                <option value="week">Theo tuần</option>
                <option value="year">Theo năm</option>
            </select>
        </div>
        <div style="height: 350px;">
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="chart-section">
                <h5 class="fw-bold mb-3">🏆 Top Khóa học Thịnh hành </h5>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div style="height: 300px;">
                            <canvas id="courseChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Khóa học</th>
                                        <th class="text-center">Số lượng bán</th>
                                        <th class="text-end">Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $topCourses = $data['topCourses'] ?? [];
                                    $totalSales = array_sum(array_column($topCourses, 'total_sales'));
                                    foreach($topCourses as $index => $c): 
                                        $percent = $totalSales > 0 ? round(($c['total_sales'] / $totalSales) * 100, 1) : 0;
                                        $colors = ['#2563eb', '#10b981', '#fbbf24', '#f87171', '#8b5cf6'];
                                    ?>
                                    <tr>
                                        <td>
                                            <i class="fa-solid fa-circle me-2" style="color: <?= $colors[$index % 5] ?>; font-size: 10px;"></i>
                                            <span class="fw-bold"><?= $c['title'] ?></span>
                                        </td>
                                        <td class="text-center fw-bold"><?= $c['total_sales'] ?></td>
                                        <td class="text-end text-muted small"><?= $percent ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartInstance = null;

// ==========================
// 1. BIỂU ĐỒ TĂNG TRƯỞNG (LINE)
// ==========================
function renderChart(raw) {
    const labels = Object.keys(raw);
    const userValues = labels.map(key => raw[key].users);
    const revenueValues = labels.map(key => raw[key].revenue);

    if (chartInstance) { chartInstance.destroy(); }

    const ctx = document.getElementById('userChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Học viên mới',
                    data: userValues,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y',
                    pointRadius: 4
                },
                {
                    label: 'Doanh thu (VNĐ)',
                    data: revenueValues,
                    borderColor: '#fbbf24',
                    backgroundColor: 'rgba(251,191,36,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                y: { type: 'linear', position: 'left', beginAtZero: true, title: { display: true, text: 'Học viên' } },
                y1: { 
                    type: 'linear', position: 'right', beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { callback: v => v.toLocaleString('vi-VN') + 'đ' }
                }
            }
        }
    });
}

// ==========================
// 2. BIỂU ĐỒ KHÓA HỌC (DOUGHNUT)
// ==========================
function renderCourseChart() {
    const courseData = <?= json_encode($data['topCourses'] ?? []) ?>;
    const labels = courseData.map(c => c.title);
    const values = courseData.map(c => c.total_sales);

    const ctx = document.getElementById('courseChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: ['#2563eb', '#10b981', '#fbbf24', '#f87171', '#8b5cf6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } } // Ẩn vì đã có bảng danh sách bên cạnh
        }
    });
}

async function loadChart(type = 'month') {
    try {
        const res = await fetch(`index.php?page=admin&type=${type}&ajax=1`);
        const data = await res.json();
        renderChart(data);
    } catch (err) { console.error(err); }
}

document.addEventListener("DOMContentLoaded", function () {
    const filter = document.getElementById("timeFilter");
    loadChart(filter.value);
    renderCourseChart();
    filter.addEventListener("change", function () { loadChart(this.value); });
});
</script>