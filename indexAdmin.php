<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- konekcija jquery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <!-- konekcija font awesome css-a -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- konekcija bootstrap-ovog css-a -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- konekcija css-a -->
    <link rel="stylesheet" href="./style.css">

    <title>Admin</title>
</head>
<body>
    
    <!-- nav bar -->
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container-fluid">

            <!-- logo -->
            <a id="logo" class="navbar-brand fa-fade" href="indexAdmin.php">Bookshelf <sup>©</sup></a>

            <div class="collapse navbar-collapse justify-content-evenly" id="navbarSupportedContent">
              
              <!-- dodavanje knjige -->
              <button name="btnDodajKnjigu" id="btnDodajKnjigu" type="button" class="btn btn-outline-dark">Dodaj knjigu</button>

              <!-- izmena knjige -->
              <button name="btnIzmeniKnjigu" id="btnIzmeniKnjigu" type="button" class="btn btn-outline-dark">Izmeni knjigu</button>

              <!-- brisanje knjige -->
              <button name="btnObrisiKnjigu" id="btnObrisiKnjigu" type="button" class="btn btn-outline-dark">Obrisi knjigu</button>

            
              <!-- odjava -->
              <form method="post">
                <button name="odjava" id="odjava" type="submit" class="btn btn-outline-dark">Odjava</button>
              </form>
              <?php
                if(isset($_POST['odjava'])){
                  session_unset();
                  session_destroy();
                  header("Location: login/login.php");
                  exit();
                }
              ?>
            </div>
          </div>
      </nav>

    <script>
      $(document).ready(function(){

        // Prikaz knjiga
        function prikaziKnjige() {
            $.post("./crud/prikaziKnjige.php", function(response){
            $("#prikazKnjiga").html(response);
          });
        }

          prikaziKnjige(); // Poziv funkcije za prikaz


        // Dodavanje knjige
        $("#dodajForma").submit(function(e){
          e.preventDefault();
          let naziv = $("naziv").val();
          let autor = $("autor").val();
          let godinaIzdavanja = $("godinaIzdavanja").val();
          let kategorija = $("kategorija").val();
          $.post("./crud/dodajKnjigu.php", {naziv: naziv, autor:autor, godinaIzdavanja:godinaIzdavanja, kategorija:kategorija}, function(response){
            $("#dodajForma input").val(""); // resetovanje input polja forme
            prikaziKnjige();
          });
        });

        // Izmena knjige
        $("#izmeniForma").submit(function(e){
          e.preventDefault();
          let izborIzmene = $("#izborIzmene").val();
          let naziv = $("#izmeniNaziv").val();
          let autor = $("#izmeniAutor").val();
          let godinaIzdavanja = $("#izmeniGodinaIzdavanja").val();
          let kategorija = $("#izmeniKategorija").val();

          $.post("./crud/izmeniKnjigu.php", {izborIzmene:izborIzmene, naziv: naziv, autor:autor, godinaIzdavanja:godinaIzdavanja, kategorija:kategorija}, function(response){
            $("#izmeniForma input").val(""); // resetovanje input polja forme
            prikaziKnjige();
          });
        });
        
        // Brisanje knjige
        $("#obrisiForma").submit(function(e){
          e.preventDefault();
          let izborBrisanja = $("#izborBrisanja").val();

          $.post("./crud/obrisiKnjigu.php", {izborBrisanja:izborBrisanja}, function(response){
            prikaziKnjige();
          });
        });
            


      })
      </script>


    <!-- DODAVANJE KNJIGA -->

      <div id="dodavanjeKnjige" class="prozor">

        <!-- Forma za dodavanje knjiga u lokalnu bazu podataka -->
        <form id = "dodajForma">
            <label for="naziv">Naziv:</label>
            <input type="text" name="naziv" id="naziv" required><br><br>

            <label for="autor">Autor:</label>
            <input type="text" name="autor" id="autor" required><br><br>

            <label for="godinaIzdavanja">Godina izdavanja:</label>
            <input type="text" name="godinaIzdavanja" id="godinaIzdavanja" required><br><br>

            <label for="kategorija">Kategorija:</label>
            <input type="text" name="kategorija" id="kategorija" required><br><br>

            <input type="hidden" name="admin" id="admin" value="1">
            <!-- samo za test-->

            <button type="submit" name="btnDodaj" id="btnDodaj" value="submit" class="btn btn-outline-dark">Sačuvaj knjigu</button>
            
        </form> 

      </div> 

    


    <!-- PRIKAZ KNJIGA -->
      <div class="container col-12" id="prikazKnjiga"></div>
    


    <!-- IZMENA KNJIGE -->

      <div id="izmenaKnjige" class="prozor">

        <!-- Forma za izmenu knjiga u lokalnoj bazi podataka -->
        <form id="izmeniForma">

          <select name="izborIzmene" id="izborIzmene">
            <!-- DINAMICKI ISPISATI -->
            <?php
              $odgovor="";
              $upit = 'SELECT * FROM knjiga';
              $rez = mysqli_query($database, $upit);
              while($red = mysqli_fetch_assoc($rez))
                $odgovor.="<option value='{$red['ID_KNJIGA']}'>{$red['NAZIV_KNJIGA']}</option>";
              echo $odgovor;
            ?>
          </select>


          <!-- Forma u kojoj treba uneti podatke za izmenu -->
          <h3>Ovde unesite izmene:</h3>

          <label for="naziv">Naziv:</label>
            <input type="text" name="izmeniNaziv" id="izmeniNaziv"><br><br>

            <label for="autor">Autor:</label>
            <input type="text" name="izmeniAutor" id="izmeniAutor"><br><br>

            <label for="godinaIzdavanja">Godina izdavanja:</label>
            <input type="text" name="izmeniGodinaIzdavanja" id="izmeniGodinaIzdavanja"><br><br>

            <label for="kategorija">Kategorija:</label>
            <input type="text" name="izmeniKategorija" id="izmeniKategorija"><br><br>

            <input type="hidden" name="admin" id="admin" value="1">
            <!-- umesto value=1 ce ici vrednost sesije u php tagovima -->
    

          <button type="submit" name="btnIzmeni" id="btnIzmeni" value="submit" class="btn btn-outline-dark">Sačuvaj izmene</button>
            
        </form> 

      </div> 

    <!-- BRISANJE KNJIGE -->

      <div id="brisanjeKnjige" class="prozor">
        <form id="obrisiForma">
          <h3>Ovde izaberite koju knjigu zelite da obrišete:</h3>
          <select name="izborBrisanja" id="izborBrisanja">
            <?php
              $odgovor="";
              $upit = 'SELECT * FROM knjiga';
              $rez = mysqli_query($database, $upit);
              while($red = mysqli_fetch_assoc($rez))
                $odgovor.="<option value='{$red['ID_KNJIGA']}'>{$red['NAZIV_KNJIGA']}</option>";
              echo $odgovor;
            ?>

          </select><br><br>
          
          <button type="submit" name="btnObrisi" id="btnObrisi" value="submit" class="btn btn-outline-dark">Obriši knjigu</button>

        </form>

      </div>

    <!-- OTVARANJE DETALJA KNJIGE -->
      <script>
        $(document).ready(function () {
          $(".knjiga").click(function(){
            let idModal = $(this).attr("id");
            $.post("ajax.php?funkcija=modal", {idModal: idModal}, function(response){
                $("#prikazKnjiga").html(response);
              })
            })
          })

      </script>

    <!-- zatamljenje kada se otvara prozor -->
      <div id="pozadina"></div>

    <!-- konekcija JS fajla koji se bavi animacijama -->
      <script src="scriptAdmin.js"></script>

    <!-- konekcija bootrstrap-ovog JS-a -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>