<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /**
     * Seed catalog and pricing data for module bootstrap.
     */
    public function run(): void
    {
        $now = now();

        DB::table('categories')->upsert([
            ['name' => 'Consumables', 'slug' => 'consumables', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Diagnostics', 'slug' => 'diagnostics', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Instruments', 'slug' => 'instruments', 'created_at' => $now, 'updated_at' => $now],
        ], ['slug'], ['name', 'updated_at']);

        $categoryIds = DB::table('categories')->pluck('id', 'slug');

        DB::table('products')->upsert([
            [
                'category_id' => $categoryIds['consumables'] ?? null,
                'sku' => 'BIO-GLV-001',
                'name' => 'Nitrile Gloves',
                'description' => 'Single-use gloves for basic lab and hospital procedures.',
                'visibility_scope' => 'public',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $categoryIds['diagnostics'] ?? null,
                'sku' => 'BIO-PCR-100',
                'name' => 'PCR Test Kit',
                'description' => 'Retail-focused PCR kit package for B2C users.',
                'visibility_scope' => 'b2c',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $categoryIds['instruments'] ?? null,
                'sku' => 'BIO-ANA-500',
                'name' => 'Clinical Analyzer',
                'description' => 'Institutional analyzer with dealer and contract pricing.',
                'visibility_scope' => 'b2b',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $categoryIds['consumables'] ?? null,
                'sku' => 'BIO-SYR-050',
                'name' => 'Sterile Syringe Pack',
                'description' => 'Common consumable visible to guest, B2C, and B2B.',
                'visibility_scope' => 'all',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['sku'], ['category_id', 'name', 'description', 'visibility_scope', 'is_active', 'updated_at']);

        $contractCompany = DB::table('companies')->where('name', 'Apollo Diagnostics')->first();

        if (! $contractCompany) {
            $contractCompanyId = DB::table('companies')->insertGetId([
                'name' => 'Apollo Diagnostics',
                'company_type' => 'lab',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $contractCompanyId = $contractCompany->id;
        }

        $productIds = DB::table('products')->pluck('id', 'sku');

        DB::table('product_prices')->whereIn('product_id', $productIds->values())->delete();

        DB::table('product_prices')->insert([
            ['product_id' => $productIds['BIO-GLV-001'], 'price_type' => 'public', 'company_id' => null, 'amount' => 120.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-GLV-001'], 'price_type' => 'retail', 'company_id' => null, 'amount' => 115.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-GLV-001'], 'price_type' => 'dealer', 'company_id' => null, 'amount' => 102.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-GLV-001'], 'price_type' => 'institutional', 'company_id' => null, 'amount' => 99.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],

            ['product_id' => $productIds['BIO-PCR-100'], 'price_type' => 'public', 'company_id' => null, 'amount' => 460.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-PCR-100'], 'price_type' => 'retail', 'company_id' => null, 'amount' => 430.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],

            ['product_id' => $productIds['BIO-ANA-500'], 'price_type' => 'dealer', 'company_id' => null, 'amount' => 105000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-ANA-500'], 'price_type' => 'institutional', 'company_id' => null, 'amount' => 98000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-ANA-500'], 'price_type' => 'contract', 'company_id' => $contractCompanyId, 'amount' => 91000.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],

            ['product_id' => $productIds['BIO-SYR-050'], 'price_type' => 'public', 'company_id' => null, 'amount' => 80.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-SYR-050'], 'price_type' => 'retail', 'company_id' => null, 'amount' => 75.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-SYR-050'], 'price_type' => 'dealer', 'company_id' => null, 'amount' => 62.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-SYR-050'], 'price_type' => 'institutional', 'company_id' => null, 'amount' => 59.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $productIds['BIO-SYR-050'], 'price_type' => 'contract', 'company_id' => $contractCompanyId, 'amount' => 55.00, 'currency' => 'INR', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
