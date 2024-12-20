<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./fontawesome-free-6.6.0-web/css/all.min.css">
  <link rel="stylesheet" href="./webfonts/fa-brands-400.ttf">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/bootstrap.css">
  <link rel="stylesheet" href="./css/main.css">
  <title>home</title>
</head>

<body>
  <div class="container ">
    <div class="row">
      <span id="bg-color"></span>
      <nav class="navbar navbar-expand-lg bg-body-tertiary ">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"> welcome to our home   </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 m-auto">
              <li class="nav-item">
                <a class="nav-link active text-primary" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="users_tracks.php">tracks</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="#">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="#">Pricing</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">Dropdown</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Dropdown 1</a></li>
                  <li><a class="dropdown-item" href="#">Dropdown 2</a></li>
                  <li><a class="dropdown-item" href="#">Dropdown 3</a></li>
                  <li><a class="dropdown-item" href="#">Dropdown 4</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="#">Contact</a>
              </li>
            </ul>
          </div>
          <button onclick="location.href='../login.html'" type="button" class="btn btn-primary p-2">Get Started</button>
        </div>
      </nav>
      <div class="col-md-4">
        <div class="address_home d-flex ">
          <div class="caption">
            <div class="caption_setting d-flex  align-items-center justify-content-center">
              <div class="icon">
                <i class="fa-solid fa-gear text-primary"></i>
              </div>
              <div class="text_setting fw-bold text-primary">
                Working for your success
              </div>
            </div>
            <div class="address">
             <h1>  online learning help  <span class=" text-primary"> you safe the time </span></h1>
            </div>
            <div class="Pragraph">
              <p>Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna.</p>
            </div>
            <div class="buttons">
              <button  onclick="location.href='../login.html'" type="button" class="btn btn-primary  ">Get Started</button>
              <button type="button" class="btn">
                <div class="content_button d-flex">
                  <div class="icon_play">
                    <i class="fa-solid fa-play"></i>
                  </div>
                  <div class="text_button fw-bold">
                    <a href="https://www.youtube.com/">play video</a>  
                  </div>
                </div>
              </button>
            </div>
          </div>
          <div class="img">
            <img src="./img/img_home.webp" alt="img">
            <!-- <div class="box">
              <img src="./img/box1.webp" alt="" >
              <img src="./img/box2.webp" alt="" >
              <img src="./img/box3.webp" alt="" >
              <img src="./img/box4.webp" alt="" >
              <img src="./img/box5.webp" alt="" >
              <h4>12+</h4>
               <p>12,000+ lorem ipsum dolor sit amet consectetur adipiscing elit</p>

            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>







  <script src="./bootstrap.bundle.min.js"></script>
</body>

</html>