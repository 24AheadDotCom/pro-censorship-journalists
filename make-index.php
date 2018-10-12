<?php

function buildTableHeader( $line ) {
	$s = '<tr>';
	$ary = [];
	$cellNumber = 0;
	foreach ( $line as $data ) {
		if ( !$cellNumber ) {
			$extra = ' data-sortable-type="date" ';
		}
		else {
			$extra = '';
		}

		$ary[] = '<th data-sortable="true" ' . $extra . '>' . $data . '</th>';
		$cellNumber++;
	}

	return $s . implode( '', $ary ) . '</tr></thead>' . PHP_EOL;
}

function buildTableRow( $line ) {
	$s = '<tr>';
	$ary = [];
	foreach ( $line as $data ) {
		if ( stripos( $data, 'http' ) !== FALSE ) {
			$data = str_replace( '.', ' &middot; ', str_ireplace( [ 'http://', 'https://' ], '', $data ) );
		}
		$classes = 'datacell';
		if ( strlen( $data ) > 100 ) {
			$data = '<div class="readmore">' . $data . '</div>';
		}
		$ary[] = '<td>' . $data . '</td>';
	}

	return $s . implode( '', $ary ) . '</tr>' . PHP_EOL;
}

$intro = 'Journalists shouldn\'t support censorship, but obviously many do. Help add to this list <a href="https://github.com/24AheadDotCom/pro-censorship-journalists">here</a>';

$content = '<table class="sortable-theme-bootstrap" data-sortable><thead>';

$fp = fopen( __DIR__ . '/listing.csv', 'r' );

$lineNumber = 1;
while ( $line = fgetcsv( $fp ) ) {
	if ( $lineNumber == 1 ) {
		$content .= buildTableHeader( $line );
		$content .= '</thead><tbody>';
	}
	else {
		$content .= buildTableRow( $line );
	}
	$lineNumber++;
}

$content .= '</tbody></table>';

fclose( $fp );

ob_start();
include( __DIR__ . '/index.tpl.php' );
$s = ob_get_contents();
ob_end_clean();

$fp = fopen( __DIR__ . '/index.html', 'w' );
fwrite( $fp, $s );
fclose( $fp );
