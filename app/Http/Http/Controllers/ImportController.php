<?php

namespace App\Http\Controllers;

use App\Models\NaeGermany;
use App\Models\Product;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function importFromNae()
    {
        $naeProducts = NaeGermany::all();
        $imported = 0;

        foreach ($naeProducts as $nae) {
            if (empty($nae->SKU)) continue;
            if (Product::where('SKU', $nae->SKU)->exists()) continue;

            $images = array_filter([
                $nae->image1,
                $nae->image2,
                $nae->image3,
                $nae->image4,
                $nae->image5,
            ]);

            Product::create([
                'name_pl' => $nae->product_title_PL ?? $nae->Product_Name,
                'name_en' => $nae->product_title_EN ?? $nae->Product_Name,
                'slug_pl' => Str::slug(($nae->product_title_PL ?? $nae->Product_Name) . '-' . $nae->SKU),
                'slug_en' => Str::slug(($nae->product_title_EN ?? $nae->Product_Name) . '-' . $nae->SKU),
                'short_description_pl' => $nae->Product_Description_PL ? mb_substr(strip_tags($nae->Product_Description_PL), 0, 220) : null,
                'short_description_en' => $nae->Product_Description_EN ? mb_substr(strip_tags($nae->Product_Description_EN), 0, 220) : null,
                'description_pl' => $nae->Product_Description_PL,
                'description_en' => $nae->Product_Description_EN,
                'regular_price' => $nae->product_price ? floatval($nae->product_price) : 0,
                'sale_price' => $nae->promo_price ? floatval($nae->promo_price) : null,
                'SKU' => $nae->SKU,
                'Parent_ID_SKU' => $nae->Parent_ID_SKU ?? $nae->SKU,
                'stock_status' => ($nae->Stock ?? 0) > 0 ? 'instock' : 'outofstock',
                'Polecane' => 0,
                'quantity' => intval($nae->Stock ?? 0),
                'image' => $images[0] ?? null,
                'images' => count($images) ? json_encode(array_values($images)) : null,
                'badge' => $nae->badge ?? null,
                'category_id' => null, // tymczasowo pomijamy kategorie
            ]);

            $imported++;
        }

        return "Import zakończony! Dodano {$imported} produktów.";
    }
}
