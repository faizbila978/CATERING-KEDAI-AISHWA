<?php
  // Logika untuk menampilkan phpinfo()
  if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
      case 'info':
        phpinfo(); 
        exit;
      break;
    }
  }
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laragon</title>
        <link rel="stylesheet" href="koneksidatabase.css">
        <link href="https://fonts.googleapis.com/css?family=Karla:400" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title" title="Laragon">Laragon</div>
     
                <div class="info">
                    <br />
                    <?php echo $_SERVER['SERVER_SOFTWARE']; ?><br />
                    PHP version: <?php echo phpversion(); ?> 
                    <span>
                        <a title="phpinfo()" href="/?q=info">info</a>
                    </span><br />
                    Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?><br />
                </div>

                <div class="opt">
                  <div>
                      <a title="Getting Started" href="https://laragon.org/docs">Getting Started</a>
                  </div>
                </div>
            </div>
        </div>
    </body>
</html>