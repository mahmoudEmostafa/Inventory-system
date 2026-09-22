<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Infrastructure\Database\Database;

$dotenv = Dotenv\Dotenv::createImmutable(
    dirname(__DIR__, 2)
);

$dotenv->load();

$database = new Database();

$connection = $database->getConnection();

echo "Database connected successfully." . PHP_EOL;



function seedUsers(PDO $connection): void
{
    $statement = $connection->prepare(
        'INSERT INTO users
            (name, username, password, role)
         VALUES
            (:name, :username, :password, :role)'
    );

    $users = [
        [
            'name' => 'System Admin',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
        ],
        [
            'name' => 'Warehouse Employee',
            'username' => 'employee',
            'password' => 'employee123',
            'role' => 'employee',
        ],
    ];

    foreach ($users as $user) {
        $statement->execute([
            'name' => $user['name'],
            'username' => $user['username'],
            'password' => password_hash(
                $user['password'],
                PASSWORD_DEFAULT
            ),
            'role' => $user['role'],
        ]);
    }

    echo "Users seeded." . PHP_EOL;
}




function seedSuppliers(PDO $connection): void
{
    $statement = $connection->prepare(
        'INSERT INTO suppliers
            (name, phone, address, is_active)
         VALUES
            (:name, :phone, :address, :is_active)'
    );

    $suppliers = [
        [
            'name' => 'Al Noor Building Materials',
            'phone' => '0111111111',
            'address' => 'Damascus',
        ],
        [
            'name' => 'Damascus Steel Company',
            'phone' => '0222222222',
            'address' => 'Damascus',
        ],
        [
            'name' => 'Modern Cement Supplier',
            'phone' => '0333333333',
            'address' => 'Homs',
        ],
    ];

    foreach ($suppliers as $supplier) {
        $statement->execute([
            'name' => $supplier['name'],
            'phone' => $supplier['phone'],
            'address' => $supplier['address'],
            'is_active' => true,
        ]);
    }

    echo "Suppliers seeded." . PHP_EOL;
}




function seedCustomers(PDO $connection): void
{
    $statement = $connection->prepare(
        'INSERT INTO customers
            (name, phone, address, is_active)
         VALUES
            (:name, :phone, :address, :is_active)'
    );

    $customers = [
        [
            'name' => 'Al Amal Trading',
            'phone' => '0444444444',
            'address' => 'Damascus',
        ],
        [
            'name' => 'Al Baraka Construction',
            'phone' => '0555555555',
            'address' => 'Aleppo',
        ],
        [
            'name' => 'Future Builders',
            'phone' => '0666666666',
            'address' => 'Homs',
        ],
    ];

    foreach ($customers as $customer) {
        $statement->execute([
            'name' => $customer['name'],
            'phone' => $customer['phone'],
            'address' => $customer['address'],
            'is_active' => true,
        ]);
    }

    echo "Customers seeded." . PHP_EOL;
}




function seedProducts(PDO $connection): void
{
    $statement = $connection->prepare(
        'INSERT INTO products
            (code, name, unit, is_active)
         VALUES
            (:code, :name, :unit, :is_active)'
    );

    $products = [
        [
            'code' => 'CEM-001',
            'name' => 'Portland Cement',
            'unit' => 'ton',
        ],
        [
            'code' => 'STE-001',
            'name' => 'Steel Bars',
            'unit' => 'ton',
        ],
        [
            'code' => 'SAN-001',
            'name' => 'Fine Sand',
            'unit' => 'ton',
        ],
        [
            'code' => 'BRI-001',
            'name' => 'Building Bricks',
            'unit' => 'piece',
        ],
    ];

    foreach ($products as $product) {
        $statement->execute([
            'code' => $product['code'],
            'name' => $product['name'],
            'unit' => $product['unit'],
            'is_active' => true,
        ]);
    }

    echo "Products seeded." . PHP_EOL;
}



function seedWarehouses(PDO $connection): void
{
    $statement = $connection->prepare(
        'INSERT INTO warehouses
            (name, location, is_active)
         VALUES
            (:name, :location, :is_active)'
    );

    $warehouses = [
        [
            'name' => 'Main Warehouse',
            'location' => 'Damascus Industrial Area',
        ],
        [
            'name' => 'Secondary Warehouse',
            'location' => 'Aleppo Industrial Area',
        ],
    ];

    foreach ($warehouses as $warehouse) {
        $statement->execute([
            'name' => $warehouse['name'],
            'location' => $warehouse['location'],
            'is_active' => true,
        ]);
    }

    echo "Warehouses seeded." . PHP_EOL;
}



seedUsers($connection);
seedSuppliers($connection);
seedCustomers($connection);
seedProducts($connection);
seedWarehouses($connection);

echo PHP_EOL;
echo "Demo data seeded successfully." . PHP_EOL;