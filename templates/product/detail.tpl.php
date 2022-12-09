<?php
$product = $this->product;
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>FW TEST - PRODUCT DETAIL</title>
  
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


  <!-- Custom styles for this template -->
  <link href="css/main.css" rel="stylesheet">

</head>

<body>

  <!-- Page Content -->
  <div class="container">

      <!-- Jumbotron Header -->
      <header class="jumbotron my-4">
        <h1 class="display-3">PRODUCT DETAIL</h1>
      </header>

      <div class="row text-center">

        <div class="card mt-4">
          <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
          <div class="card-body">
            <h3 class="card-title"><?php echo $product->produit_titreobjet; ?></h3>
            <h4>
              <?php
              if ($product->produit_prixremise>0 && $product->produit_prixremise<$product->produit_vente) {
              ?>
                <span><?php echo $product->produit_prixremise ?> &euro;</span>
                <span><strike><?php echo $product->produit_prixvente ?> &euro;</strike></span>
              <?php
              } else {
              ?>
                <span><?php echo $product->produit_prixvente ?> &euro;</span>
              <?php
              }
              ?>
             </h4>
            <p class="card-text"><?php echo $product->produit_description; ?></p>
          </div>
        </div>
        <!-- /.card -->

      </div>
      <!-- /.col-lg-9 -->

  </div>
  <!-- /.container -->

</body>

</html>
