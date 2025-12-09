<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ViolationCategory;
use App\Models\Violation;

class ViolationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Violation::truncate();
        ViolationCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $violationsText = file_get_contents(database_path('../violations.txt'));
        $lines = explode("\n", trim($violationsText));

        $currentCategory = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            if (strpos($line, '->') === false) {
                $currentCategory = ViolationCategory::firstOrCreate(['name' => $line]);
                continue;
            }

            if ($currentCategory) {
                $lineContent = ltrim($line, '-> ');
                $parts = preg_split('/->\s*(Fees|Penalty)\s*/', $lineContent, -1, PREG_SPLIT_DELIM_CAPTURE);

                if (count($parts) < 3) {
                    continue;
                }

                $violationName = trim($parts[0]);
                $feesString = '';
                $penaltyString = '';

                for ($i = 1; $i < count($parts); $i += 2) {
                    if (strtolower($parts[$i]) === 'fees') {
                        $feesString = trim($parts[$i+1]);
                    } elseif (strtolower($parts[$i]) === 'penalty') {
                        $penaltyString = trim($parts[$i+1]);
                    }
                }
                
                if (substr($penaltyString, 0, 1) == '(' && substr($penaltyString, -1) == ')') {
                    $penaltyString = trim(substr($penaltyString, 1, -1));
                }

                $fees = [
                    'first_offense' => null,
                    'second_offense' => null,
                    'third_offense' => null,
                ];

                if (strpos($violationName, 'Colorum violation') !== false) {
                    if (preg_match('/Bus:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 1000000.00;
                    elseif (preg_match('/Truck:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 200000.00;
                    elseif (preg_match('/Van:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 200000.00;
                    elseif (preg_match('/Sedan:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 120000.00;
                    elseif (preg_match('/MC:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 6000.00;
                    elseif (preg_match('/Jeepney:\s*Php\s*([\d,]+\.\d{2})/', $feesString, $m)) $fees['first_offense'] = 50000.00;
                } else {
                    preg_match_all('/(\d+)(?:st|nd|rd|th):\s*Php\s*([\d,]+\.\d{2})/', $feesString, $feeMatches);
                    foreach ($feeMatches[1] as $index => $offenseNum) {
                        $feeValue = (float)str_replace(',', '', $feeMatches[2][$index]);
                        if ($offenseNum == 1) $fees['first_offense'] = $feeValue;
                        if ($offenseNum == 2) $fees['second_offense'] = $feeValue;
                        if ($offenseNum == 3) $fees['third_offense'] = $feeValue;
                    }
                }

                Violation::create([
                    'violation_category_id' => $currentCategory->id,
                    'name' => $violationName,
                    'first_offense' => $fees['first_offense'],
                    'second_offense' => $fees['second_offense'],
                    'third_offense' => $fees['third_offense'],
                    'penalty' => $penaltyString,
                ]);
            }
        }
    }

    private function createViolation(array &$violationData, int $categoryId): void
    {
        if (empty($violationData['name'])) {
            $violationData = [];
            return;
        }

        $fees = [
            'first_offense' => null,
            'second_offense' => null,
            'third_offense' => null,
        ];

        if (!empty($violationData['fees'])) {
            preg_match_all('/(\d+)(?:st|nd|rd|th):\s*Php\s*([\d,]+\.\d{2})/', $violationData['fees'], $feeMatches);

            foreach ($feeMatches[1] as $index => $offenseNum) {
                $feeValue = (float)str_replace(',', '', $feeMatches[2][$index]);
                if ($offenseNum == 1) $fees['first_offense'] = $feeValue;
                if ($offenseNum == 2) $fees['second_offense'] = $feeValue;
                if ($offenseNum == 3) $fees['third_offense'] = $feeValue;
            }
        }

        Violation::create([
            'violation_category_id' => $categoryId,
            'name' => $violationData['name'],
            'first_offense' => $fees['first_offense'],
            'second_offense' => $fees['second_offense'],
            'third_offense' => $fees['third_offense'],
            'penalty' => $violationData['penalty'] ?? null,
        ]);

        $violationData = [];
    }
}
