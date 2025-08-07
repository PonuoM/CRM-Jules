<?php
/**
 * Company Management - List Companies
 */
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการบริษัท - CRM SalesTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../components/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . '/../../components/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-building me-2"></i>
                        จัดการบริษัท
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="admin.php?action=companies_create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>เพิ่มบริษัทใหม่
                        </a>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php
                            $messages = [
                                'company_created' => 'สร้างบริษัทใหม่สำเร็จ',
                                'company_updated' => 'อัปเดตข้อมูลบริษัทสำเร็จ',
                                'company_deleted' => 'ลบบริษัทสำเร็จ'
                            ];
                            echo $messages[$_GET['message']] ?? 'ดำเนินการสำเร็จ';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['error'])): ?>
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Companies Table -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list me-2"></i>รายการบริษัททั้งหมด
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>ชื่อบริษัท</th>
                                        <th>รหัสบริษัท</th>
                                        <th>เบอร์โทร</th>
                                        <th>อีเมล</th>
                                        <th>สถานะ</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($companies)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">ยังไม่มีข้อมูลบริษัท</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($companies as $company): ?>
                                        <tr>
                                            <td><?php echo $company['company_id']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($company['company_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($company['company_code'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($company['phone'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($company['email'] ?? '-'); ?></td>
                                            <td>
                                                <?php if ($company['is_active']): ?>
                                                    <span class="badge bg-success">เปิดใช้งาน</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">ปิดใช้งาน</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="admin.php?action=companies_edit&id=<?php echo $company['company_id']; ?>"
                                                       class="btn btn-sm btn-outline-primary" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="admin.php?action=companies_delete&id=<?php echo $company['company_id']; ?>"
                                                       class="btn btn-sm btn-outline-danger" title="ลบ"
                                                       onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบบริษัทนี้? การกระทำนี้ไม่สามารถย้อนกลับได้')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
