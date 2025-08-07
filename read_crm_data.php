<?php
// Script to connect to the CRM database and read its data.

// --- Database Configuration ---
// Credentials from .kiro/specs/crm-salestracker/tasks.md
$host = 'localhost';
$dbname = 'primacom_Customer';
$username = 'primacom_bloguser';
$password = 'pJnL53Wkhju2LaGPytw8';
$charset = 'utf8mb4';

// --- Helper Functions ---
function print_header($title) {
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "=== " . strtoupper($title) . "\n";
    echo str_repeat('=', 80) . "\n\n";
}

function print_table(array $data) {
    if (empty($data)) {
        echo "No data to display.\n";
        return;
    }

    // Get headers
    $headers = array_keys($data[0]);
    $columnWidths = array_map('strlen', $headers);

    // Calculate column widths
    foreach ($data as $row) {
        foreach ($row as $key => $value) {
            $value = (is_null($value)) ? 'NULL' : $value;
            if (strlen($value) > $columnWidths[$key]) {
                $columnWidths[$key] = strlen($value);
            }
        }
    }

    // Print header
    $headerLine = "|";
    foreach ($headers as $i => $header) {
        $headerLine .= " " . str_pad($header, $columnWidths[$i]) . " |";
    }
    echo $headerLine . "\n";

    // Print separator
    $separatorLine = "|";
    foreach ($columnWidths as $width) {
        $separatorLine .= str_repeat('-', $width + 2) . "|";
    }
    echo $separatorLine . "\n";

    // Print rows
    foreach ($data as $row) {
        $rowLine = "|";
        foreach ($row as $key => $value) {
            $value = (is_null($value)) ? 'NULL' : $value;
            $rowLine .= " " . str_pad($value, $columnWidths[$key]) . " |";
        }
        echo $rowLine . "\n";
    }
}


// --- Main Execution ---

print_header("CRM Data Reader");

echo "Connecting to database '{$dbname}' on '{$host}'...\n";

try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "✅ Connection successful!\n";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 1. List all tables
print_header("Tables in Database");
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "- " . $table . "\n";
    }
} catch (PDOException $e) {
    echo "❌ Failed to list tables: " . $e->getMessage() . "\n";
}

// 2. Summarize key tables
print_header("Key Table Summaries");
$key_tables = ['users', 'customers', 'products', 'orders', 'customer_activities', 'call_logs'];
foreach ($key_tables as $table) {
    if (in_array($table, $tables)) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
            $count = $stmt->fetchColumn();
            echo "- Table '{$table}': {$count} rows.\n";
        } catch (PDOException $e) {
            echo "❌ Failed to get row count for '{$table}': " . $e->getMessage() . "\n";
        }
    } else {
        echo "- Table '{$table}': Not found.\n";
    }
}

// 3. Show sample data from each table
print_header("Sample Data from Tables");
foreach ($tables as $table) {
    echo "\n--- Sample data from '{$table}' (LIMIT 5) ---\n";
    try {
        $stmt = $pdo->query("SELECT * FROM {$table} LIMIT 5");
        $results = $stmt->fetchAll();
        if (empty($results)) {
            echo "Table is empty.\n";
        } else {
            print_table($results);
        }
    } catch (PDOException $e) {
        echo "❌ Failed to fetch sample data for '{$table}': " . $e->getMessage() . "\n";
    }
}

echo "\n" . str_repeat('=', 80) . "\n";
echo "=== " . "DATA READING COMPLETE" . "\n";
echo str_repeat('=', 80) . "\n";

?>
