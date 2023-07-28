<!DOCTYPE html>
<html>

<head>
	<title>Crop Image Before Upload using CropperJS with PHP</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
	<script src="https://unpkg.com/dropzone"></script>
	<script src="https://unpkg.com/cropperjs"></script>
	<link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
	<div class="container">
		<br />
		<h3>Crop Image</h3>
		<br />
		<div class="row">
			<div class="col-md-4">&nbsp;</div>
			<div class="col-md-4">
				<div class="image_area">
					<form method="post">
						<label for="upload_image">
							<input type="file" name="image" class="image" id="upload_image" />
						</label>
					</form>
				</div>
			</div>

			<!--  -->
			<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Crop Image</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>

						<!--  -->
						<div class="modal-body">
							<div class="img-container">
								<div class="row">
									<div class="col-md-8">
										<img src="" id="sample_image" />
									</div>
									<div class="col-md-4">
										<div class="preview"></div>
										<div>
											<h4>Rotate Image</h4>
											<button class="btn btn-primary" id="rotate-left">Rotate Left</button>
											<button class="btn btn-primary" id="rotate-right">Rotate Right</button>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--  -->
						<div class="modal-footer">
							<button class="btn btn-success" id="download">Download Cropped Image</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
</body>

</html>

<script>
	window.addEventListener('DOMContentLoaded', () => {
		let $modal = $('#modal');
		let image = document.getElementById('sample_image');
		let cropper;

		$('#upload_image').change(function (event) {
			let files = event.target.files;

			let done = function (url) {
				image.src = url;
				$modal.modal('show');
			};

			if (files && files.length > 0) {
				reader = new FileReader();
				reader.onload = function (event) {
					done(reader.result);
				};
				reader.readAsDataURL(files[0]);
			}

			// Rotate image 
			$('#rotate-left').on('click', (function () {
				cropper.rotate(45)
			}));

			$('#rotate-right').on('click', (function (e) {
				cropper.rotate(-45)
			}));


			// Download image
			$('#download').click(function () {
				let croppedCanvas = cropper.getCroppedCanvas();
				croppedCanvas.toBlob(function (blob) {
					let url = URL.createObjectURL(blob);
					let anchor = document.createElement('a');
					anchor.href = url;
					anchor.download = 'cropped_image.png';
					anchor.click();
					URL.revokeObjectURL(url);
				});
			});
		});

		// modal and cropper config
		$modal.on('shown.bs.modal', function () {
			cropper = new Cropper(image, {
				preview: '.preview'
			});
		}).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		});

		$('#crop').click(function () {
			canvas = cropper.getCroppedCanvas({
				width: 400,
				height: 400
			});
		});
	})




</script>