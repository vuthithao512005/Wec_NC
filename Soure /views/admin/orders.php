<div class="container-fluid pt-2 px-4 min-vh-100" style="background-color: #f8fafc;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="fw-bolder text-dark mb-1" style="font-size: 1.6rem;">
                <i class="fa-solid fa-cart-shopping text-primary me-2"></i>Quản lý Đơn hàng
            </h2>
            <p class="text-muted small shadow-sm p-2 bg-white rounded-3 d-inline-block mb-0">
                <i class="fa-solid fa-chart-line text-success me-1"></i> 
                Hệ thống đang ghi nhận <strong><?= count($orders) ?></strong> giao dịch
            </p>
        </div>
        <div>
            <button onclick="window.location.reload()" class="btn btn-white shadow-sm border-0 fw-bold text-secondary px-3 py-2" style="border-radius: 10px;">
                <i class="fa-solid fa-rotate me-1"></i> Làm mới
            </button>
        </div>
    </div>

    <div id="alert-container" style="min-height: 45px; margin-bottom: -10px;">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="mini-alert alert bg-success text-white border-0 shadow-sm py-2 px-4 d-inline-flex align-items-center animate__animated animate__fadeInDown" 
                 style="border-radius: 10px; min-width: 300px; font-size: 0.9rem;">
                <i class="fa-solid fa-circle-check me-2"></i>
                <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm mt-n3" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Doanh thu</th>
                        <th>Trạng thái</th>
                        <th>Ngày giao dịch</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): foreach($orders as $o): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-soft-primary text-primary fw-bold" style="background: #eef2ff;">
                                #ORD-<?= $o['id'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fa-solid fa-user small"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($o['user_name']) ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;">ID: <?= $o['user_id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-dark"><?= number_format($o['total']) ?>đ</td>
                        <td>
                            <?php if($o['status'] == 'paid'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                    <i class="fa-solid fa-check-double me-1"></i> Đã thanh toán
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                    <i class="fa-solid fa-hourglass-half me-1"></i> Chờ duyệt
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <i class="fa-regular fa-calendar me-1"></i> <?= date('d/m/Y', strtotime($o['created_at'])) ?>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary border-0 rounded-circle me-1" 
                                    onclick="viewOrderDetail(<?= $o['id'] ?>)" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="index.php?page=admin_orders&delete_id=<?= $o['id'] ?>" 
                               class="btn btn-sm btn-light text-danger border-0 rounded-circle" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa hóa đơn này chứ?')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy đơn hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOrderDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered shadow-lg">
        <div class="modal-content border-0" style="border-radius: 25px; overflow: hidden;">
            
            <div class="modal-header border-0 bg-primary bg-opacity-10 py-3 px-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="fa-solid fa-file-invoice me-2"></i>Chi tiết hóa đơn <span id="detail_order_id"></span>
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div id="detail_items_list">
                    </div>

                <hr class="my-4 opacity-50">

                <div class="bg-light p-3 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Số lượng khóa học:</span>
                        <span class="fw-bold text-dark" id="detail_quantity">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Phí dịch vụ:</span>
                        <span class="fw-bold text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-2 border-white">
                        <span class="fw-bolder text-dark">TỔNG CỘNG:</span>
                        <span id="detail_total_amount" class="fs-4 fw-bolder text-danger"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary w-100 fw-bold py-2 shadow-sm" style="border-radius: 12px;" data-bs-dismiss="modal">
                    Đóng cửa sổ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        let alert = document.querySelector('.mini-alert');
        if (alert) {
            alert.style.transition = '0.5s opacity, 0.5s transform';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.remove();
                document.getElementById('alert-container').style.minHeight = '0px'; // Thu hẹp khoảng trống sau khi ẩn
            }, 500);
        }
    }, 3000);
});

function viewOrderDetail(orderId) {
    document.getElementById('detail_order_id').innerText = '#' + orderId;
    const listContainer = document.getElementById('detail_items_list');
    const totalContainer = document.getElementById('detail_total_amount');
    const quantityContainer = document.getElementById('detail_quantity');
    
    // Hiệu ứng Loading
    listContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted small">Đang truy xuất dữ liệu...</p></div>';

    fetch('index.php?page=admin_order_detail&id=' + orderId)
    .then(response => response.json())
    .then(items => {
        listContainer.innerHTML = '';
        let total = 0;
        
        items.forEach((item, index) => {
            let price = parseFloat(item.price);
            total += price;
            
            // Layout từng món hàng như một item list chuyên nghiệp
            listContainer.innerHTML += `
                <div class="d-flex align-items-center p-3 mb-2 bg-white border rounded-4 hover-shadow transition-all">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">${item.course_name}</h6>
                        <span class="text-muted" style="font-size: 0.75rem;">Mã khóa học: #${item.course_id}</span>
                    </div>
                    <div class="text-end">
                        <span class="fw-bolder text-primary text-nowrap">${new Intl.NumberFormat().format(price)}đ</span>
                    </div>
                </div>
            `;
        });
        
        quantityContainer.innerText = items.length;
        totalContainer.innerText = new Intl.NumberFormat().format(total) + 'đ';
        new bootstrap.Modal(document.getElementById('modalOrderDetail')).show();
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi hệ thống: Không thể kết nối dữ liệu đơn hàng!');
    });
}
</script>