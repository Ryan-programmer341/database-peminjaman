<?php
    require '../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

    include "../koneksi.php";

    $styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '0000'],
        ],
    ],
];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // MENGGABUNGKAN BEBERAPA SEL JADI SATU KOTAK BESAR //
    $sheet->mergeCells('A1:F1');
    $sheet->mergeCells('A2:F2');
    $sheet->mergeCells('A3:F3');
    $sheet->mergeCells('A4:F4');

        // mengisi data ke dalam sel excel //
    $sheet->setCellValue('A1', 'SISTEM PEMINJAMAN BUKU');
    $sheet->setCellValue('A2', 'LAPORAN DATA PEMINJAMAN');
    $sheet->setCellValue('A3', 'perpustakaan Ryan');
    $sheet->setCellValue('A4', 'Tanggal Cetak: ' . date('d F Y'));

    $sheet->getstyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getstyle('A6:F6')->getFont()-> setBold(true);
    $sheet->getstyle('A1:A2')->getFont()-> setBold(true);
    $sheet->setCellValue('A6', 'No');
    $sheet->setCellValue('B6', 'Nama');
    $sheet->setCellValue('C6', 'Tanggal Pinjam');
    $sheet->setCellValue('D6', 'Tanggal Kembali');
    $sheet->setCellValue('E6', 'Denda');
    $sheet->setCellValue('F6', 'Status');

    $query = mysqli_query($koneksi, "SELECT borrows.id,students.name AS student_name,
                borrows.borrow_date,borrows.return_date,borrows.denda,borrows.status FROM borrows JOIN students ON borrows.student_id = students.id
                ORDER BY borrows.id ASC
                ");


    $rowNum = 7;
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $sheet->setCellValue('A' . $rowNum, $no);
        $sheet->setCellValue('B' . $rowNum, $row['student_name']);
        $sheet->setCellValue('C' . $rowNum, $row['borrow_date']);
        $sheet->setCellValue('D' . $rowNum, $row['return_date']);
        $sheet->setCellValue('E' . $rowNum, $row['denda']);
        $sheet->setCellValue('F' . $rowNum, $row['status']);
        $rowNum++;
        $no++;
    }
    // bagian total dan nominal //
    $lastDataRow = $rowNum - 1;
    $sheet->mergeCells('A' . $rowNum . ':D' . $rowNum);
    $sheet->setCellValue('A' . $rowNum, 'Total');

    $sheet->mergeCells('E' . $rowNum . ':F' . $rowNum);
    $sheet->setCellValue('E' . $rowNum, '=SUM(E7:E' . $lastDataRow . ')');

    $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFont()->setBold(true);

    $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)
    ->getBorders()->getAllBorders()
    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // mengatur lebar kolom otomatis //
    foreach (range('A', 'F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->getStyle('A6:F'.$rowNum -1)->applyFromArray($styleArray);
    $writer = new Xlsx($spreadsheet);
    $filename = 'LAPORAN_PEMINJAMAN BUKU.Xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
?>