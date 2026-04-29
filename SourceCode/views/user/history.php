<div class="container mt-5 mb-5">
    
    <div class="d-flex align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i> Lịch sử làm bài</h3>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3">Tên bài học</th>
                        <th class="py-3 text-center">Điểm số</th>
                        <th class="py-3">Thời gian nộp</th>
                        <th class="py-3 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($results)): ?>
                        <?php foreach($results as $index => $r): ?>
                        <tr>
                            <td class="px-4 fw-bold text-muted"><?= $index + 1 ?></td>
                            
                            <td class="fw-bold">
                                <?= htmlspecialchars($r['lesson_name'] ?? 'Bài học số ' . $r['lesson_id']) ?>
                            </td>
                            
                            <td class="text-center">
                                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">
                                    <?= $r['score'] ?> Điểm
                                </span>
                            </td>
                            
                            <td class="text-muted">
                                <?= !empty($r['created_at']) ? date('d/m/Y - H:i', strtotime($r['created_at'])) : '---' ?>
                            </td>

                            <td class="text-center">
                                <a href="index.php?page=quiz&lesson_id=<?= $r['lesson_id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Làm lại
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-1 mb-3 text-light"></i>
                                <h5>Bạn chưa làm bài kiểm tra nào</h5>
                                <a href="index.php?page=courses" class="btn btn-primary mt-2 rounded-pill">Đi học ngay</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover { background-color: #f8faff; }
</style>