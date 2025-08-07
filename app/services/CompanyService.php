<?php
/**
 * CompanyService Class
 * Handles business logic for company management.
 */

class CompanyService {
    private $db;

    public function __construct() {
        // It's better to inject the database dependency, but for consistency
        // with other services in this project, we'll create it here.
        if (!class_exists('Database')) {
            require_once __DIR__ . '/../core/Database.php';
        }
        $this->db = new Database();
    }

    /**
     * Get all companies from the database.
     * @return array An array of all companies.
     */
    public function getAllCompanies() {
        $sql = "SELECT * FROM companies ORDER BY company_name ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get a single company by its ID.
     * @param int $companyId The ID of the company.
     * @return array|null The company data or null if not found.
     */
    public function getCompanyById($companyId) {
        $sql = "SELECT * FROM companies WHERE company_id = :company_id";
        return $this->db->fetchOne($sql, ['company_id' => $companyId]);
    }

    /**
     * Create a new company.
     * @param array $companyData The data for the new company.
     * @return array An array with success status and message.
     */
    public function createCompany($companyData) {
        try {
            // Basic validation
            if (empty($companyData['company_name'])) {
                return ['success' => false, 'message' => 'ชื่อบริษัทห้ามว่าง'];
            }

            $sql = "INSERT INTO companies (company_name, company_code, address, phone, email, is_active, created_at)
                    VALUES (:company_name, :company_code, :address, :phone, :email, 1, NOW())";

            $this->db->query($sql, [
                'company_name' => $companyData['company_name'],
                'company_code' => $companyData['company_code'] ?? null,
                'address' => $companyData['address'] ?? null,
                'phone' => $companyData['phone'] ?? null,
                'email' => $companyData['email'] ?? null,
            ]);

            return ['success' => true, 'message' => 'สร้างบริษัทใหม่สำเร็จ'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดในการสร้างบริษัท: ' . $e->getMessage()];
        }
    }

    /**
     * Update an existing company.
     * @param array $companyData The data to update.
     * @return array An array with success status and message.
     */
    public function updateCompany($companyData) {
        try {
            // Basic validation
            if (empty($companyData['company_id']) || empty($companyData['company_name'])) {
                return ['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน'];
            }

            $sql = "UPDATE companies SET
                        company_name = :company_name,
                        company_code = :company_code,
                        address = :address,
                        phone = :phone,
                        email = :email,
                        is_active = :is_active
                    WHERE company_id = :company_id";

            $this->db->query($sql, [
                'company_id' => $companyData['company_id'],
                'company_name' => $companyData['company_name'],
                'company_code' => $companyData['company_code'] ?? null,
                'address' => $companyData['address'] ?? null,
                'phone' => $companyData['phone'] ?? null,
                'email' => $companyData['email'] ?? null,
                'is_active' => $companyData['is_active'] ?? 1,
            ]);

            return ['success' => true, 'message' => 'อัปเดตข้อมูลบริษัทสำเร็จ'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดตบริษัท: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a company.
     * @param int $companyId The ID of the company to delete.
     * @return array An array with success status and message.
     */
    public function deleteCompany($companyId) {
        try {
            // Check if any user is associated with this company
            $sql = "SELECT COUNT(*) as count FROM users WHERE company_id = :company_id";
            $result = $this->db->fetchOne($sql, ['company_id' => $companyId]);

            if ($result['count'] > 0) {
                return ['success' => false, 'message' => 'ไม่สามารถลบบริษัทได้ เนื่องจากมีผู้ใช้งานอยู่ในบริษัทนี้'];
            }

            $sql = "DELETE FROM companies WHERE company_id = :company_id";
            $this->db->query($sql, ['company_id' => $companyId]);

            return ['success' => true, 'message' => 'ลบบริษัทสำเร็จ'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบบริษัท: ' . $e->getMessage()];
        }
    }
}
?>
