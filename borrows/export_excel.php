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
    $sheet->mergeCells('A1:G1');
    $sheet->mergeCells('A2:G2');
    $sheet->mergeCells('A3:G3');
    $sheet->mergeCells('A4:G4');

        // mengisi data ke dalam sel excel //
    $sheet->setCellValue('A1', 'SISTEM PEMINJAMAN BUKU');
    $sheet->setCellValue('A2', 'LAPORAN DATA PEMINJAMAN');
    $sheet->setCellValue('A3', 'perpustakaan Ryan');
    $sheet->setCellValue('A4', 'Tanggal Cetak: ' . date('d F Y'));

    $sheet->getstyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getstyle('A1:A2')->getFont()-> setBold(true);
    
    $sheet->setCellValue('A6', 'No');
    $sheet->setCellValue('B6', 'Nama');
    $sheet->setCellValue('C6', 'Buku');
    $sheet->setCellValue('D6', 'Tanggal Pinjam');
    $sheet->setCellValue('E6', 'Tanggal Kembali');
    $sheet->setCellValue('F6', 'Denda');
    $sheet->setCellValue('G6', 'Status');
    $sheet->getstyle('A6:G6')->getFont()-> setBold(true);

    $nama = isset($_GET['nama']) ? $_GET['nama'] : '';
    $tgl_pinjam = isset($_GET['tgl_pinjam']) ? $_GET['tgl_pinjam'] : '';
    $statusfilter = isset($_GET['status']) ? $_GET['status'] : '';

    $query =   "SELECT borrows.id,students.name AS student_name,
                GROUP_CONCAT(books.title SEPARATOR ',') AS book_titles,
                borrows.borrow_date,borrows.return_date,borrows.denda,borrows.status FROM borrows 
                JOIN students ON borrows.student_id = students.id
                JOIN borrow_details ON borrows.id = borrow_details.borrow_id
                JOIN books ON borrow_details.book_id = books.id";
                
                $query .= " WHERE borrows.id !=0 ";
                if (!empty($statusfilter)) {
                    $query .= " AND borrows.status = '$statusfilter' ";
                }

                if (!empty($nama)) {
                    $query .= "AND students.name LIKE '%$nama%'";
                }

                if (!empty($tgl_pinjam)) {
                    $query .= "AND borrows.borrow_date = '$tgl_pinjam'";
                }


                $query .=" GROUP BY borrows.id,students.name,borrows.borrow_date,borrows.return_date,borrows.denda,borrows.status
                        ORDER BY borrows.id ASC";

    $query = mysqli_query($koneksi, $query);

    function tanggal_indo($tanggal) {
        if (!$tanggal || $tanggal == '0000-00-00') return '';
        $bulan = [
            1=> 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $split = explode('-', $tanggal);
        return $split[2] .' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    }
    
    $rowNum = 7;
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $borrow_date = tanggal_indo($row['borrow_date']);
        $return_date = tanggal_indo($row['return_date']);


        $sheet->setCellValue('A' . $rowNum, $no);
        $sheet->setCellValue('B' . $rowNum, $row['student_name']);
        $sheet->setCellValue('C' . $rowNum, $row['book_titles']);
        $sheet->setCellValue('D' . $rowNum, $borrow_date);
        $sheet->setCellValue('E' . $rowNum, $return_date );
        $sheet->setCellValue('F' . $rowNum, $row['denda']);
        $sheet->setCellValue('G' . $rowNum, $row['status']);
        $rowNum++;
        $no++;
    }
    // bagian total dan nominal //
    $lastDataRow = $rowNum - 1;


    $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum);
    $sheet->setCellValue('A' . $rowNum, 'Total');

    $sheet->mergeCells('G' . $rowNum . ':G' . $rowNum);
    $sheet->setCellValue('F' . $rowNum, '=SUM(F7:F' . $lastDataRow . ')');

    $sheet->getStyle('A' . $rowNum . ':G' . $rowNum)->getFont()->setBold(true);

    $sheet->getStyle('A' . $rowNum . ':G' . $rowNum)->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    $sheet->getStyle('A' . $rowNum . ':G' . $rowNum)
    ->getBorders()->getAllBorders()
    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // mengatur lebar kolom otomatis //
    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->getStyle('A6:G'.$rowNum -1)->applyFromArray($styleArray);

    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageSetup()->setHorizontalCentered(false);
    $sheet->getPageSetup()->setVerticalCentered(false);

    $writer = new Xlsx($spreadsheet);
    $filename = 'LAPORAN_PEMINJAMAN BUKU.Xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
?>