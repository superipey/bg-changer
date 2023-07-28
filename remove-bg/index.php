<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Background</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <!-- Main container -->
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="image">Select an image:</label>
            <input type="file" name="image" id="image" required>
            <br>
            <input type="submit" value="Upload">
        </form>

        <!-- PHP code to handle image processing -->
        <?php
        require_once "vendor/autoload.php";
        use GuzzleHttp\Client;

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['image'])) {


            $apiKey = 'dA8RVRnnS56zEuRe6KayzCe5';

            $imgName = $_FILES["image"]["name"];
            $imgPath = './assets/uploads/' . $imgName;
            move_uploaded_file($_FILES['image']['tmp_name'], $imgPath);

            $client = new Client();
            $response = $client->post('https://api.remove.bg/v1.0/removebg', [
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => fopen($imgPath, 'r')
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto'
                    ]
                ],
                'headers' => [
                    'X-Api-Key' => $apiKey
                ]
            ]);

            $outputImagePath = "./assets/output-no-bg/" . $imgName;
            $fp = fopen($outputImagePath, "wb");
            fwrite($fp, $response->getBody());
            fclose($fp);
        ?>

        <!-- Output wrapper -->
        <div class="output-wrapper">

            <!-- Image wrapper -->
            <div class="image-wrapper">
                <img src="<?= $outputImagePath ?>" alt="" class="person-img" id="pImage">
                <img src="./assets/images/style-1.png" alt="" class="bg-img style-1">
            </div>

            <!-- Option wrapper -->
            <div class="option-wrapper">
                <select name="select-bg" id="select-bg">
                    <!-- Dropdown options -->
                    <option value="">Select Style</option>
                    <option value="./assets/images/style-1.png">Style 1</option>
                    <option value="./assets/images/style-2.png">Style 2</option>
                    <option value="./assets/images/style-3.png">Style 3</option>
                    <option value="./assets/images/style-4.png">Style 4</option>
                </select>
                <!-- Download button to save -->
                <button id="download">Download</button>
            </div>
        </div>
        <?php
        }
        ?>
    </div>

    <!-- JavaScript library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Javascript -->
    <script>
        const imageWrapper = document.querySelector('.image-wrapper');
        const selectBtn = document.getElementById('select-bg');
        const bgImage = document.querySelector('.bg-img');
        const downloadBtn = document.getElementById('download');
        const imageElement = document.getElementById("pImage");

        let isDragging = false;
        let offsetX, offsetY;

        selectBtn.addEventListener('change', (e) => {
            const imgPath = e.target.value;
            bgImage.src = imgPath;
        })

        downloadBtn.addEventListener("click", async () => {
            html2canvas(imageWrapper).then((canvas) => {
                const imageURL = canvas.toDataURL();
                const downloadButton = document.createElement('a');
                downloadButton.setAttribute('href', imageURL);
                downloadButton.setAttribute("download", "image.png");
                downloadButton.click();
                downloadButton.remove();
            })
        });

        function handleMouseDown(event) {
            event.preventDefault();

            isDragging = true;

            offsetX = event.clientX - imageElement.offsetLeft;
            offsetY = event.clientY - imageElement.offsetTop;
        }

        function handleMouseMove(event) {
            if (isDragging) {
                const newX = event.clientX - offsetX;
                const newY = event.clientY - offsetY;
                imageElement.style.left = newX + "px";
                imageElement.style.top = newY + "px";
                console.log('hai')
            }
        }

        function handleMouseUp(event) {
            isDragging = false;
        }

        imageElement.addEventListener("mousedown", handleMouseDown);
        document.addEventListener("mousemove", handleMouseMove);
        document.addEventListener("mouseup", handleMouseUp);
    </script>
</body>

</html>