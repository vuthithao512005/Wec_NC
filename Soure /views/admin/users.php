<div class="container-fluid pt-2 pb-5 px-4 min-vh-100" style="background-color: #f8fafc;">
    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white;">
                <i class="fa-solid fa-users-gear fs-5"></i>
            </div>
            <div>
                <h2 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Quản lý Người dùng</h2>
                <p class="text-muted small mb-0 fw-medium">Kiểm soát tài khoản Admin và Học viên</p>
            </div>
        </div>

        <div>
            <button class="btn btn-primary fw-bold py-2 px-4 border-0 shadow-sm transition-hover" style="border-radius: 12px; background: #3b82f6;" data-bs-toggle="modal" data-bs-target="#modalAddUser" onclick="resetUserForm()">
                <i class="fa-solid fa-user-plus me-2"></i> Thêm tài khoản
            </button>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert bg-success text-white shadow-sm border-0 py-2 px-3 mb-4 d-inline-flex align-items-center mini-alert" style="border-radius: 12px;">
            <i class="fa-solid fa-circle-check me-2 fs-5"></i>
            <span class="small fw-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert bg-danger text-white shadow-sm border-0 py-2 px-3 mb-4 d-inline-flex align-items-center mini-alert" style="border-radius: 12px;">
            <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i>
            <span class="small fw-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-2 px-3">
            <form method="GET" class="row g-2 align-items-center m-0 w-100">
                <input type="hidden" name="page" value="admin_users">
                
                <div class="col-12 col-xl-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center bg-light rounded-3 px-3 flex-grow-1" style="height: 42px; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0'">
                            <span class="text-muted small fw-bold me-2">Từ:</span>
                            <input type="date" name="from_date" class="form-control border-0 shadow-none bg-transparent p-0 fw-medium text-dark w-100" title="Từ ngày" value="<?= $_GET['from_date'] ?? '' ?>" style="font-size: 0.95rem; outline: none;" onchange="this.form.submit()">
                        </div>
                        <div class="text-muted opacity-50"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="d-flex align-items-center bg-light rounded-3 px-3 flex-grow-1" style="height: 42px; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0'">
                            <span class="text-muted small fw-bold me-2">Đến:</span>
                            <input type="date" name="to_date" class="form-control border-0 shadow-none bg-transparent p-0 fw-medium text-dark w-100" title="Đến ngày" value="<?= $_GET['to_date'] ?? '' ?>" style="font-size: 0.95rem; outline: none;" onchange="this.form.submit()">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8 col-xl-4">
                    <div class="d-flex align-items-center bg-light rounded-3 px-3 w-100" style="height: 42px; border: 1px solid #e2e8f0; transition: 0.3s;" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <i class="fa-solid fa-shield-halved text-muted pe-2"></i>
                        <select name="role" class="form-select border-0 shadow-none bg-transparent px-0 fw-medium w-100" style="font-size: 0.95rem;" onchange="this.form.submit()">
                            <option value="">-- Tất cả vai trò --</option>
                            <option value="admin" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                            <option value="user" <?= (isset($_GET['role']) && $_GET['role'] === 'user') ? 'selected' : '' ?>>Học viên (User)</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-xl-2 d-flex gap-1 justify-content-end">
                    <button type="submit" class="btn btn-dark transition-hover d-flex justify-content-center align-items-center" style="border-radius: 10px; height: 42px; width: 45px;" title="Áp dụng bộ lọc">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <?php if(!empty($_GET['from_date']) || !empty($_GET['to_date']) || (isset($_GET['role']) && $_GET['role'] !== '')): ?>
                        <a href="index.php?page=admin_users" class="btn btn-light border-0 transition-hover d-flex justify-content-center align-items-center" style="border-radius: 10px; background: #f1f5f9; height: 42px; width: 45px;" title="Bỏ lọc">
                            <i class="fa-solid fa-xmark text-muted fs-5"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-table">
                <thead class="bg-light text-muted" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3 border-bottom-0">ID</th>
                        <th class="border-bottom-0">Người dùng</th>
                        <th class="border-bottom-0">Liên hệ</th>
                        <th class="border-bottom-0 text-center">Vai trò</th>
                        <th class="border-bottom-0 text-center">Trạng thái</th>
                        <th class="border-bottom-0 text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(!empty($users)): foreach($users as $u): ?>
                    <tr>
                        <td class="ps-4 text-muted fw-bold small">#<?= $u['id'] ?></td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <?php $initial = mb_substr($u['name'], 0, 1, "UTF-8"); ?>
                                <div class="avatar-circle me-3 d-flex justify-content-center align-items-center fw-bold text-white shadow-sm" style="background-color: <?= ($u['role'] == 1 || $u['role'] === 'admin') ? '#ef4444' : '#3b82f6' ?>;">
                                    <?= mb_strtoupper($initial, "UTF-8") ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($u['name']) ?></div>
                                    <div class="small text-muted">Tham gia: <?= date('d/m/Y', strtotime($u['created_at'] ?? 'now')) ?></div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <div class="fw-medium text-dark" style="font-size: 0.9rem;"><i class="fa-regular fa-envelope text-muted me-1"></i> <?= htmlspecialchars($u['email']) ?></div>
                        </td>
                        
                        <td class="text-center">
                            <?php if($u['role'] == 1 || $u['role'] === 'admin'): ?>
                                <span class="badge bg-soft-danger text-danger border border-danger border-opacity-10 px-3 py-2 rounded-pill"><i class="fa-solid fa-crown me-1"></i> Admin</span>
                            <?php else: ?>
                                <span class="badge bg-soft-primary text-primary border border-primary border-opacity-10 px-3 py-2 rounded-pill"><i class="fa-solid fa-graduation-cap me-1"></i> Học viên</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php $status = $u['status'] ?? 1; ?>
                            <?php if($status == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded"><i class="fa-solid fa-circle-dot small me-1" style="font-size: 0.5rem;"></i>Hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded"><i class="fa-solid fa-lock small me-1" style="font-size: 0.5rem;"></i>Bị khóa</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary border-0 fw-bold me-1 hover-scale" onclick="openEditUser(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)" title="Sửa thông tin">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <a href="index.php?page=admin_users&delete_id=<?= $u['id'] ?>" 
                               class="btn btn-sm btn-light text-danger border-0 fw-bold hover-scale" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')" 
                               title="Xóa tài khoản">
                               <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fa-solid fa-users-slash fs-1 opacity-25 mb-3"></i>
                                <h6 class="fw-bold">Không tìm thấy người dùng</h6>
                                <p class="small">Chưa có dữ liệu hoặc không có kết quả phù hợp với điều kiện lọc.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="index.php?page=admin_users" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <input type="hidden" name="id" id="f_user_id">
            
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bolder text-dark mb-0" id="modalUserTitle"><i class="fa-solid fa-user-plus text-primary me-2"></i>Thêm tài khoản mới</h5>
                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4 pt-2">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="fw-bold small text-muted mb-2">Họ và tên <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center bg-light rounded-3 px-3 w-100" style="height: 42px; border: 1px solid #e2e8f0;">
                            <i class="fa-solid fa-user text-muted pe-2"></i>
                            <input type="text" name="fullname" id="f_fullname" class="form-control border-0 shadow-none bg-transparent px-0" placeholder="Nhập họ tên đầy đủ..." required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="fw-bold small text-muted mb-2">Email đăng nhập <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center bg-light rounded-3 px-3 w-100" style="height: 42px; border: 1px solid #e2e8f0;">
                            <i class="fa-solid fa-envelope text-muted pe-2"></i>
                            <input type="email" name="email" id="f_email" class="form-control border-0 shadow-none bg-transparent px-0" placeholder="example@gmail.com" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="fw-bold small text-muted mb-2">Mật khẩu <span id="pwd_req" class="text-danger">*</span></label>
                        <div class="d-flex align-items-center bg-light rounded-3 px-3 w-100" style="height: 42px; border: 1px solid #e2e8f0;">
                            <i class="fa-solid fa-lock text-muted pe-2"></i>
                            <input type="password" name="password" id="f_password" class="form-control border-0 shadow-none bg-transparent px-0" placeholder="Nhập mật khẩu (để trống nếu không đổi)" required>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;" id="pwd_hint">Tối thiểu 6 ký tự.</small>
                    </div>

                    <div class="col-12 mt-4">
                        <label class="fw-bold small text-muted mb-2">Phân quyền tài khoản</label>
                        <div class="d-flex gap-3">
                            <div class="form-check custom-radio-card flex-grow-1 border rounded-3 p-2 ps-3 transition-hover bg-light">
                                <input class="form-check-input" type="radio" name="role" id="role_user" value="user" checked required>
                                <label class="form-check-label fw-bold text-dark w-100 ps-2" style="cursor:pointer;" for="role_user">
                                    Học viên (User)
                                </label>
                            </div>
                            <div class="form-check custom-radio-card flex-grow-1 border rounded-3 p-2 ps-3 transition-hover bg-light">
                                <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin" required>
                                <label class="form-check-label fw-bold text-danger w-100 ps-2" style="cursor:pointer;" for="role_admin">
                                    Quản trị (Admin)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12" id="status_group" style="display: none;">
                        <label class="fw-bold small text-muted mb-2 mt-2">Trạng thái hoạt động</label>
                        <select name="status" id="f_status" class="form-select border-light bg-light rounded-3 py-2 shadow-none">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Khóa tài khoản</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pb-4 px-4 bg-white" style="border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-light px-4 fw-bold py-2" data-bs-dismiss="modal" style="border-radius: 12px;">Hủy</button>
                <button type="submit" name="save_user" id="btnSubmitUser" class="btn btn-primary px-5 fw-bold py-2 shadow-sm" style="border-radius: 12px; background: #3b82f6; border: none;">TẠO TÀI KHOẢN</button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: #eef2ff; }
    .bg-soft-danger { background-color: #fef2f2; }
    
    .custom-table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .custom-table tbody tr:hover { background-color: #f8fafc; }
    
    .avatar-circle { width: 42px; height: 42px; border-radius: 50%; font-size: 1.1rem; letter-spacing: 1px; }
    
    .custom-radio-card { border-color: transparent; }
    .custom-radio-card:has(input:checked) { background-color: #eff6ff !important; border-color: #3b82f6 !important; }
    
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); opacity: 0.95; }
    .hover-scale:hover { transform: scale(1.1); }
    
    .mini-alert { animation: slideDown 0.4s ease; transition: opacity 0.5s; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
// Tự ẩn thông báo sau 4 giây
setTimeout(() => {
    const alerts = document.querySelectorAll('.mini-alert');
    alerts.forEach(a => { a.style.opacity = "0"; setTimeout(() => a.remove(), 500); });
}, 4000);

// Reset Form khi bấm Thêm Mới
function resetUserForm() {
    document.getElementById('modalUserTitle').innerHTML = '<i class="fa-solid fa-user-plus text-primary me-2"></i>Thêm tài khoản mới';
    document.getElementById('f_user_id').value = '';
    document.getElementById('f_fullname').value = '';
    document.getElementById('f_email').value = '';
    
    document.getElementById('f_password').required = true;
    document.getElementById('f_password').value = '';
    document.getElementById('f_password').placeholder = 'Nhập mật khẩu (Tối thiểu 6 ký tự)';
    document.getElementById('pwd_req').style.display = 'inline';
    
    document.getElementById('role_user').checked = true;
    document.getElementById('status_group').style.display = 'none'; 
    
    document.getElementById('btnSubmitUser').name = 'save_user';
    document.getElementById('btnSubmitUser').innerText = 'TẠO TÀI KHOẢN';
}

// Đổ dữ liệu vào Form khi bấm Sửa
function openEditUser(u) {
    document.getElementById('modalUserTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-primary me-2"></i>Cập nhật tài khoản';
    document.getElementById('f_user_id').value = u.id;
    document.getElementById('f_fullname').value = u.name; 
    document.getElementById('f_email').value = u.email;
    
    document.getElementById('f_password').required = false;
    document.getElementById('f_password').value = '';
    document.getElementById('f_password').placeholder = 'Bỏ trống nếu không muốn đổi mật khẩu';
    document.getElementById('pwd_req').style.display = 'none';
    
    if(u.role == 1 || u.role === 'admin') {
        document.getElementById('role_admin').checked = true;
    } else {
        document.getElementById('role_user').checked = true;
    }
    
    document.getElementById('status_group').style.display = 'block';
    if(document.getElementById('f_status')) {
        document.getElementById('f_status').value = u.status !== undefined ? u.status : 1;
    }

    document.getElementById('btnSubmitUser').name = 'update_user';
    document.getElementById('btnSubmitUser').innerText = 'LƯU THAY ĐỔI';
    
    var userModal = new bootstrap.Modal(document.getElementById('modalAddUser'));
    userModal.show();
}
</script>