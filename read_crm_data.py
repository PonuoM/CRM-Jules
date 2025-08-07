import mysql.connector
from mysql.connector import errorcode

# --- Database Configuration ---
# Credentials from .kiro/specs/crm-salestracker/tasks.md
DB_CONFIG = {
    'user': 'primacom_bloguser',
    'password': 'pJnL53Wkhju2LaGPytw8',
    'host': 'localhost',
    'database': 'primacom_Customer',
    'raise_on_warnings': True,
    'use_pure': False, # C-connector is faster
}

# --- Helper Functions ---
def print_header(title):
    """Prints a formatted header to the console."""
    print("\n" + "=" * 80)
    print(f"=== {title.upper()}")
    print("=" * 80 + "\n")

def print_table(rows, headers):
    """Prints a list of dictionary rows in a formatted table."""
    if not rows:
        print("No data to display.")
        return

    # Calculate column widths
    column_widths = {header: len(header) for header in headers}
    for row in rows:
        for i, header in enumerate(headers):
            cell_value = str(row[i]) if row[i] is not None else 'NULL'
            if len(cell_value) > column_widths[header]:
                column_widths[header] = len(cell_value)

    # Print header
    header_line = "|"
    for header in headers:
        header_line += f" {header.ljust(column_widths[header])} |"
    print(header_line)

    # Print separator
    separator_line = "|"
    for header in headers:
        separator_line += "-" * (column_widths[header] + 2) + "|"
    print(separator_line)

    # Print rows
    for row in rows:
        row_line = "|"
        for i, header in enumerate(headers):
            cell_value = str(row[i]) if row[i] is not None else 'NULL'
            row_line += f" {cell_value.ljust(column_widths[header])} |"
        print(row_line)

# --- Main Execution ---
def main():
    """Main function to connect to the DB and read data."""
    print_header("CRM Data Reader (Python Version)")

    try:
        cnx = mysql.connector.connect(**DB_CONFIG)
        cursor = cnx.cursor()
        print(f"✅ Connection to database '{DB_CONFIG['database']}' successful!")
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("❌ Something is wrong with your user name or password")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print("❌ Database does not exist")
        else:
            print(f"❌ {err}")
        return # Exit if connection fails

    # 1. List all tables
    print_header("Tables in Database")
    try:
        cursor.execute("SHOW TABLES")
        tables = [table[0] for table in cursor.fetchall()]
        print(f"Found {len(tables)} tables:")
        for table in tables:
            print(f"- {table}")
    except mysql.connector.Error as err:
        print(f"❌ Failed to list tables: {err}")
        tables = []

    # 2. Summarize key tables
    print_header("Key Table Summaries")
    key_tables = ['users', 'customers', 'products', 'orders', 'customer_activities', 'call_logs']
    for table in key_tables:
        if table in tables:
            try:
                cursor.execute(f"SELECT COUNT(*) FROM {table}")
                count = cursor.fetchone()[0]
                print(f"- Table '{table}': {count} rows.")
            except mysql.connector.Error as err:
                print(f"❌ Failed to get row count for '{table}': {err}")
        else:
            print(f"- Table '{table}': Not found.")

    # 3. Show sample data from each table
    print_header("Sample Data from Tables")
    for table in tables:
        print(f"\n--- Sample data from '{table}' (LIMIT 5) ---")
        try:
            cursor.execute(f"SELECT * FROM {table} LIMIT 5")
            rows = cursor.fetchall()
            if not rows:
                print("Table is empty.")
            else:
                headers = [i[0] for i in cursor.description]
                print_table(rows, headers)
        except mysql.connector.Error as err:
            print(f"❌ Failed to fetch sample data for '{table}': {err}")

    cursor.close()
    cnx.close()
    print("\n✅ Database connection closed.")

    print("\n" + "=" * 80)
    print("=== DATA READING COMPLETE ===")
    print("=" * 80)


if __name__ == "__main__":
    main()
