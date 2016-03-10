<?php
	
$file = $_FILES['file']['tmp_name'];
$gridSize = $_REQUEST['grid_size'];
$whiteThreshold = (255*255*255) / 2;
if(getimagesize($file) !== false) {
	if(exif_imagetype($file) === IMAGETYPE_JPEG) {
	
		$fileName = $_FILES['file']['name'];
		$fileType = $_FILES['file']['type'];
		$fileContent = file_get_contents($file);
		$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
	
		$img = imagecreatefromjpeg($file);
		$width = imagesx($img);
		$height = imagesy($img);
		$data = [];
		
		for($x = 0; $x < $width; $x++) {
			// get every Nth horizontal pixel equal to $gridSize
			if ($x % $gridSize == ($gridSize - 1)) {
			    for($y = 0; $y < $height; $y++) {
			    	// get every Nth vertical pixel equal to $gridSize
			    	if ($y % $gridSize == ($gridSize - 1)) {
				        $data[] = [
				        	'x' => ($x + 1) / $gridSize,
				        	'y' => ($y + 1) / $gridSize,
				        	'color' => (imagecolorat($img, $x, $y) < $whiteThreshold) ? 'black' : 'white'
				        ];
				    }
			    }
			}
		};
		
		echo json_encode([
			'type' => 'success',
			'gridSize' => $gridSize,
			'image' => [
				'name' => $fileName,
				'type' => $fileType,
				'dataUrl' => $dataUrl
			],
			'data' => $data
		]);
		
	} else {
		echo json_encode(['type'=>'error', 'text' => 'The image needs to be a jpg brah.']);
	}
	
} else {
    echo json_encode(['type'=>'error', 'text' => 'That file is not a legit image brah.']);
}