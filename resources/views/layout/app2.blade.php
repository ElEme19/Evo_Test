<!doctype html>
<html lang="en">
    <head>
        <title>CloudLabs</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

          <link rel="icon" type="image/x-icon" href="{{ asset('images/CloudLabs.png') }}">


        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />


       
    </head>

    <body>
    <section class="vh-100" style="background-color: #0D1117;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
          <div class="row g-1">
            <div class="col-md-10 col-lg-5 d-none d-md-block">
              <img src="{{ asset('images/bici_Evobike.png') }}"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-10 col-lg-6 d-flex align-items-center">
              <div class="card-body p-9 p-lg-4 text-black">

              @yield('fondo')

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
        
    </body>
</html>
