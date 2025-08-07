<?php
/**
 * Company Management - Create Company
 */
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มบริษัทใหม่ - CRM SalesTracker</title>
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
                        <i class="fas fa-plus-circle me-2"></i>
                        เพิ่มบริษัทใหม่
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="admin.php?action=companies" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>กลับไปรายการบริษัท
                        </a>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">ข้อมูลบริษัท</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="admin.php?action=companies_store">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">ชื่อบริษัท <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company_code" class="form-label">รหัสบริษัท (ถ้ามี)</label>
                                    <input type="text" class="form-control" id="company_code" name="company_code">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">ที่อยู่</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>
                             <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="admin.php?action=companies" class="btn btn-secondary me-2">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
