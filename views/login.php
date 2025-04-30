<!doctype html>
<html lang="en">
<head>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/bootstrap/css/bootstrap-grid.min.css" rel="stylesheet">
  <link href="https://kawankerja.id/assets/favicon/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" />
  <link href="https://kawankerja.id/assets/favicon/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" />
  <link href="https://kawankerja.id/assets/favicon/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png" />
  <link href="https://kawankerja.id/assets/favicon/favicon.ico" rel="icon" type="image/ico" />
  <link href="https://kawankerja.id/assets/favicon/site.webmanifest" rel="manifest" />
  <title>Login - Kawan Kerja</title>
</head>
<body>
  <div class="container-xl">
    <section class="vh-100">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
              <div class="row g-0">
                <div class="col-md-12 col-lg-12 d-flex align-items-center">
                  <div class="card-body p-4 p-lg-5 text-black">
                    <form method="post" action="login">
                      <div class="d-flex align-items-center mb-3 pb-1">
                        <i class="fas fa-cubes fa-2x " style="color: #ff6219;"></i>
                        <span class="h1 fw-bold mb-0">Daily 2.0</span>
                      </div>
                      <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Masuk ke Daily 2.0</h5>
                      <?php if ($alert): ?>
                      <div class="alert <?= $alert['status'] === 'FAILED' ? 'alert-danger' : 'alert-primary' ?>" role="alert">
                        <?= $alert['message'] ?>
                      </div>
                      <?php endif ?>

                      <div class="form-outline mb-4">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg" />
                      </div>

                      <div class="form-outline mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-lg" />
                      </div>

                      <div class="form-outline mb-4">
                        <div class="cf-turnstile" data-sitekey="<?= $siteKey ?>" data-callback="captchaSuccess"></div>
                      </div>

                      <div class="pt-1 mb-4">
                        <button class="btn btn-dark btn-lg btn-block" id="loginButton" type="submit" disabled>Login</button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script>
    function captchaSuccess(token) {
      document.getElementById('loginButton').disabled = false;
    }
  </script>
  <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
