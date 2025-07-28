<?php

namespace App\Imports;

use App\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class StocksImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Stock([
            'article_code' => $row['article_code'] ?? null,
            'article_label' => $row['article_label'] ?? null,
            
            // Map other fields as needed
        ]);
    }

    public function uniqueBy()
    {
        return 'article_code';
    }
}
