<?php
    $output = $masterListOfFormat['MasterListOfFormat']['document_details'];
    // $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output); 
    // $output = str_replace('<table border="1" cellpadding="0" cellspacing="0">', '<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">', $output);  
    // $output = str_replace('<tr>', '<tr  bgcolor="#FFFFFF">', $output);
    $output = str_replace('<div style="page-break-after: always;"><span style="display:none">&nbsp;</span></div>', '<tcpdf method="AddPage" />', $output);
    echo $output;
?>
<?php
    $output = $masterListOfFormat['MasterListOfFormat']['work_instructions'];
    $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output); 
    $output = str_replace('<table border="1" cellpadding="0" cellspacing="0">', '<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">', $output);  
    $output = str_replace('<tr>', '<tr  bgcolor="#FFFFFF">', $output);
    echo $output;
?>
<?php
