<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Category',
            'Description',
            'Price',
            'Stock',
            'Enabled'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category?->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->enabled ? 'Yes' : 'No'
        ];
    }
}