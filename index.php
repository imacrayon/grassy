<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<title>Grassy</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body>
	<div class="container" id="container">
		<div class="form-group">
			<label class="control-label" for="grid_size">Grid Size</label>
			<input class="form-control" type="number" name="grid_size" id="grid_size" value="10">
		</div>
		<div id="image" class="text-center"></div>
		<div class="form-group">
			<label class="control-label" for="file">Image</label>
			<input class="form-control" type="file" name="file" id="file" accept="image/*">
		</div>
		<div id="loading_bar" class="progress" style="display: none;">
			<div id="loading" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
		</div>

		<form id="export" name="export" method="post" action="export.php">
			<input type="hidden" id="export_data" name="export_data">
			<input type="hidden" id="export_name" name="export_name">
			<input type="hidden" id="export_grid" name="export_grid">
 		</form>
	</div>
	<script>
		document.getElementById('file').addEventListener('change', function(e) {
		
			var file = this.files[0];
			var loading = document.getElementById('loading');
			var container = document.getElementById('container');
			document.getElementById('loading_bar').style.display = 'block';
			
			var fd = new FormData();
			fd.append('file', file);
			fd.append('grid_size', document.getElementById('grid_size').value);
			
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'cruncher.php', true);
			
			xhr.upload.onprogress = function(e) {
				if (e.lengthComputable) {
					var percentComplete = Math.round((e.loaded / e.total) * 100);
					var percentLabel = percentComplete + '%';
					loading.style.width = percentLabel;
					loading.innerHTML = percentLabel;
					loading.dataset['valuenow'] = percentComplete;
				}
			};
			
			xhr.onload = function() {
				if (this.status == 200) {
					var resp = JSON.parse(this.response);
					
					loading.classList.remove('active');
					
					if(resp.type == 'error') {  
						container.innerHTML += '<div class="alert alert-danger">'+ resp.text +'</div>';
					} else {
						console.log(resp);
						var image = document.createElement('img');
						image.style.width = '200px';
						image.src = resp.image.dataUrl;
						document.getElementById('image').appendChild(image);

						document.getElementById('export_data').value = JSON.stringify(resp.data);
						document.getElementById('export_name').value = resp.image.name;
						document.getElementById('export_grid').value = resp.gridSize;
						setTimeout(function(){
							document.forms['export'].submit();
							container.innerHTML += '<div class="alert alert-success">I just farted out a spreadsheet file for you!</div>';
						}, 1000);
					}
				};
			};
			
			xhr.send(fd);
		}, false);
	</script>
</body>
</html>