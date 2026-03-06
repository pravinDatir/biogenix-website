<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

       DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Diagnostics', 'slug' => 'diagnostics', 'description' => 'Laboratory diagnostic equipment and testing systems.', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Imaging', 'slug' => 'imaging', 'description' => 'Medical imaging devices for radiology and diagnostic scans.', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Cardiology', 'slug' => 'cardiology', 'description' => 'Devices and systems used for heart monitoring and cardiac diagnostics.', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Consumables', 'slug' => 'consumables', 'description' => 'Single-use medical supplies and laboratory consumable products.', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('subcategories')->insert([
            ['id' => 1, 'category_id' => 1, 'name' => 'Hematology', 'slug' => 'hematology', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'category_id' => 1, 'name' => 'Biochemistry', 'slug' => 'biochemistry', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'category_id' => 2, 'name' => 'Digital X-Ray', 'slug' => 'digital-xray', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'category_id' => 2, 'name' => 'Ultrasound', 'slug' => 'ultrasound', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'category_id' => 3, 'name' => 'ECG Systems', 'slug' => 'ecg-systems', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'category_id' => 4, 'name' => 'Gloves', 'slug' => 'gloves', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'category_id' => 4, 'name' => 'Syringes', 'slug' => 'syringes', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('product_specifications')->insert([
            ['id' => 1, 'specs' => '{"Throughput":"60 tests/hour","Sample Volume":"20uL","Display":"7-inch LCD"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'specs' => '{"Tests":"120/hour","Cooling":"Yes","Connectivity":"LIS"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'specs' => '{"Detector Type":"Flat Panel","Resolution":"2048 x 2048 pixels","Power":"220V"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'specs' => '{"Display":"15-inch Touchscreen","Modes":"2D, Doppler, M-Mode"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'specs' => '{"Leads":"12-lead","Display":"5-inch LCD","Battery":"Rechargeable"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'specs' => '{"Material":"Nitrile","Powder Free":"Yes","Usage":"Single use"}', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'specs' => '{"Sterile":"Yes","Capacity":"5ml","Pack Size":"50"}', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('product_image')->insert([
            ['id' => 1,'file_path' => 'images/hema1.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'file_path' => 'images/hema2.jpg', 'is_primary' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3,  'file_path' => 'images/image1.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'file_path' => 'images/image2.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'file_path' => 'images/image3.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'file_path' => 'images/image4.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'file_path' => 'images/image5.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8,  'file_path' => 'images/image1.jpg', 'is_primary' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'file_path' => 'images/image2.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('products')->insert([
            ['id' => 1, 'name' => 'Hematology Analyzer X200', 'slug' => 'hematology-analyzer-x200', 'description' => 'High-precision hematology analyzer for labs.', 'category_id' => 1, 'subcategory_id' => 1, 'base_sku' => 'BIO-HEMA', 'is_published' => true, 'product_image_id' => 1, 'product_specifications_id' => 1, 'sku' => 'BIO-HEMA-X200', 'brand' => 'Biogenix', 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Biochemistry Analyzer B500', 'slug' => 'biochemistry-analyzer-b500', 'description' => 'Fully automated biochemistry analyzer.', 'category_id' => 1, 'subcategory_id' => 2, 'base_sku' => 'BIO-BIOC', 'is_published' => true, 'product_image_id' => 2, 'product_specifications_id' => 2, 'sku' => 'BIO-BIOC-B500', 'brand' => 'Biogenix', 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Digital X-Ray Machine DXR-1000', 'slug' => 'digital-xray-machine-dxr-1000', 'description' => 'High-resolution digital X-ray imaging system.', 'category_id' => 2, 'subcategory_id' => 3, 'base_sku' => 'BIO-DXR', 'is_published' => true, 'product_image_id' => 3, 'product_specifications_id' => 3, 'sku' => 'BIO-DXR-1000', 'brand' => 'Biogenix', 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Ultrasound Machine UltraSound Pro', 'slug' => 'ultrasound-machine-ultrasound-pro', 'description' => 'Advanced ultrasound system for detailed imaging.', 'category_id' => 2, 'subcategory_id' => 4, 'base_sku' => 'BIO-USP', 'is_published' => true, 'product_image_id' => 4, 'product_specifications_id' => 4, 'sku' => 'BIO-USP-PRO', 'brand' => 'Biogenix', 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'ECG Machine CardioMax 3000', 'slug' => 'ecg-machine-cardiomax-3000', 'description' => 'Portable ECG machine with advanced features.', 'category_id' => 3, 'subcategory_id' => 5, 'base_sku' => 'BIO-ECG', 'is_published' => true, 'product_image_id' => 5, 'product_specifications_id' => 5, 'sku' => 'BIO-ECG-3000', 'brand' => 'Biogenix', 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Nitrile Gloves', 'slug' => 'nitrile-gloves', 'description' => 'Single-use gloves for basic lab and hospital procedures.', 'category_id' => 4, 'subcategory_id' => 6, 'base_sku' => 'BIO-GLV', 'is_published' => true, 'product_image_id' => 5, 'product_specifications_id' => 6, 'sku' => 'BIO-GLV-001', 'brand' => 'Biogenix', 'visibility_scope' => 'public', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Sterile Syringe Pack', 'slug' => 'sterile-syringe-pack', 'description' => 'Common consumable visible for guest, B2C, and B2B.', 'category_id' => 4, 'subcategory_id' => 7, 'base_sku' => 'BIO-SYR', 'is_published' => true, 'product_image_id' => 6, 'product_specifications_id' => 7, 'sku' => 'BIO-SYR-050', 'brand' => 'Biogenix', 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('product_prices')->insert([
            ['id' => 1, 'product_id' => 1, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 245000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 2, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 368000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_id' => 3, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 1450000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_id' => 4, 'price_type' => 'institutional', 'company_id' => null, 'amount' => 825000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_id' => 5, 'price_type' => 'public', 'company_id' => null, 'amount' => 120000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_id' => 6, 'price_type' => 'public', 'company_id' => null, 'amount' => 120.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_id' => 7, 'price_type' => 'public', 'company_id' => null, 'amount' => 80.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
        ]);




        DB::table('product_variants')->insert([
            ['id' => 1, 'product_id' => 1, 'sku' => 'BIO-HEMA-X200-STD', 'price' => 250000.00, 'stock_quantity' => 8, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 1, 'sku' => 'BIO-HEMA-X200-PRO', 'price' => 285000.00, 'stock_quantity' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_id' => 2, 'sku' => 'BIO-BIOC-B500-STD', 'price' => 380000.00, 'stock_quantity' => 5, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_id' => 3, 'sku' => 'BIO-DXR-1000-BASE', 'price' => 1500000.00, 'stock_quantity' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_id' => 4, 'sku' => 'BIO-USP-PRO-BASE', 'price' => 850000.00, 'stock_quantity' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_id' => 5, 'sku' => 'BIO-ECG-3000-BASE', 'price' => 120000.00, 'stock_quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_id' => 6, 'sku' => 'BIO-GLV-001-100', 'price' => 120.00, 'stock_quantity' => 350, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'product_id' => 7, 'sku' => 'BIO-SYR-050-50', 'price' => 80.00, 'stock_quantity' => 500, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('variant_attributes')->insert([
            ['id' => 1, 'product_variant_id' => 1, 'attribute_name' => 'Throughput', 'attribute_value' => '60 tests/hour', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_variant_id' => 2, 'attribute_name' => 'Throughput', 'attribute_value' => '90 tests/hour', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_variant_id' => 3, 'attribute_name' => 'Tests', 'attribute_value' => '120/hour', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_variant_id' => 4, 'attribute_name' => 'Detector', 'attribute_value' => 'Flat Panel', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_variant_id' => 5, 'attribute_name' => 'Modes', 'attribute_value' => '2D, Doppler, M-Mode', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_variant_id' => 6, 'attribute_name' => 'Leads', 'attribute_value' => '12-lead', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_variant_id' => 7, 'attribute_name' => 'Pack Size', 'attribute_value' => '100 units', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'product_variant_id' => 8, 'attribute_name' => 'Pack Size', 'attribute_value' => '50 units', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
