<?php
include('./function/function.php');
check_session();

$data = sqlfetch("SELECT * FROM `enquiry` ORDER BY id DESC");

$filename = "enquiries_" . date('Y-m-d') . ".csv";

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('S.No', 'Date', 'Customer Name', 'Email', 'Phone', 'Location', 'Message', 'Page'));

// Output the data
$count = 1;
if (count($data)) {
    foreach ($data as $e) {
        fputcsv($output, array(
            $count++,
            date('d M, Y', strtotime($e['tdate'])),
            $e['name'],
            $e['email'],
            $e['phone'],
            $e['city'],
            $e['message'],
            $e['page']
        ));
    }
}

fclose($output);
exit;
?>
