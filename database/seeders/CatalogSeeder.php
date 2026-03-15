<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Step 1: seed categories and subcategories used across the catalog.
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Diagnostics',
                'slug' => 'diagnostics',
                'description' => 'Diagnostic kits and testing systems for clinical and laboratory use.',
                'application' => 'Hospitals, pathology labs, diagnostic centers, clinics',
                'IsDisplayedOnHomePage' => true,
                'default_image_path' => 'storage/categories/image1.jpg',
                'gst_rate' => 18.00,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Biochemistry',
                'slug' => 'biochemistry',
                'description' => 'Clinical biochemistry reagents, analyzers, and test kits.',
                'application' => 'Clinical chemistry labs, hospital laboratories, research labs',
                'IsDisplayedOnHomePage' => true,
                'default_image_path' => 'storage/categories/image2.jpg',
                'gst_rate' => 18.00,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Molecular Diagnostics',
                'slug' => 'molecular-diagnostics',
                'description' => 'PCR kits, molecular testing products, and advanced diagnostic solutions.',
                'application' => 'Molecular labs, infectious disease testing, research institutions',
                'IsDisplayedOnHomePage' => true,
                'default_image_path' => 'storage/categories/image3.jpg',
                'gst_rate' => 18.00,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Laboratory Equipment',
                'slug' => 'laboratory-equipment',
                'description' => 'IVD instruments, analyzers, and supporting laboratory equipment.',
                'application' => 'Diagnostic labs, hospitals, medical colleges, research facilities',
                'IsDisplayedOnHomePage' => true,
                'default_image_path' => 'storage/categories/image4.jpg',
                'gst_rate' => 18.00,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Consumables',
                'slug' => 'consumables',
                'description' => 'Single-use laboratory and medical consumables.',
                'application' => 'Routine lab operations, sample handling, testing workflows',
                'IsDisplayedOnHomePage' => true,
                'default_image_path' => 'storage/categories/image5.jpg',
                'gst_rate' => 12.00,
                'sort_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('subcategories')->insert([
            ['id' => 1, 'category_id' => 1, 'name' => 'ELISA Kits', 'slug' => 'elisa-kits', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'category_id' => 1, 'name' => 'Rapid Test Kits', 'slug' => 'rapid-test-kits', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'category_id' => 1, 'name' => 'Serology Kits', 'slug' => 'serology-kits', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'category_id' => 1, 'name' => 'Urine Testing Kits', 'slug' => 'urine-testing-kits', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'category_id' => 2, 'name' => 'Biochemistry Reagents', 'slug' => 'biochemistry-reagents', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'category_id' => 2, 'name' => 'Clinical Chemistry Kits', 'slug' => 'clinical-chemistry-kits', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'category_id' => 2, 'name' => 'Hematology Reagents', 'slug' => 'hematology-reagents', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'category_id' => 3, 'name' => 'RT-PCR Kits', 'slug' => 'rt-pcr-kits', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'category_id' => 3, 'name' => 'Molecular Products', 'slug' => 'molecular-products', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'category_id' => 3, 'name' => 'Microbiology', 'slug' => 'microbiology', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'category_id' => 4, 'name' => 'IVD Instruments', 'slug' => 'ivd-instruments', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'category_id' => 4, 'name' => 'Analyzers', 'slug' => 'analyzers', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'category_id' => 4, 'name' => 'Laboratory Equipment', 'slug' => 'lab-equipment', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'category_id' => 5, 'name' => 'Blood Collection Consumables', 'slug' => 'blood-collection-consumables', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'category_id' => 5, 'name' => 'Plasticware', 'slug' => 'plasticware', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'category_id' => 5, 'name' => 'General Lab Consumables', 'slug' => 'general-lab-consumables', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
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
            ['id' => 1,'file_path' => 'storage/products/hema1.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'file_path' => 'storage/products/hema2.jpg', 'is_primary' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3,  'file_path' => 'storage/products/image1.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'file_path' => 'storage/products/image2.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'file_path' => 'storage/products/image3.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'file_path' => 'storage/products/image4.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'file_path' => 'storage/products/image5.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8,  'file_path' => 'storage/products/image1.jpg', 'is_primary' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'file_path' => 'storage/products/image2.jpg', 'is_primary' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        // Step 2: seed product-level GST override where needed.
        DB::table('products')->insert([
            ['id' => 1, 'name' => 'Hematology Analyzer X200', 'slug' => 'hematology-analyzer-x200', 'description' => 'High-precision hematology analyzer for labs.', 'category_id' => 1, 'subcategory_id' => 1, 'base_sku' => 'BIO-HEMA', 'is_published' => true, 'product_image_id' => 1, 'product_specifications_id' => 1, 'sku' => 'BIO-HEMA-X200', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Biochemistry Analyzer B500', 'slug' => 'biochemistry-analyzer-b500', 'description' => 'Fully automated biochemistry analyzer.', 'category_id' => 1, 'subcategory_id' => 2, 'base_sku' => 'BIO-BIOC', 'is_published' => true, 'product_image_id' => 2, 'product_specifications_id' => 2, 'sku' => 'BIO-BIOC-B500', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Digital X-Ray Machine DXR-1000', 'slug' => 'digital-xray-machine-dxr-1000', 'description' => 'High-resolution digital X-ray imaging system.', 'category_id' => 2, 'subcategory_id' => 3, 'base_sku' => 'BIO-DXR', 'is_published' => true, 'product_image_id' => 3, 'product_specifications_id' => 3, 'sku' => 'BIO-DXR-1000', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Ultrasound Machine UltraSound Pro', 'slug' => 'ultrasound-machine-ultrasound-pro', 'description' => 'Advanced ultrasound system for detailed imaging.', 'category_id' => 2, 'subcategory_id' => 4, 'base_sku' => 'BIO-USP', 'is_published' => true, 'product_image_id' => 4, 'product_specifications_id' => 4, 'sku' => 'BIO-USP-PRO', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'b2b', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'ECG Machine CardioMax 3000', 'slug' => 'ecg-machine-cardiomax-3000', 'description' => 'Portable ECG machine with advanced features.', 'category_id' => 3, 'subcategory_id' => 5, 'base_sku' => 'BIO-ECG', 'is_published' => true, 'product_image_id' => 5, 'product_specifications_id' => 5, 'sku' => 'BIO-ECG-3000', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Nitrile Gloves', 'slug' => 'nitrile-gloves', 'description' => 'Single-use gloves for basic lab and hospital procedures.', 'category_id' => 4, 'subcategory_id' => 6, 'base_sku' => 'BIO-GLV', 'is_published' => true, 'product_image_id' => 5, 'product_specifications_id' => 6, 'sku' => 'BIO-GLV-001', 'brand' => 'Biogenix', 'gst_rate' => 5.00, 'visibility_scope' => 'public', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Sterile Syringe Pack', 'slug' => 'sterile-syringe-pack', 'description' => 'Common consumable visible for guest, B2C, and B2B.', 'category_id' => 4, 'subcategory_id' => 7, 'base_sku' => 'BIO-SYR', 'is_published' => true, 'product_image_id' => 6, 'product_specifications_id' => 7, 'sku' => 'BIO-SYR-050', 'brand' => 'Biogenix', 'gst_rate' => null, 'visibility_scope' => 'all', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Step 3: each product has at least one variant.
        DB::table('product_variants')->insert([
            ['id' => 1, 'product_id' => 1, 'sku' => 'BIO-HEMA-X200-STD', 'variant_name' => 'Standard', 'attributes_json' => '{"Throughput":"60 tests/hour"}', 'min_order_quantity' => 1, 'max_order_quantity' => 20, 'model_number' => 'X200-STD', 'catalog_number' => 'CAT-HEMA-001', 'stock_quantity' => 8, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 1, 'sku' => 'BIO-HEMA-X200-PRO', 'variant_name' => 'Pro', 'attributes_json' => '{"Throughput":"90 tests/hour"}', 'min_order_quantity' => 1, 'max_order_quantity' => 20, 'model_number' => 'X200-PRO', 'catalog_number' => 'CAT-HEMA-002', 'stock_quantity' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_id' => 2, 'sku' => 'BIO-BIOC-B500-STD', 'variant_name' => 'Standard', 'attributes_json' => '{"Tests":"120/hour"}', 'min_order_quantity' => 1, 'max_order_quantity' => 12, 'model_number' => 'B500-STD', 'catalog_number' => 'CAT-BIOC-001', 'stock_quantity' => 5, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_id' => 3, 'sku' => 'BIO-DXR-1000-BASE', 'variant_name' => 'Base', 'attributes_json' => '{"Detector":"Flat Panel"}', 'min_order_quantity' => 1, 'max_order_quantity' => 6, 'model_number' => 'DXR-1000', 'catalog_number' => 'CAT-DXR-001', 'stock_quantity' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_id' => 4, 'sku' => 'BIO-USP-PRO-BASE', 'variant_name' => 'Base', 'attributes_json' => '{"Modes":"2D, Doppler, M-Mode"}', 'min_order_quantity' => 1, 'max_order_quantity' => 8, 'model_number' => 'USP-PRO', 'catalog_number' => 'CAT-USP-001', 'stock_quantity' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_id' => 5, 'sku' => 'BIO-ECG-3000-BASE', 'variant_name' => 'Base', 'attributes_json' => '{"Leads":"12-lead"}', 'min_order_quantity' => 1, 'max_order_quantity' => 25, 'model_number' => 'ECG-3000', 'catalog_number' => 'CAT-ECG-001', 'stock_quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_id' => 6, 'sku' => 'BIO-GLV-001-100', 'variant_name' => 'Pack 100', 'attributes_json' => '{"Pack Size":"100 units"}', 'min_order_quantity' => 10, 'max_order_quantity' => 500, 'model_number' => 'GLV-100', 'catalog_number' => 'CAT-GLV-001', 'stock_quantity' => 350, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'product_id' => 7, 'sku' => 'BIO-SYR-050-50', 'variant_name' => 'Pack 50', 'attributes_json' => '{"Pack Size":"50 units"}', 'min_order_quantity' => 10, 'max_order_quantity' => 500, 'model_number' => 'SYR-050', 'catalog_number' => 'CAT-SYR-001', 'stock_quantity' => 500, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Step 4: save GST and final price directly on each price row.
        $variantGstRates = [
            1 => 18.00,
            2 => 18.00,
            3 => 18.00,
            4 => 18.00,
            5 => 18.00,
            6 => 18.00,
            7 => 5.00,
            8 => 12.00,
        ];

        $variantQuantityRules = [
            1 => ['min_order_quantity' => 1, 'max_order_quantity' => 20, 'lot_size' => 1],
            2 => ['min_order_quantity' => 1, 'max_order_quantity' => 20, 'lot_size' => 1],
            3 => ['min_order_quantity' => 1, 'max_order_quantity' => 12, 'lot_size' => 1],
            4 => ['min_order_quantity' => 1, 'max_order_quantity' => 6, 'lot_size' => 1],
            5 => ['min_order_quantity' => 1, 'max_order_quantity' => 8, 'lot_size' => 1],
            6 => ['min_order_quantity' => 1, 'max_order_quantity' => 25, 'lot_size' => 1],
            7 => ['min_order_quantity' => 10, 'max_order_quantity' => 500, 'lot_size' => 10],
            8 => ['min_order_quantity' => 10, 'max_order_quantity' => 500, 'lot_size' => 10],
        ];

        $genericPriceRows = [
            ['product_variant_id' => 1, 'price_type' => 'retail', 'company_id' => null, 'amount' => 265000.00, 'currency' => 'INR', 'quantity' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 1, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 255000.00, 'currency' => 'INR', 'quantity' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 1, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 245000.00, 'currency' => 'INR', 'quantity' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 1, 'price_type' => 'institutional', 'company_id' => null, 'amount' => 248000.00, 'currency' => 'INR', 'quantity' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 2, 'price_type' => 'retail', 'company_id' => null, 'amount' => 285000.00, 'currency' => 'INR', 'quantity' => 10, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 3, 'price_type' => 'retail', 'company_id' => null, 'amount' => 390000.00, 'currency' => 'INR', 'quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 3, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 378000.00, 'currency' => 'INR', 'quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 3, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 368000.00, 'currency' => 'INR', 'quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 3, 'price_type' => 'institutional', 'company_id' => null, 'amount' => 372000.00, 'currency' => 'INR', 'quantity' => 12, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 4, 'price_type' => 'retail', 'company_id' => null, 'amount' => 1550000.00, 'currency' => 'INR', 'quantity' => 6, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 4, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 1490000.00, 'currency' => 'INR', 'quantity' => 6, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 4, 'price_type' => 'dealer', 'company_id' => null, 'amount' => 1450000.00, 'currency' => 'INR', 'quantity' => 6, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 5, 'price_type' => 'retail', 'company_id' => null, 'amount' => 870000.00, 'currency' => 'INR', 'quantity' => 8, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 5, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 840000.00, 'currency' => 'INR', 'quantity' => 8, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 5, 'price_type' => 'institutional', 'company_id' => null, 'amount' => 825000.00, 'currency' => 'INR', 'quantity' => 8, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 6, 'price_type' => 'public', 'company_id' => null, 'amount' => 120000.00, 'currency' => 'INR', 'quantity' => 25, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 6, 'price_type' => 'retail', 'company_id' => null, 'amount' => 126000.00, 'currency' => 'INR', 'quantity' => 25, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 6, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 122000.00, 'currency' => 'INR', 'quantity' => 25, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 7, 'price_type' => 'public', 'company_id' => null, 'amount' => 120.00, 'currency' => 'INR', 'quantity' => 350, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 7, 'price_type' => 'retail', 'company_id' => null, 'amount' => 130.00, 'currency' => 'INR', 'quantity' => 350, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 7, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 125.00, 'currency' => 'INR', 'quantity' => 350, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            ['product_variant_id' => 8, 'price_type' => 'public', 'company_id' => null, 'amount' => 80.00, 'currency' => 'INR', 'quantity' => 500, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 8, 'price_type' => 'retail', 'company_id' => null, 'amount' => 95.00, 'currency' => 'INR', 'quantity' => 500, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['product_variant_id' => 8, 'price_type' => 'logged_in', 'company_id' => null, 'amount' => 88.00, 'currency' => 'INR', 'quantity' => 500, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('product_prices')->insert(array_map(function (array $row) use ($variantGstRates, $variantQuantityRules): array {
            $gstRate = $variantGstRates[$row['product_variant_id']] ?? 0;
            $quantityRules = $variantQuantityRules[$row['product_variant_id']] ?? ['min_order_quantity' => 1, 'max_order_quantity' => null, 'lot_size' => 1];
            $taxAmount = round(($row['amount'] * $gstRate) / 100, 2);
            $row['gst_rate'] = $gstRate;
            $row['tax_amount'] = $taxAmount;
            $row['price_after_gst'] = round($row['amount'] + $taxAmount, 2);
            $row['min_order_quantity'] = $quantityRules['min_order_quantity'];
            $row['max_order_quantity'] = $quantityRules['max_order_quantity'];
            $row['lot_size'] = $quantityRules['lot_size'];

            return $row;
        }, $genericPriceRows));

        // Optional example: client-specific company pricing.
        $firstCompanyId = DB::table('companies')->value('id');
        if ($firstCompanyId) {
            $companyPriceRows = [
                ['product_variant_id' => 1, 'price_type' => 'company_price', 'company_id' => $firstCompanyId, 'amount' => 238000.00, 'currency' => 'INR', 'quantity' => 15, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['product_variant_id' => 6, 'price_type' => 'company_price', 'company_id' => $firstCompanyId, 'amount' => 116500.00, 'currency' => 'INR', 'quantity' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ];

            DB::table('product_prices')->insert(array_map(function (array $row) use ($variantGstRates, $variantQuantityRules): array {
                $gstRate = $variantGstRates[$row['product_variant_id']] ?? 0;
                $quantityRules = $variantQuantityRules[$row['product_variant_id']] ?? ['min_order_quantity' => 1, 'max_order_quantity' => null, 'lot_size' => 1];
                $taxAmount = round(($row['amount'] * $gstRate) / 100, 2);
                $row['gst_rate'] = $gstRate;
                $row['tax_amount'] = $taxAmount;
                $row['price_after_gst'] = round($row['amount'] + $taxAmount, 2);
                $row['min_order_quantity'] = $quantityRules['min_order_quantity'];
                $row['max_order_quantity'] = $quantityRules['max_order_quantity'];
                $row['lot_size'] = $quantityRules['lot_size'];

                return $row;
            }, $companyPriceRows));
        }

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
