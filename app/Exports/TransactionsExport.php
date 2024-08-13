<?php

namespace App\Exports;

use App\Models\Transaction; // Import your model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Transaction::whereBetween('created_at', [$this->startDate, $this->endDate])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Amount',
            'Credit',
            'Debit',
            'Charge',
            'Etop Charge',
            'Note',
            'Date',

            // Add more headers as needed
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->ref_trans_id,
            $transaction->transaction_type,
            $transaction->amount,
            $transaction->credit,
            $transaction->debit,
            $transaction->charge,
            $transaction->etop_charge,
            $transaction->note,
            $transaction->created_at,


            // Map additional fields as needed
        ];
    }
}
