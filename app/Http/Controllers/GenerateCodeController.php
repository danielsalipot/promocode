<?php

namespace App\Http\Controllers;

use App\Models\PepsiCodes;
use Illuminate\Http\Request;

class GenerateCodeController extends Controller
{
    public function index($key)
    {
        $count = 1000000;
        $starting_values = str_split('JYHMRPC');
        $values = [
            $this->rearrangeValuesBasedOnStartingValue('J9PCLAV', $starting_values[0]),
            $this->rearrangeValuesBasedOnStartingValue('YERTM7H3W', $starting_values[1]),
            $this->rearrangeValuesBasedOnStartingValue('H3W4J9PCLAVDFYE', $starting_values[2]),
            $this->rearrangeValuesBasedOnStartingValue('M7H3W4J9PCLAVDFYERT', $starting_values[3]),
            $this->rearrangeValuesBasedOnStartingValue('RTM7H3W4J9PCLAVDF', $starting_values[4]),
            $this->rearrangeValuesBasedOnStartingValue('PCLAVDFYERTM7H3', $starting_values[5]),
            $this->rearrangeValuesBasedOnStartingValue('CLAVDFYERTM7H3W4J9', $starting_values[6]),
        ];

        $column_weights = [1, 3, 2, 6, 5, 4, 1];
        $weight_frequencies = [1, 18, 270, 2430, 36450, 619650];

        $columns[] = $this->generateColumnRows($values[$key], $count, $weight_frequencies[$column_weights[$key] - 1], $key);

        return implode("", $columns[0]);
    }

    public function concatForSecondPhase()
    {
        $pepsi_code = PepsiCodes::all();
        foreach ($pepsi_code as $key => $value) {
            // 18263810 - starting
            PepsiCodes::create([
                'column' => substr($value->column, 18263810)
            ]);
        }
    }

    public function generateCodePhaseTwo($start)
    {
        $start -= 18263810;
        $pepsi_code = PepsiCodes::orderBy('id', 'desc')->take(8)->get()->sortBy('id');

        $temp = [];
        foreach ($pepsi_code as $key => $code) {
            $temp[] = str_split(substr($code->column, $start, 1000000));
        }

        return $this->mergeColumnsToStrings($temp);
    }

    public function generateCode($start)
    {
        $pepsi_code = PepsiCodes::all();

        $temp = [];
        foreach ($pepsi_code as $key => $code) {
            $temp[] = str_split(substr($code->column, $start, 1000000));
        }

        return $this->mergeColumnsToStrings($temp);
    }

    function mergeColumnsToStrings($data)
    {
        $result = [];
        $transposedData = array_map(null, ...$data);

        foreach ($transposedData as $column) {
            $result[] = implode("", $column);
        }

        return $result;
    }


    public function generateColumnRows($column_possible_values, $count, $column_weight, $key)
    {
        $column_rows = [];
        while (count($column_rows) < $count) {
            foreach ($column_possible_values as $value) {
                for ($j = 0; $j < $column_weight; $j++) {
                    $column_rows[] = $value;
                    if (count($column_rows) == $count) {
                        break 2;
                    }
                }
            }
            echo "On key: " . $key . ' | Count: ' . count($column_rows);
            echo "\r\n";
        }
        return $column_rows;
    }

    public function rearrangeValuesBasedOnStartingValue($value, $starting_value)
    {
        $position = strpos($value, $starting_value);
        $beforeB = substr($value, 0, $position);
        $afterB = substr($value, $position);
        $result = $afterB . $beforeB;
        return str_split($result);
    }
}
