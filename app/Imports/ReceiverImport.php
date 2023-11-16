<?php

namespace App\Imports;

use App\Models\Receiver;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;


class ReceiverImport implements ToModel, WithHeadingRow, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }


    protected $programId;

    public function __construct($programId)
    {
        $this->programId = $programId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Receiver([
            'program_id' => $this->getProgramId(),
            'status' => 1,
            'bank' => $row['bank'],
            'name' => $row['name'],
            'identification_number' => $row['identification_number'],
            'address' => $row['address'],
            'phone_number' => $row['phone_number'],
            'email' => $row['email'],
            'account_number' => $row['account_number'],
        ]);
    }

    public function getProgramId()
    {
        return $this->programId;
    }

    public function uniqueBy()
    {
        return ['program_id', 'identification_number'];
    }
}
